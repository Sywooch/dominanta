<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\ActiveRecord\ProductCategory;

$model = new ProductCategory;

if ($links) {

?>

<div class="row">
<div class="row">

    <?php foreach ($links AS $category) { $model->id = $category['id']; ?>

    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="product_category_item thumbnail">
            <div class="product_category_item_image" <?= file_exists($model->uploadFolder.DIRECTORY_SEPARATOR.$category['id'].'.jpg') ?
            'style="background: url(\''.str_replace(Yii::getAlias('@webroot'), '', $model->getPreview($model->uploadFolder.DIRECTORY_SEPARATOR.$category['id'].'.jpg', 410, 230)).'\') 50% 50%"'
            : '' ?>>
                <?= Html::a('', $category['link']) ?>
            </div>
            <div class="product_category_item_header">
                <?= $category['items'] ? Html::encode($category['name']) : Html::a(Html::encode($category['name']), $category['link']) ?>
            </div>
            <div class="product_category_item_subcats">
                <?php for ($i = 0; $i < 5 && $i < count($category['items']); $i++) { ?>
                    <?= Html::a(Html::encode($category['items'][$i]['name']), $category['items'][$i]['link']) ?>
                <?php } ?>

                <?php if (count($category['items']) > 5) { ?>
                <div class="product_category_item_subcats_hidden" id="hidden_subcats_<?= $category['id'] ?>">
                    <?php for ($i = 5; $i < count($category['items']); $i++) { ?>
                        <?= Html::a(Html::encode($category['items'][$i]['name']), $category['items'][$i]['link']) ?>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php if (count($category['items']) > 5) { ?>
        <div class="product_category_item_show_all" data-id="<?= $category['id'] ?>">
             Смотреть все подкатегории &nbsp;&nbsp;<i class="fa fa-angle-down"></i>
        <?php } else { ?>
        <div class="product_category_item_bottom_all">
             &nbsp;
        <?php } ?>
        </div>
    </div>

    <?php } ?>

<div class="category_description col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?= $cat_model->category_description ?>
</div>

</div>
</div>

<?php

} else {

?>

<div class="jumbotron">
    <p>Товаров в данной категории не обнаружено</p>
</div>


<?php

}

?>