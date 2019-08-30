<?php

namespace app\modules\manage\controllers\mail;

use Yii;
use app\modules\manage\controllers\AbstractManageController;

class TemplatesController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\MailTemplate';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']['actions']['activate'] = ['post'];
        return $behaviors;
    }

    public function actionIndex()
    {
        $controller_model = $this->__model;

        $data_provider = $this->__model->search(['>=', 'status', $controller_model::STATUS_INACTIVE], ['defaultOrder' => ['id' => SORT_ASC]]);
        return $this->render('index', ['data_provider' => $data_provider, 'search' => $this->__model]);
    }


    public function actionEdit($id = 0)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();
            $model->content = '{{{content}}}';
        }

        $model->scenario = $model::SCENARIO_FORM;

        if ($model->load(Yii::$app->request->post())) {
            $model->save(false);

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/mail/templates'], 301);
            }
        }

        return $this->render('edit', ['model' => $model, 'is_modal' => true]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);
        $model->delete();

        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/mail/templates'], 301);
    }

    public function actionActivate($id)
    {
        $this->checkRules('edit');
        $model = $this->getById($id);

        $controller_model = $this->__model;
        $controller_model::updateAll(['status' => $controller_model::STATUS_INACTIVE], ['status' => $controller_model::STATUS_ACTIVE]);

        $model->status = $controller_model::STATUS_ACTIVE;
        $model->save();

        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));
        return $this->redirect(['/manage/mail/templates'], 301);
    }
}