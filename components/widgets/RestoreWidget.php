<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ActiveRecord\Mail;
use app\models\ActiveRecord\User;

class RestoreWidget extends Widget
{
    public static $name = 'Форма восстановления пароля';

    public $call_model, $controller;

    public $form_name = 'restore_form';


    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        if (!Yii::$app->user->isGuest) {
            return '';
        }

        $model = [
            'restore' => new User(['scenario' => User::SCENARIO_RESTORE])
        ];

        $form = Yii::$app->request->post('sended_form', false);

        if ($form == $this->form_name && User::loadMultiple($model, Yii::$app->request->post()) && User::validateMultiple($model)) {
            $find_user = User::find()->where(['email' => $model['restore']->email_or_phone])
                                     ->orWhere(['phone' => '+'.str_replace('-', '', $model['restore']->email_or_phone)])
                                     ->one();

            if ($find_user && $find_user->status == User::STATUS_ACTIVE) {
                $link = Url::to(['restore', 'token' => $find_user->access_token], true);

                Mail::createAndSave([
                    'to_email'  => $find_user->email,
                    'subject'   => 'Восстановление пароля на сайте '.ucfirst($_SERVER['SERVER_NAME']),
                    'body_text' => 'Для вашего аккаунта на сайте '.$_SERVER['SERVER_NAME'].' было запрошено восстановление пароля.'.PHP_EOL.PHP_EOL.
                                   'Для восстановления пароля перейдите, пожалуйста, по ссылке - '.$link.PHP_EOL.PHP_EOL.
                                   'Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.',
                    'body_html' => 'Для вашего аккаунта на сайте '.$_SERVER['SERVER_NAME'].' было запрошено восстановление пароля.<br /><br />'.
                                   'Для восстановления пароля перейдите, пожалуйста, по ссылке - '.
                                    Html::a($link, $link).'<br /><br />Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.',
                ]);

                return $this->render('restore', [
                    'model' => false,
                    'error' => false,
                ]);
            } else {
                return $this->render('restore', [
                    'model' => $model,
                    'error' => true,
                ]);
            }
        }

        return $this->render('restore', [
            'model' => $model,
            'error' => false,
        ]);
    }
}