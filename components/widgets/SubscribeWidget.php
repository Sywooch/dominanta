<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Subscriber;

class SubscribeWidget extends Widget
{
    public static $name = 'Форма подписки на новости';

    public $call_model, $controller;

    public $form_name = 'subscribe_form';


    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $model = new Subscriber();

        $form = Yii::$app->request->post('sended_form', false);

        if ($form == $this->form_name && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();

            return $this->render('subscribe', [
                'model' => new Subscriber(),
                'success' => true,
            ]);
        }

        return $this->render('subscribe', [
            'model' => $model,
            'success' => false,
        ]);
    }
}