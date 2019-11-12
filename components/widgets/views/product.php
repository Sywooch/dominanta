<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use Cocur\Slugify\Slugify;
use rmrevin\yii\fontawesome\component\Icon;
use app\models\ActiveRecord\ProductPhoto;

$shopcart = Yii::$app->shopcart->getItems();

$header_en = (new Slugify())->slugify($header);

$id = 'product_widget_carousel_'.$header_en;

$sizes = [
    'lg' => 4,
    'md' => 3,
    'sm' => 2,
    'xs' => 1,
];

?>

<div class="row">
    <div class="product_widget">
        <div class="product_widget_header">
            <?= $header ?>
        </div>

        <div class="carousel carousel-showmanymoveone slide" id="<?= $id ?>" data-interval="<?= $interval ?>">
            <div class="carousel-inner">

            <?php foreach ($products AS $idx => $product) {
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

                <div class="item<?= !$idx ? ' active' : '' ?>">

                    <div class="product_widget_item col-xs-12 col-sm-6 col-md-3 col-lg-3">
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
                            <div class="product_item_q<?= isset($shopcart[$product->id]) ? ' hidden' : '' ?> product_item_q<?= $product->id ?>">
                                <div class="product_item_quantity_control" id="product_item_quantity_control_<?= $product->id ?><?= $header_en ?>">
                                    <span class="product_item_quantity_control_minus" data-id="<?= $product->id ?>" data-widget="<?= $header_en ?>">&ndash;</span>
                                    <span class="product_item_quantity_control_plus" data-id="<?= $product->id ?>" data-widget="<?= $header_en ?>">+</span>
                                    <span class="product_item_quantity_control_count">1</span>
                                </div>
                            </div>
                            <button class="add_shopcart add_shopcart<?= $product->id ?> <?= isset($shopcart[$product->id]) ? 'added_shopcart' : '' ?> add_shopcart<?= $product->id ?><?= $header_en ?>" data-id="<?= $product->id ?>" data-cnt="<?= isset($shopcart[$product->id]) ? '0' : '1' ?>">
                                <?= isset($shopcart[$product->id]) ? '<i class="fa fa-check"></i> Добавлено!' : 'В корзину' ?>
                            </button>
                        </div>

                    </div>
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
</div>


<?php

/*


foreach ($sizes AS $sz => $one_size) {

    $id = 'product_widget_carousel_'.$header_en.'_'.$sz;
    $s = 0;

    $slides = [
        $s => []
    ];

    foreach ($products AS $product) {
        if (count($slides[$s]) == $one_size) {
            $s++;
            $slides[$s] = [];
        }

        $slides[$s][] = $product;
    }

    $sz_cp = $sizes;
    unset($sz_cp[$sz]);
    $sz_class = implode(' hidden-', array_keys($sz_cp));

    ?>



    <div class="product_widget hidden-<?= $sz_class ?>">
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
                            <div class="product_item_q<?= isset($shopcart[$product->id]) ? ' hidden' : '' ?> product_item_q<?= $product->id ?>">
                                <div class="product_item_quantity_control" id="product_item_quantity_control_<?= $product->id ?><?= $header_en ?>_<?= $sz ?>">
                                    <span class="product_item_quantity_control_minus" data-id="<?= $product->id ?>" data-widget="<?= $header_en ?>_<?= $sz ?>">&ndash;</span>
                                    <span class="product_item_quantity_control_plus" data-id="<?= $product->id ?>" data-widget="<?= $header_en ?>_<?= $sz ?>">+</span>
                                    <span class="product_item_quantity_control_count">1</span>
                                </div>
                            </div>
                            <button class="add_shopcart add_shopcart<?= $product->id ?> <?= isset($shopcart[$product->id]) ? 'added_shopcart' : '' ?> add_shopcart<?= $product->id ?><?= $header_en ?>_<?= $sz ?>" data-id="<?= $product->id ?>" data-cnt="<?= isset($shopcart[$product->id]) ? '0' : '1' ?>">
                                <?= isset($shopcart[$product->id]) ? '<i class="fa fa-check"></i> Добавлено!' : 'В корзину' ?>
                            </button>
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
<?php } ?>

*/ ?>