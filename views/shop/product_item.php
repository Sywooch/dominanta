<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use rmrevin\yii\fontawesome\component\Icon;

?>

<div class="product_item">
    <div class="product_labels">
    <?php foreach ($product['labels'] AS $label) { ?>
        <?= $label->content ?>
    <?php } ?>
    </div>
    <div class="product_item_image">
        <?= Html::a($photo, $link) ?>
    </div>
    <div class="product_item_title">
        <?= (!Yii::$app->user->isGuest && Yii::$app->user->identity->rules['Product']['is_edit']) ? Html::a(new Icon('pencil'), ['/manage/market/products/edit', 'id' => $product['prod_id']], ['target' => '_blank']) : '' ?>
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