<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<div class="product_item">
    <div class="product_item_image">
        <?= Html::a($photo, $link) ?>
    </div>
    <div class="product_item_title">
        <?= Html::a(Html::encode($product['product_name']), $link) ?>
    </div>
        <div class="product_item_unit">Цена за шт.</div>
        <div class="product_item_price">
            <?= Yii::$app->formatter->asDecimal($product['real_price'], 2) ?>
            <i class="fa fa-ruble"></i>
        </div>
    <div class="product_item_button">
        <button class="add_shopcart" data-id="<?= $product['prod_id'] ?>" data-cnt="1">В корзину</button>
    </div>
</div>