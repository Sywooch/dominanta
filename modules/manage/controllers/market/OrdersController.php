<?php

namespace app\modules\manage\controllers\market;

use Yii;
use yii\helpers\Html;
use app\modules\manage\controllers\AbstractManageController;
use app\models\ActiveRecord\Product;
use app\models\ActiveRecord\Property;
use app\models\ActiveRecord\ShopOrderPosition;

class OrdersController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\ShopOrder';

    public function actionIndex($status = NULL)
    {
        $controller_model = $this->__model;

        if ($status == $controller_model::STATUS_DELETED) {
            $cond = ['status' => $status];
        } else {
            $cond = [
                'query' => [
                    ['!=', 'status', $controller_model::STATUS_DELETED],
                ]
            ];
        }

        $data_provider = $controller_model->search($cond, ['defaultOrder' => ['add_time' => SORT_DESC]]);

        return $this->render('index', [
            'data_provider' => $data_provider,
            'search' => $this->__model,
            'model' => $controller_model,
            'status' => $status,
        ]);
    }


    public function actionEdit($id)
    {
        if ($id) {
            $model = $this->getById($id);
        }

        $model->scenario = $model::SCENARIO_EDIT;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/market/orders'], 301);
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'is_modal' => true,
        ]);
    }

    public function actionDelete($id)
    {
        $model = parent::actionDelete($id);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/market/orders'], 301);
    }
}