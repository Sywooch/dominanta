<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\controllers\ShopcartController;
use app\models\ActiveRecord\Product;

class ShopcartWidget extends Widget
{
    public static $name = 'Корзина';

    public $call_model, $controller;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {

        $shopcart = Yii::$app->shopcart->getItems();

        $cnt = 0;
        $sum = 0;

        foreach ($shopcart AS $item) {
            if ($item->product->status != Product::STATUS_ACTIVE) {
                $item->delete();
            }

            $cnt += $item->quantity;
            $sum += ($item->product->price - ($item->product->price * ($item->product->discount / 100))) * $item['quantity'];
        }


        return $this->render('shopcart', [
            'cnt' => $cnt,
            'sum' => Yii::$app->formatter->asDecimal($sum, 2),
        ]);
    }
}