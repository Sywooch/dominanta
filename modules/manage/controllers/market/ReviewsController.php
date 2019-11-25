<?php

namespace app\modules\manage\controllers\market;

use Yii;
use yii\helpers\Html;
use app\modules\manage\controllers\AbstractManageController;
use app\models\ActiveRecord\Product;

class ReviewsController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\ProductReview';

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

    public function actionDelete($id)
    {
        $model = parent::actionDelete($id);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/market/reviews'], 301);
    }

    public function actionApprove($id)
    {
        $model = $this->getById($id);
        $model->status = $model::STATUS_ACTIVE;
        $model->approver = Yii::$app->user->identity->id;
        $model->approved = $model->dbTime;
        $model->save(false);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been published'));
        return $this->redirect(['/manage/market/reviews'], 301);
    }
}