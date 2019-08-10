<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\components\filters\ActionAdminFilter;
use app\models\ActiveRecord\Page;

class AbstractController extends Controller
{
    protected $registred_css = [];

    protected $registred_js  = [];

    /**
     * @inheritdoc
     */
    public function actions()
    {
        if (strpos(Url::to(), '/manage/') === 0 && ActionAdminFilter::checkAdminRules()) {
            return [
                'error' => [
                    'class' => 'app\modules\manage\actions\ManageErrorAction',
                    'view' => '@yiister/gentelella/views/error',
                ],
            ];
        } else {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                    'view' => '@app/views/site/error',
                ],
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (strpos(Url::to(), '/manage/') === 0 && ActionAdminFilter::checkAdminRules()) {
            $this->layout = '@app/modules/manage/views/layouts/admin';
        } elseif ($action->id == 'error') {
            $this->layout = '@app/views/layouts/simple';
        }

        return parent::beforeAction($action);
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
        $is_main_page = false;

        $site_options = Yii::$app->site_options;

        $page_extension = isset($site_options->page_extension) ? trim($site_options->page_extension) : '';

        if (!$page) {
            $is_main_page = true;
            $page = $site_options->main_page.$page_extension;
        }

        if (!$page) {
            $is_main_page = true;
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
            $replace = [
                '{{{breadcrumbs}}}' => $is_main_page ? '' : $this->getBreadcrumbs($this->page),
                '{{{page_title}}}' => $is_main_page ? '' : $this->page->page_name,
            ];

            $page_content = str_replace(array_keys($replace), $replace, $page_content);


            return $page_content;
        } else {
            throw new NotFoundHttpException();
        }
    }

    protected function getBreadcrumbs($page)
    {
        return $this->renderPartial('breadcrumbs', ['links' => $page->breadcrumbs]);
    }
}
