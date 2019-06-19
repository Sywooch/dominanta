<?php

namespace app\modules\manage\controllers\mail;

use Yii;
use app\modules\manage\controllers\AbstractManageController;

class QueueController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Mail';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']['actions']['resend'] = ['post'];
        return $behaviors;
    }

    public function actionIndex()
    {
        $controller_model = $this->__model;

        $data_provider = $this->__model->search(['>=', 'status', $controller_model::STATUS_INACTIVE], ['defaultOrder' => ['create_time' => SORT_DESC]]);
        return $this->render('index', ['data_provider' => $data_provider, 'search' => $this->__model]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);
        $model->delete();

        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/mail/queue'], 301);
    }

    public function actionResend($id)
    {
        $this->checkRules('edit');
        $controller_model = $this->__model;

        $model = $this->getById($id);
        $model->status = $controller_model::STATUS_INACTIVE;
        $model->save();

        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));
        return $this->redirect(['/manage/mail/queue'], 301);
    }

}