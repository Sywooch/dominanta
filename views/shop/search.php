<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\models\ActiveRecord\ProductPhoto;

if (!$products) {

?>

<div class="jumbotron">
    <p>По заданным условиям товаров не обнаружено</p>
</div>

<?php

} else {

    $shopcart = Yii::$app->shopcart->getItems();

?>

<div class="find_products_count">
    Найдено товаров: <?= $product_count ?>
</div>
<div class="find_products">
    <?php foreach ($products AS $product) {
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
                <?= Html::a(Html::encode($product['product_name']), $product->productLink) ?>
            </div>
            <div class="product_item_unit">Цена за шт.</div>
            <div class="product_item_price">
                <?= Yii::$app->formatter->asDecimal($product->realPrice, 2) ?>
                <i class="fa fa-ruble"></i>
            </div>
            <div class="product_item_button">
                <div class="product_item_q<?= isset($shopcart[$product->id]) ? ' hidden' : '' ?> product_item_q<?= $product->id ?>">
                    <div class="product_item_quantity_control" id="product_item_quantity_control_<?= $product->id ?>search">
                        <span class="product_item_quantity_control_minus" data-id="<?= $product->id ?>" data-widget="search">&ndash;</span>
                        <span class="product_item_quantity_control_plus" data-id="<?= $product->id ?>" data-widget="search">+</span>
                        <span class="product_item_quantity_control_count">1</span>
                    </div>
                </div>
                <button class="add_shopcart add_shopcart<?= $product->id ?> <?= isset($shopcart[$product->id]) ? 'added_shopcart' : '' ?> add_shopcart<?= $product->id ?>search" data-id="<?= $product->id ?>" data-cnt="<?= isset($shopcart[$product->id]) ? '0' : '1' ?>">
                    <?= isset($shopcart[$product->id]) ? '<i class="fa fa-check"></i> Добавлено!' : 'В корзину' ?>
                </button>
            </div>
        </div>

    <?php } ?>
</div>
<?php } ?>
