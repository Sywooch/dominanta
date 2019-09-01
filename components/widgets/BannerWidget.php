<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Banner;

class BannerWidget extends Widget
{
    public static $name = 'Виджет товаров';

    public $call_model, $controller;

    public $interval = false;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $banners = Banner::find()->where(['status' => Banner::STATUS_ACTIVE])->all();

        if ($banners) {
            return $this->render('banner', [
                'banners' => $banners,
                'interval' => $this->interval,
            ]);
        } else {
            return '';
        }
    }
}