<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use Cocur\Slugify\Slugify;
use rmrevin\yii\fontawesome\component\Icon;
use app\models\ActiveRecord\ProductPhoto;

$id = 'product_widget_carousel_'.(new Slugify())->slugify($header);
$s = 0;
$slides = [
    $s => []
];

foreach ($products AS $product) {
    if (count($slides[$s]) == 4) {
        $s++;
        $slides[$s] = [];
    }

    $slides[$s][] = $product;
}

?>

<div class="product_widget">
    <div class="product_widget_header">
        <?= $header ?>
    </div>

    <div id="<?= $id ?>" class="carousel slide" data-ride="carousel" data-interval="<?= $interval ?>">

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
        <?php foreach ($slides AS $idx => $slide) { ?>
            <div class="item<?= !$idx ? ' active' : '' ?>">
            <?php foreach ($slide AS $product) {
                $img = ProductPhoto::find()->where(['product_id' => $product->id])
                                          ->limit(1)
                                          ->orderBy(['photo_order' => SORT_ASC])
                                          ->one();

                if ($img) {
                    $photo = Html::img(str_replace(Yii::getAlias('@webroot'), '', $product->getPreview($img->photoPath, 142, 142)));
                } else {
                    $photo = Html::img("/images/product_item.png");
                }

            ?>
                <div class="product_item">
                    <div class="product_labels">
                    <?php foreach ($product->getLabels() AS $label) { ?>
                        <?= $label->content ?>
                    <?php } ?>
                    </div>
                    <div class="product_item_image">
                        <?= Html::a($photo, $product->productLink) ?>
                    </div>
                    <div class="product_item_title">
                        <?= (!Yii::$app->user->isGuest && Yii::$app->user->identity->rules['Product']['is_edit']) ? Html::a(new Icon('pencil'), ['/manage/market/products/edit', 'id' => $product->id], ['target' => '_blank']) : '' ?>
                        <?= Html::a(Html::encode($product['product_name']), $product->productLink) ?>
                    </div>
                    <div class="product_item_unit">Цена за шт.</div>
                    <div class="product_item_price">
                        <span class="product_item_discount">
                            <?= $product->discount > 0 ? '-'.intval($product->discount).'%' : '' ?>
                        </span>
                        <span class="product_item_oldprice">
                            <?php if ($product->old_price > 0 || $product->discount > 0) { ?>
                                <?= Yii::$app->formatter->asDecimal($product->discount > 0 ? $product->discount : $product->price, 2) ?>
                                <i class="fa fa-ruble"></i>
                            <?php } ?>
                        </span>
                        <?= Yii::$app->formatter->asDecimal($product->realPrice, 2) ?>
                        <i class="fa fa-ruble"></i>
                    </div>
                    <div class="product_item_button">
                        <button class="add_shopcart" data-id="<?= $product->id ?>" data-cnt="1">В корзину</button>
                    </div>
                </div>

            <?php } ?>
            </div>
        <?php } ?>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#<?= $id ?>" role="button" data-slide="prev">

        </a>
        <a class="right carousel-control" href="#<?= $id ?>" role="button" data-slide="next">

        </a>
    </div>
</div>