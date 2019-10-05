<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\controllers\ShopcartController;
use app\models\ActiveRecord\Product;
use app\models\ActiveRecord\Shopcart;

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
        $shopcart_data = ShopcartController::getShopcartData();
        $user_id = $shopcart_data['user_id'];
        $hash    = $shopcart_data['hash'];

        if ($user_id) {
            $shopcart = Shopcart::find()->where(['user_id' => $user_id])->all();
        } else {
            $shopcart = Shopcart::find()->where(['hash' => $hash])->all();
        }

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