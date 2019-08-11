<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class SearchtextWidget extends Widget
{
    public static $name = 'Текст поиска';

    public $call_model, $controller;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        return Html::encode(Yii::$app->request->get('searchtext', ''));
    }
}