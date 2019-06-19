<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ActiveRecord\Option;
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
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
    public function actionPage($page)
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

        $this->page = Page::findByAddress($page);

        if (!$this->page) {
            Yii::$app->response->statusCode = 404;
            $this->page = Page::findByAddress('404'.$page_extension);
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
