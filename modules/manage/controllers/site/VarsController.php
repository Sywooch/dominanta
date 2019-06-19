<?php

namespace app\modules\manage\controllers\site;

use Yii;
use app\modules\manage\controllers\AbstractManageController;

class VarsController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Variable';

    public function actionIndex()
    {
        $data_provider = $this->__model->search([], ['defaultOrder' => ['name' => SORT_ASC]]);
        return $this->render('index', ['data_provider' => $data_provider, 'search' => $this->__model]);
    }


    public function actionEdit($id = 0)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();
        }

        $model->scenario = $model::SCENARIO_FORM;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/site/vars'], 301);
            }
        }

        return $this->render('edit', ['model' => $model, 'is_modal' => true]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);
        $model->delete();

        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/site/vars'], 301);
    }
}