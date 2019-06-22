<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use app\components\filters\ActionAdminFilter;
use app\models\ActiveRecord\Page;

class AccountMenuWidget extends Widget
{
    public static $name = 'Кнопка личного кабинета';

    public $call_model, $controller;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $account_menu = '';

        if (ActionAdminFilter::checkAdminRules()) {
            $account_menu .= Html::a('Управление', ['/manage']);
        }

        if (!Yii::$app->user->isGuest) {
            $content = Page::findByAddress('/system_pages/account_menu', false);

            if ($content) {
                $account_menu .= $content->getHtmlContent($content->page_content);
            }
        }

        return $this->render('account_menu', ['account_menu' => $account_menu]);
    }
}