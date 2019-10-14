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
        <span class="product_item_discount">
            <?= $product['discount'] > 0 ? '-'.intval($product['discount']).'%' : '' ?>
        </span>
        <span class="product_item_oldprice">
            <?php if ($product['old_price'] > 0 || $product['discount'] > 0) { ?>
                <?= Yii::$app->formatter->asDecimal($product['discount'] > 0 ? $product['discount'] : $product['price'], 2) ?>
                <i class="fa fa-ruble"></i>
            <?php } ?>
        </span>
        <?= Yii::$app->formatter->asDecimal($product['real_price'], 2) ?>
        <i class="fa fa-ruble"></i>
    </div>
    <div class="product_item_button">
        <div class="product_item_q">
            <div class="product_item_quantity_control" id="product_item_quantity_control_<?= $product['prod_id'] ?>">
                <span class="product_item_quantity_control_minus" data-id="<?= $product['prod_id'] ?>" data-widget="">&ndash;</span>
                <span class="product_item_quantity_control_plus" data-id="<?= $product['prod_id'] ?>" data-widget="">+</span>
                <span class="product_item_quantity_control_count">1</span>
            </div>
        </div>

        <button class="add_shopcart add_shopcart<?= $product['prod_id'] ?>" data-id="<?= $product['prod_id'] ?>" data-cnt="1">В корзину</button>
    </div>
</div>