<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\User;

class AccountController extends AbstractController
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

    public function actionIndex($url = '')
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException();
        }

        $url = trim($url, '/');

        if ($url == '') {
            $url = 'index';
        }

        $site_options = Yii::$app->site_options;
        $page_extension = isset($site_options->page_extension) ? trim($site_options->page_extension) : '';

        if (method_exists(self::className(), $url)) {
            return $this->$url();
        } else {
            $this->page = Page::findByAddress('/account/'.$url, false);

            if (!$this->page) {
                Yii::$app->response->statusCode = 404;
                $this->page = Page::findByAddress('404'.$page_extension, false);
            }

            if (!$this->page) {
                throw new NotFoundHttpException();
            }

            $request = Yii::$app->getRequest();
            $page_content = $this->render('page', [
                'page' => $this->page,
                'controller' => $this,
                'site_options' => $site_options,
                'csrfParam' => $request->csrfParam,
                'csrfToken' => $request->getCsrfToken(),
            ]);

            return $page_content;
        }
    }

    protected function index()
    {
        $account_form = $this->accountForm();

        if ($account_form == 'redirect') {
            return $this->redirect(['/account'], 301);
        }

        $password_form = $this->passwordForm();

        if ($password_form == 'redirect') {
            return $this->redirect(['/account'], 301);
        }

        $this->page = Page::findByAddress('/account/index', false);

        if ($this->page->template) {
            $this->layout = $this->page->template->layout;
        }

        $site_options = Yii::$app->site_options;
        $request = Yii::$app->getRequest();
        $rendered_page = $this->render('page', [
            'page' => $this->page,
            'controller' => $this,
            'site_options' => $site_options,
            'csrfParam' => $request->csrfParam,
            'csrfToken' => $request->getCsrfToken(),
        ]);

        $replace = [
            '{{{breadcrumbs}}}' => '',
            '{{{page_title}}}' => $this->page->title,
            '{{{account_menu}}}' => $this->accountMenu('index'),
            '{{{account_form}}}' => $account_form,
            '{{{password_form}}}' => $password_form,
        ];

        return str_replace(array_keys($replace), $replace, $rendered_page);
    }

    protected function orders()
    {
        $this->page = Page::findByAddress('/account/orders', false);

        if ($this->page->template) {
            $this->layout = $this->page->template->layout;
        }

        $site_options = Yii::$app->site_options;
        $request = Yii::$app->getRequest();
        $rendered_page = $this->render('page', [
            'page' => $this->page,
            'controller' => $this,
            'site_options' => $site_options,
            'csrfParam' => $request->csrfParam,
            'csrfToken' => $request->getCsrfToken(),
        ]);

        $replace = [
            '{{{breadcrumbs}}}' => '',
            '{{{page_title}}}' => $this->page->title,
            '{{{account_menu}}}' => $this->accountMenu('orders'),
        ];

        return str_replace(array_keys($replace), $replace, $rendered_page);
    }

    protected function delivery()
    {
        $this->page = Page::findByAddress('/account/delivery', false);

        if ($this->page->template) {
            $this->layout = $this->page->template->layout;
        }

        $site_options = Yii::$app->site_options;
        $request = Yii::$app->getRequest();
        $rendered_page = $this->render('page', [
            'page' => $this->page,
            'controller' => $this,
            'site_options' => $site_options,
            'csrfParam' => $request->csrfParam,
            'csrfToken' => $request->getCsrfToken(),
        ]);

        $replace = [
            '{{{breadcrumbs}}}' => '',
            '{{{page_title}}}' => $this->page->title,
            '{{{account_menu}}}' => $this->accountMenu('delivery'),
        ];

        return str_replace(array_keys($replace), $replace, $rendered_page);
    }

    protected function accountForm()
    {
        $model = Yii::$app->user->identity;
        $model->scenario = $model::SCENARIO_ACCOUNT;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i> '.Yii::t('app', 'Data has been saved'));
            return 'redirect';
        }

        return $this->renderPartial('account', ['model' => $model]);
    }

    protected function passwordForm()
    {
        $model = Yii::$app->user->identity;
        $model->scenario = $model::SCENARIO_ACCOUNT_PASSWORD;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPassword($model->password);
            $model->save(false);
            Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i> '.Yii::t('app', 'Data has been saved'));
            return 'redirect';
        }

        $model->password = '';

        return $this->renderPartial('password', ['model' => $model]);
    }

    protected function accountMenu($active_link)
    {
        return $this->renderPartial('menu', ['active_link' => $active_link]);;
    }
}
