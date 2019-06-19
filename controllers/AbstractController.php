<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\components\filters\ActionAdminFilter;

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
}
