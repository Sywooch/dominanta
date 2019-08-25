<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\controllers\ShopcartController;
use app\models\ActiveRecord\Product;
use app\models\ActiveRecord\ProductLabel;
use app\models\ActiveRecord\ProductLabels;
use app\models\ActiveRecord\ProductResently;

class ProductWidget extends Widget
{
    public static $name = 'Виджет товаров';

    public $call_model, $controller, $filter, $header;

    public $interval = false;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $products = [];

        if ($this->filter == 'resently') {
            $shopcart = ShopcartController::getShopcartData();

            $rQuery = Product::find()->where(['status' => Product::STATUS_ACTIVE])
                                     ->innerJoin(ProductResently::tableName(), ProductResently::tableName().'.product_id='.Product::tableName().'.id')
                                     ->where(['!=', 'product_id', $this->call_model->product_id]);

            if ($shopcart['user_id']) {
                $rQuery->andWhere(['user_id' => $shopcart['user_id']]);
            } else {
                $rQuery->andWhere(['hash' => $shopcart['hash']]);
            }

            $products = $rQuery->orderBy(['add_time' => SORT_DESC])->limit(12)->all();
        } else {
            $products = Product::find()->where(['status' => Product::STATUS_ACTIVE])
                                     ->innerJoin(ProductLabels::tableName(), ProductLabels::tableName().'.product_id='.Product::tableName().'.id')
                                     ->innerJoin(ProductLabel::tableName(), ProductLabels::tableName().'.label_id='.ProductLabel::tableName().'.id')
                                     ->where(['widget' => $this->filter])
                                     ->limit(12)
                                     ->all();
        }

        if ($products) {
            return $this->render('product', [
                'header'   => $this->header,
                'products' => $products,
                'interval' => $this->interval,
            ]);
        } else {
            return '';
        }
    }
}