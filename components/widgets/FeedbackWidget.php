<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Feedback;

class FeedbackWidget extends Widget
{
    public static $name = 'Форма обратной связи';

    public $call_model, $controller;

    public $form_name = 'feedback_form';


    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $model = new Feedback();

        $form = Yii::$app->request->post('sended_form', false);

        if ($form == $this->form_name && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();

            return $this->render('feedback', [
                'model' => false,
            ]);
        }

        return $this->render('feedback', [
            'model' => $model,
        ]);
    }
}