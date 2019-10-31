<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\ActiveRecord\Mail;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\Subscriber;
use app\models\ActiveRecord\User;

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
            $user->save(false);

            Mail::createAndSave([
                'to_email'  => $user->email,
                'subject'   => 'Восстановление пароля на сайте '.ucfirst($_SERVER['SERVER_NAME']),
                'body_text' => $newPass,
                'body_html' => $newPass,
            ], 'restore');

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
     * Activate subscribe.
     *
     * @return Response|string
     */
    public function actionSubscribe($token)
    {
        $user = Subscriber::findOne(['hash' => $token, 'status' => Subscriber::STATUS_INACTIVE]);

        if (!$user) {
            $page = $this->actionPage('/info_pages/subscribe_fail', false);
        } else {
            $user->status = Subscriber::STATUS_ACTIVE;
            $user->hash = Yii::$app->security->generateRandomString();
            $user->save();
            $page = $this->actionPage('/info_pages/subscribe_success', false);
        }

        return $page;
    }

    /**
     * Activate unsubscribe.
     *
     * @return Response|string
     */
    public function actionUnsubscribe($token)
    {
        $user = Subscriber::findOne(['hash' => $token]);

        if (!$user) {
            $page = $this->actionPage('/info_pages/unsubscribe_fail', false);
        } else {
            $user->delete();
            $page = $this->actionPage('/info_pages/unsubscribe_success', false);
        }

        return $page;
    }
}
