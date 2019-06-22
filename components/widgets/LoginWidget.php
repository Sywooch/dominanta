<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\User;

class LoginWidget extends Widget
{
    public static $name = 'Форма входа';

    public $call_model, $controller;

    public $form_name = 'login_form';


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
            'login' => new User(['scenario' => User::SCENARIO_LOGIN])
        ];

        $form = Yii::$app->request->post('sended_form', false);

        if ($form == $this->form_name && User::loadMultiple($model, Yii::$app->request->post()) && $model['login']->login()) {
            $account_menu = '';
            $content = Page::findByAddress('/system_pages/account_menu', false);

            if ($content) {
                $account_menu = $content->getHtmlContent($content->page_content);
            }


            return $this->render('login', [
                'model' => false,
                'account_menu' => $account_menu,
            ]);
        }

        return $this->render('login', [
            'model' => $model,
            'account_menu' => false,
        ]);
    }
}