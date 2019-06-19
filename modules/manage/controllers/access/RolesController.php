<?php

namespace app\modules\manage\controllers\access;

use Yii;
use app\modules\manage\controllers\AbstractManageController;
use app\components\helpers\ModelsHelper;
use app\models\ActiveRecord\User;
use app\models\ActiveRecord\Rule;


class RolesController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Role';

    protected $skip_models = [
        'PageCss',
        'PageJs',
        'TemplateCss',
        'TemplateJs',
    ];

    private function getTree($status = 0, $pid = NULL)
    {
        $controller_model = $this->__model;
        $query = $controller_model::find()->where(['status' => $status]);

        if ($status == $controller_model::STATUS_ACTIVE) {
            $query->andWhere(['pid' => $pid]);
        }

        $roles = $query->all();

        if ($status == $controller_model::STATUS_ACTIVE) {
            foreach ($roles AS $idx => $role) {
                $role->subroles = $this->getTree($status, $role->id);
            }
        }

        return $roles;
    }

    public function actionIndex($status = NULL)
    {
        $controller_model = $this->__model;

        if ($status === NULL) {
            $status = $controller_model::STATUS_ACTIVE;
        }

        return $this->render('index', ['roles' => $this->getTree($status), 'status' => $status]);
    }

    public function actionEdit($id = 0, $pid = 0)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();

            if ($pid) {
                $model->pid = $pid;
            }
        }

        $models = ModelsHelper::get();
        $rules = [];

        foreach ($model->rules AS $rule) {
            $rules[$rule->model] = [
                'modelname' => $models[$rule->model]['realname'],
                'is_view' => $rule->is_view,
                'is_add' => $rule->is_add,
                'is_edit' => $rule->is_edit,
                'is_delete' => $rule->is_delete,
                'is_exists' => true,
            ];

            unset($models[$rule->model]);
        }

        foreach ($models AS $one_model_name => $one_model) {
            $rules[$one_model_name] = [
                'modelname' => $one_model['realname'],
                'is_view' => 0,
                'is_add' => 0,
                'is_edit' => 0,
                'is_delete' => 0,
                'is_exists' => false,
            ];
        }

        foreach ($this->skip_models AS $one_model) {
            unset($rules[$one_model]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();

            if (!$model->is_admin) {
                Rule::deleteAll(['role_id' => $model->id]);
            } else {
                $rules_saved = Yii::$app->request->post('rule', []);

                foreach ($rules_saved AS $rule_model => $rule_data) {
                    if (isset($rules[$rule_model]) && $rules[$rule_model]['is_exists']) {
                        $update_rule = [];

                        $is_view   = isset($rule_data['is_view']) ? $rule_data['is_view'] : 0;
                        $is_add    = isset($rule_data['is_add']) ? $rule_data['is_add'] : 0;
                        $is_edit   = isset($rule_data['is_edit']) ? $rule_data['is_edit'] : 0;
                        $is_delete = isset($rule_data['is_delete']) ? $rule_data['is_delete'] : 0;

                        if ($is_view != $rules[$rule_model]['is_view']) {
                            $update_rule['is_view'] = $is_view;
                        }

                        if ($is_add != $rules[$rule_model]['is_add']) {
                            $update_rule['is_add'] = $is_add;
                        }

                        if ($is_edit != $rules[$rule_model]['is_edit']) {
                            $update_rule['is_edit'] = $is_edit;
                        }

                        if ($is_delete != $rules[$rule_model]['is_delete']) {
                            $update_rule['is_delete'] = $is_delete;
                        }

                        if ($update_rule) {
                            Rule::updateAll($update_rule, ['role_id' => $model->id, 'model' => $rule_model]);
                        }

                        unset($rules[$rule_model]);
                    } else {
                        Rule::createAndSave([
                            'role_id'   => $model->id,
                            'model'     => $rule_model,
                            'is_view'   => isset($rule_data['is_view']) ? $rule_data['is_view'] : 0,
                            'is_add'    => isset($rule_data['is_add']) ? $rule_data['is_add'] : 0,
                            'is_edit'   => isset($rule_data['is_edit']) ? $rule_data['is_edit'] : 0,
                            'is_delete' => isset($rule_data['is_delete']) ? $rule_data['is_delete'] : 0,
                        ]);
                    }
                }
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/access/roles'], 301);
            }
        }

        return $this->render('edit', ['model' => $model, 'is_modal' => true, 'role_rules' => $rules]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);

        if ($model::find()->where(['pid' => $id])->andWhere(['status' => $model::STATUS_ACTIVE])->count()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'You must first delete all child roles'));
        } elseif (User::find()->where(['role_id' => $id])->count()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'First, you must remove all users of this role or move them to another role'));
        } else {
            $model->status = $model::STATUS_DELETED;
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Role has been deleted'));
        }

        return $this->redirect(['/manage/access/roles'], 301);
    }

    public function actionRestore($id)
    {
        $model = $this->getById($id);
        $model->status = $model::STATUS_ACTIVE;
        $model->save();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Role has been restored'));
        return $this->redirect(['/manage/access/roles'], 301);
    }
}
