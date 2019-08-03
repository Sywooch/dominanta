<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\ActiveRecord\ProductPhoto;

$img = ProductPhoto::find()->where(['product_id' => $item->product_id])
                           ->limit(1)
                           ->orderBy(['photo_order' => SORT_ASC])
                           ->one();

if ($img) {
    $photo = Html::img(str_replace(Yii::getAlias('@webroot'), '', $item->product->getPreview($img->photoPath, 142, 142)));
} else {
    $photo = Html::img("/images/product_item.png");
}

$link = $item->product->productLink;

?>

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
