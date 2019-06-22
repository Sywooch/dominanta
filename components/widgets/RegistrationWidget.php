<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\User;

class RegistrationWidget extends Widget
{
    public static $name = 'Форма регистрации';

    public $call_model, $controller;

    public $form_name = 'reg_form';


    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        if (!Yii::$app->user->isGuest) {
            return '';
        }

        $model =  new User(['scenario' => User::SCENARIO_REG]);

        $form = Yii::$app->request->post('sended_form', false);

        if ($form == $this->form_name && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();

            return $this->render('registration', [
                'model' => false,
            ]);
        }

        return $this->render('registration', [
            'model' => $model,
        ]);
    }
}