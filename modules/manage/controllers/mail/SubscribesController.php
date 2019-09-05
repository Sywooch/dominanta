<?php

namespace app\modules\manage\controllers\mail;

use Yii;
use app\modules\manage\controllers\AbstractManageController;
use app\models\ActiveRecord\MailTemplate;
use app\models\ActiveRecord\Subscriber;

class SubscribesController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Subscribe';

    public function actionIndex()
    {
        $controller_model = $this->__model;

        $data_provider = $this->__model->search(['>=', 'status', $controller_model::STATUS_INACTIVE], ['defaultOrder' => ['id' => SORT_DESC]]);
        return $this->render('index', [
            'data_provider' => $data_provider,
            'search' => $this->__model,
        ]);
    }


    public function actionEdit($id = 0)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();

            $default_template = MailTemplate::findOne(['slug' => 'default']);

            if ($default_template) {
                $model->mail_text = str_replace('{{{content}}}', '<br /><br />', $default_template->content);
            }
        }

        $model->scenario = $model::SCENARIO_FORM;

        if ($model->load(Yii::$app->request->post())) {
            $model->save(false);

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/mail/subscribes'], 301);
            }
        }

        return $this->render('edit', ['model' => $model, 'is_modal' => true]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);
        $model->delete();

        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/mail/subscribes'], 301);
    }
}