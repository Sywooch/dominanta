<?php

namespace app\modules\manage\controllers\access;

use Yii;
use yii\helpers\Url;
use app\modules\manage\controllers\AbstractManageController;
use app\components\helpers\ModelsHelper;

class UsersController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\User';

    public function actionIndex($status = NULL)
    {
        $controller_model = $this->__model;

        if ($status === NULL) {
            $status = $controller_model::STATUS_ACTIVE;
        }

        $this->__model->scenario = $controller_model::SCENARIO_SEARCH;
        $data_provider = $this->__model->search(['status' => $status], ['defaultOrder' => ['create_time' => SORT_DESC]]);
        return $this->render('index', ['data_provider' => $data_provider, 'search' => $this->__model, 'status' => $status]);
    }

    public function actionEdit($id = 0)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();
            $model->status = $model::STATUS_ACTIVE;
        }

        $controller_model = $this->__model;

        if (!$id) {
            $model->scenario = $controller_model::SCENARIO_ADD;
            $sel_model = 'add';
        } else {
            $sel_model = 'edit';

            $scenarios = [
                $controller_model::SCENARIO_EDIT,
                $controller_model::SCENARIO_PASSWORD,
                $controller_model::SCENARIO_SETTINGS,
            ];

            $models = [];

            foreach ($scenarios AS $sc) {
                $models[$sc] = clone $model;
                $models[$sc]->scenario = $sc;

                if ($sc == $controller_model::SCENARIO_PASSWORD) {
                    $models[$sc]->password = '';
                }
            }

            $model = $models;
        }

        if (Yii::$app->request->post()) {
            if (!$id) {
                $save_model = $model;
            } else {
                $save_model = $model[Yii::$app->request->post('scenario', 'edit')];
                $sel_model  = $save_model->scenario;
            }

            if ($save_model->load(Yii::$app->request->post()) && $save_model->validate()) {
                if ($save_model->scenario == $save_model::SCENARIO_PASSWORD) {
                    $save_model->setPassword($save_model->password);
                }

                if ($save_model->scenario == $save_model::SCENARIO_SETTINGS) {
                    $notifies = [];

                    if (is_array($save_model->notify)) {
                        foreach ($save_model->notify AS $n_key => $n_value) {
                            if ($n_value) {
                                $notifies[] = $n_key;
                            }
                        }
                    }

                    $save_model->notify = ','.implode(',', $notifies).',';
                }

                $save_model->save(false);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

                if (Yii::$app->request->isPjax) {
                    $model = false;
                } else {
                    return $this->redirect(['/manage/access/users'], 301);
                }
            } else {
              die('NOVALID');
            }
        }

        $notify = [];
        $models = ModelsHelper::get();

        if ($model && $id) {
            foreach ($models AS $modelName => $oneModel) {

                $n_model = $oneModel['classname'];

                if ($n_model::$notify) {
                    $notify[$modelName] = [
                        'name' => $n_model::$notify,
                        'checked' => (strpos($model[$controller_model::SCENARIO_SETTINGS]->notify, $modelName) !== false),
                    ];
                }
            }
        }

        return $this->render('edit', ['model' => $model, 'sel_model' => $sel_model, 'is_modal' => true, 'notify' => $notify]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);

        if (Yii::$app->user->identity->id == $id) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'You can not delete the current user'));
        } else {
            $model->status = $model::STATUS_DELETED;
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'User has been deleted'));
        }

        return $this->redirect(['/manage/access/users'], 301);
    }

    public function actionRestore($id)
    {
        parent::actionRestore($id);
        Yii::$app->session->setFlash('success', Yii::t('app', 'User has been restored'));
        return $this->redirect(['/manage/access/users'], 301);
    }
}
