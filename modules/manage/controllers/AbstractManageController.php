<?php

namespace app\modules\manage\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\components\filters\ActionAdminFilter;
use app\models\ActiveRecord\Page;

/**
 * This class is needed for inheritance by real admin controllers.
 *
 * @abstract
 * @autor Roman Serov <info@inter-projects.ru>
 * @package Yii2
 */
abstract class AbstractManageController extends Controller implements AbstractManageInterface
{
    public $layout = 'admin.php';

    protected $rules, $options;

    /**
     * @inheritdoc
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->getModel();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post'],
                        'restore' => ['post'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'admin'  => [
                    'class' => ActionAdminFilter::className(),
                ],
            ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            $module = Yii::$app->controller->module;
            $this->rules   = $module->params['rules'];
            $this->options = $module->params['options'];

            if ($action->id == 'index') {
                $this->checkRules('view');
            }

            if ($action->id == 'delete' || $action->id == 'restore') {
                $this->checkRules('delete');
            }

            if ($action->id == 'edit') {
                if (preg_match("#/edit/\d#U", $_SERVER['REQUEST_URI'], $findres)) {
                    $this->checkRules('edit');
                } else {
                    $this->checkRules('add');
                }
            }
        }

        return parent::beforeAction($action);
    }

    public function checkRules($action, $model = NULL) {
        if (!$model) {
            $model = $this->__model;
        }

        if (is_object($model)) {
            $model = $model->modelName;
        }

        if (!isset($this->rules[$model]) || !$this->rules[$model]['is_'.$action]) {
            if (Yii::$app->request->isAjax) {
                $this->layout = 'modal';
                $content = $this->render('@app/modules/manage/views/manage/error', ['message' => 'Access is denied to this section for your group.', 'name' => 'Forbidden']);
                die($content);
            } else {
                throw new ForbiddenHttpException(Yii::t('app', 'Access is denied to this section for your group.'), 200);
            }
        }

        return true;
    }

    public function getModel()
    {
        $model = $this->__model;
        $this->__model = new $model;
    }

    public function getById($id, $entity = null)
    {
        if (!$entity) {
            $entity = $this->__model;
        }

        $record = $entity::findOne($id);

        if (!$record) {
            throw new NotFoundHttpException(Yii::t('app', 'Record not found').' ('.Yii::t('app', $entity->entitiesName).', id: '.$id.')');
        }

        return $record;
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);
        $model->status = $model::STATUS_DELETED;
        $model->save(false);
    }

    public function actionRestore($id)
    {
        $model = $this->getById($id);
        $model->status = $model::STATUS_ACTIVE;
        $model->save(false);
    }

    public function render($view, $params = [])
    {
        if (Yii::$app->request->isAjax && isset($params['is_modal']) && $params['is_modal']) {
            $this->layout = 'modal';
        }

        $params['page_model'] = $this->__model;
        $params['rules']      = $this->rules;
        $params['options']    = $this->options;
        $params['form_id'] = strtolower($this->__model->modelName).'_form';
        $params['form_config'] = [
            'options' => [
                'data' => [
                    'pjax' => '1',
                ],
            ],
            'id' => $params['form_id'],
        ];

        $params['submit_options'] = [
            'class' => 'btn btn-round btn-success'
        ];

        if (Yii::$app->request->isAjax) {
            $params['pjax_conf'] = [
                'enablePushState' => false,
            ];

            $params['submit_options']['onclick'] = "\$('#".$params['form_id']."').submit(); return false";
        }

        return parent::render($view, $params);
    }

    public function updatePagesByWidget($widget)
    {
        $pages = Page::find()->where(['like', 'page_content', '{{{widget|'.$widget.'}}}'])->orWhere(['like', 'page_content', '{{{widget|'.$widget.'|'])->all();

        foreach ($pages AS $page) {
            $page->updatePage();
            $page->save();
        }
    }
}