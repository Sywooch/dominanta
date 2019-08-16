<?php

namespace app\modules\manage\controllers\market;

use Yii;
use yii\helpers\Html;
use app\modules\manage\controllers\AbstractManageController;
use app\models\ActiveRecord\Product;
use app\models\ActiveRecord\Property;
use app\models\ActiveRecord\ShopOrderPosition;

class LabelsController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\ProductLabel';

    public function actionIndex()
    {
        $controller_model = $this->__model;
        $data_provider = $controller_model->search([], ['defaultOrder' => ['label' => SORT_ASC]]);

        return $this->render('index', [
            'data_provider' => $data_provider,
            'search' => $this->__model,
            'model' => $controller_model,
        ]);
    }


    public function actionEdit($id)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();
        }

        $model->scenario = $model::SCENARIO_FORM;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/market/labels'], 301);
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'is_modal' => true,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/market/labels'], 301);
    }
}