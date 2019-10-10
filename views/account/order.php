<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$positions = $order->shopOrderPosition;
$order_amount = 0;

foreach ($positions AS $position) {
    $order_amount += $position->price;
}


?>

<div class="row order_item_row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 order_item_layout">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 order_item">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <div class="order_item_label">Номер заказа:</div>
                    <div class="order_item_data"><b><?= $order->id ?></b></div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <div class="order_item_label">Дата заказа:</div>
                    <div class="order_item_data"><b><?= $order->getPageDate($order->add_time) ?></b></div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <div class="order_item_label">Сумма заказа:</div>
                    <div class="order_item_data"><b><?= Yii::$app->formatter->asDecimal($order_amount, 2) ?> <i class="fa fa-ruble"></i></b></div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <span class="order_item_collapse_button" data-id="<?= $order->id ?>"><i class="fa fa-angle-down"></i></span>
                    <div class="order_item_label">Статус заказа:</div>
                    <div class="order_item_data"><b><?= $order->statuses[$order->status] ?></b></div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 order_positions collapse" id="order_positions_<?= $order->id ?>">
            <?php foreach ($positions AS $position) { ?>
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <div class="order_item_label">Товар:</div>
                        <div class="order_item_data"><b><?= $position->product->status == $position->product::STATUS_ACTIVE ? Html::a(Html::encode($position->product->product_name), $position->product->productLink, ['target' => '_blank']) : Html::encode($position->product->product_name) ?></b></div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                        <div class="order_item_label">Количество:</div>
                        <div class="order_item_data"><?= $position->quantity ?> <?= $position->product->unit ? $position->product->unit : 'шт.' ?></div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                        <div class="order_item_label">Цена:</div>
                        <div class="order_item_data"><?= Yii::$app->formatter->asDecimal($position->price, 2) ?> <i class="fa fa-ruble"></i></div>
                    </div>

                </div>
                <div class="position_end"></div>
            <?php } ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 order_item_address">
                    <?php if ($order->delivery_type) { ?>
                    <span class="order_item_address_label">Адрес доставки:</span>
                    <?= Html::encode($order->address) ?>
                    <?php } else { ?>
                    <span class="order_item_address_label">Самовывоз</span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>



</div>



<?php /*
<div class="row shopcart_item" id="shopcart_item_<?= $item->id ?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= Html::a($photo, $link, ['target' => '_blank']) ?>
        <button type="button" class="close" data-id="<?= $item->id ?>" aria-label="Удалить" data-id=""><span aria-hidden="true">&times;</span></button>
        <div class="shopcart_item_link">
            <?= Html::a($item->product->product_name, $link, ['target' => '_blank']) ?>
        </div>
        <div class="shopcart_item_qprice">
            <div class="shopcart_item_q">
                <div class="shopcart_info_label">Количество</div>
                <div class="quantity_control" id="quantity_control_<?= $item->id ?>">
                    <span class="quantity_control_minus" data-id="<?= $item->id ?>">&ndash;</span>
                    <span class="quantity_control_plus" data-id="<?= $item->id ?>">+</span>
                    <span class="quantity_control_count"><?= round($item->quantity) ?></span>
                </div>
            </div>
            <div class="shopcart_item_price">
                <div class="shopcart_info_label">Цена</div>
                <?= Yii::$app->formatter->asDecimal($item->product->price - ($item->product->price * ($item->product->discount / 100)), 2) ?> <i class="fa fa-ruble"></i>
            </div>
        </div>
    </div>
</div>

*/ ?>