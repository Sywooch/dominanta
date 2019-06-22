<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ActiveRecord\Option;
use app\models\ActiveRecord\Mail;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\User;
use app\modules\manage\controllers\site\SettingsController;

class SiteController extends AbstractController
{
    /** @var app\models\ActiveRecord\Page */
    protected $page = false;

    //public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->actionPage('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'simple';

        $model = new User(['scenario' => User::SCENARIO_LOGIN]);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Activate action.
     *
     * @return Response|string
     */
    public function actionActivate($token)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = User::findIdentityByAccessToken($token, User::STATUS_INACTIVE);

        if (!$user) {
            $page = $this->actionPage('/info_pages/activate_fail', false);
        } else {
            $user->activateUser();
            $user->loginUser();
            $page = $this->actionPage('/info_pages/activate_success', false);
        }

        return $page;
    }

    /**
     * Restore action.
     *
     * @return Response|string
     */
    public function actionRestore($token)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = User::findIdentityByAccessToken($token);

        if (!$user) {
            $page = $this->actionPage('/info_pages/restore_fail', false);
        } else {
            $newPass = Yii::$app->security->generateRandomString(8);
            $user->setPassword($newPass);
            $user->refreshAuthkey();
            $user->save();

            Mail::createAndSave([
                'to_email'  => $user->email,
                'subject'   => 'Восстановление пароля на сайте '.ucfirst($_SERVER['SERVER_NAME']),
                'body_text' => 'Новый пароль вашего аккаунта на сайте '.$_SERVER['SERVER_NAME'].':'.PHP_EOL.PHP_EOL.$newPass,
                'body_html' => 'Новый пароль вашего на сайте '.$_SERVER['SERVER_NAME'].':<br /><br />'.$newPass,
            ]);

            $page = $this->actionPage('/info_pages/restore_success', false);
        }

        return $page;
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Page action.
     *
     * @param $page string
     * @return Response
     */
    public function actionPage($page, $only_active = true)
    {
        $page_content = false;

        $site_options = Option::find()->where(['option' => array_keys(SettingsController::$site_options)])->indexBy('option')->all();

        $page_extension = isset($site_options['page_extension']) ? trim($site_options['page_extension']->option_value) : '';

        if (!$page) {
            $page = $site_options['main_page']->option_value.$page_extension;
        }

        if (!$page) {
            $page = 'index'.$page_extension;
        }

        $this->page = Page::findByAddress($page, $only_active);

        if (!$this->page) {
            Yii::$app->response->statusCode = 404;
            $this->page = Page::findByAddress('404'.$page_extension, $only_active);
        }

        if (!$this->page) {
            throw new NotFoundHttpException();
        }

        if ($this->page->template) {
            $this->layout = $this->page->template->layout;
        }

        $request = Yii::$app->getRequest();
        $page_content = $this->render('page', [
            'page' => $this->page,
            'controller' => $this,
            'site_options' => $site_options,
            'csrfParam' => $request->csrfParam,
            'csrfToken' => $request->getCsrfToken(),
        ]);

        if ($page_content) {
            return $page_content;
        } else {
            throw new NotFoundHttpException();
        }
    }
}
