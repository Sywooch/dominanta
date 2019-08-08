<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Callback;

class CallbackWidget extends Widget
{
    public static $name = 'Форма обратного звонка';

    public $call_model, $controller;

    public $form_name = 'callback_form';


    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $model = new Callback();

        $form = Yii::$app->request->post('sended_form', false);

        if ($form == $this->form_name && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();

            return $this->render('callback', [
                'model' => false,
            ]);
        }

        return $this->render('callback', [
            'model' => $model,
        ]);
    }
}