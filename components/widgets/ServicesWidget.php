<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Page;

class ServicesWidget extends Widget
{
    public static $name = 'Список услуг';

    public $call_model, $controller;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $services = Page::find()->where(['pid' => $this->call_model->id])
                                ->andWhere(['status' => Page::STATUS_ACTIVE])
                                ->all();

        return $this->render('services', ['services' => $services]);
    }
}