<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use rmrevin\yii\fontawesome\component\Icon;
use app\models\ActiveRecord\ProductCategory;

$model = new ProductCategory;

if ($links) {

$cnt = 0;

?>

<div class="row">

    <?php foreach ($links AS $idx => $category) { $model->id = $category['id']; ?>

    <?= !$cnt ? '<div class="row hidden-sm hidden-xs">' : '' ?>

    <div class="col-lg-4 col-md-4">
        <div class="product_category_item thumbnail">
            <div class="product_category_item_image" <?= file_exists($model->uploadFolder.DIRECTORY_SEPARATOR.$category['id'].'.jpg') ?
            'style="background: url(\''.str_replace(Yii::getAlias('@webroot'), '', $model->getPreview($model->uploadFolder.DIRECTORY_SEPARATOR.$category['id'].'.jpg', 410, 230)).'\') 50% 50% no-repeat"'
            : '' ?>>
                <?= Html::a('', $category['link']) ?>
            </div>
            <div class="product_category_item_header">
                <?= (!Yii::$app->user->isGuest && Yii::$app->user->identity->rules['ProductCategory']['is_edit']) ? Html::a(new Icon('pencil'), ['/manage/market/categories/edit', 'id' => $category['id']], ['target' => '_blank']) : '' ?>
                <?= Html::a(Html::encode($category['name']), $category['link']) ?>
            </div>
        </div>
        <?php if ($category['items']) { ?>
            <div class="product_category_item_show_all product_category_item_show_all<?= $category['id'] ?>" data-id="<?= $category['id'] ?>">
                Смотреть все подкатегории &nbsp;&nbsp;<i class="fa fa-angle-down"></i>

                <div class="product_category_item_subcats product_category_item_subcats<?= $category['id'] ?>">
                    <div>
                    <?php for ($i = 0; $i < count($category['items']) && $i < count($category['items']); $i++) { ?>
                        <?= Html::a(Html::encode($category['items'][$i]['name']), $category['items'][$i]['link']) ?>
                    <?php } ?>
                    </div>
                </div>
            <?php } else { ?>
            <div class="product_category_item_bottom_all">
                &nbsp;
            <?php } ?>
        </div>
    </div>

    <?php $cnt++; if ($cnt == 3 || $idx == count($links) - 1) { $cnt = 0; echo '</div>'; } ?>

<?php

    }

    foreach ($links AS $idx => $category) { $model->id = $category['id'];

?>

    <?= !$cnt ? '<div class="row hidden-lg hidden-md hidden-xs">' : '' ?>

    <div class="col-sm-6">
        <div class="product_category_item thumbnail">
            <div class="product_category_item_image" <?= file_exists($model->uploadFolder.DIRECTORY_SEPARATOR.$category['id'].'.jpg') ?
            'style="background: url(\''.str_replace(Yii::getAlias('@webroot'), '', $model->getPreview($model->uploadFolder.DIRECTORY_SEPARATOR.$category['id'].'.jpg', 410, 230)).'\') 50% 50%"'
            : '' ?>>
                <?= Html::a('', $category['link']) ?>
            </div>
            <div class="product_category_item_header">
                <?= (!Yii::$app->user->isGuest && Yii::$app->user->identity->rules['ProductCategory']['is_edit']) ? Html::a(new Icon('pencil'), ['/manage/market/categories/edit', 'id' => $category['id']], ['target' => '_blank']) : '' ?>
                <?= Html::a(Html::encode($category['name']), $category['link']) ?>
            </div>
        </div>
        <?php if ($category['items']) { ?>
        <div class="product_category_item_show_all product_category_item_show_all<?= $category['id'] ?>" data-id="<?= $category['id'] ?>">
             Смотреть все подкатегории &nbsp;&nbsp;<i class="fa fa-angle-down"></i>

            <div class="product_category_item_subcats product_category_item_subcats<?= $category['id'] ?>">
                <div>
                <?php for ($i = 0; $i < count($category['items']) && $i < count($category['items']); $i++) { ?>
                    <?= Html::a(Html::encode($category['items'][$i]['name']), $category['items'][$i]['link']) ?>
                <?php } ?>
                </div>
            </div>
        <?php } else { ?>
        <div class="product_category_item_bottom_all">
             &nbsp;
        <?php } ?>
        </div>
    </div>

    <?php $cnt++; if ($cnt == 2 || $idx == count($links) - 1) { $cnt = 0; echo '</div>'; } ?>

<?php

    }

    foreach ($links AS $idx => $category) { $model->id = $category['id'];

?>

    <div class="row hidden-lg hidden-md hidden-sm">

    <div class="col-xs-12">
        <div class="product_category_item thumbnail">
            <div class="product_category_item_image" <?= file_exists($model->uploadFolder.DIRECTORY_SEPARATOR.$category['id'].'.jpg') ?
            'style="background: url(\''.str_replace(Yii::getAlias('@webroot'), '', $model->getPreview($model->uploadFolder.DIRECTORY_SEPARATOR.$category['id'].'.jpg', 410, 230)).'\') 50% 50%"'
            : '' ?>>
                <?= Html::a('', $category['link']) ?>
            </div>
            <div class="product_category_item_header">
                <?= (!Yii::$app->user->isGuest && Yii::$app->user->identity->rules['ProductCategory']['is_edit']) ? Html::a(new Icon('pencil'), ['/manage/market/categories/edit', 'id' => $category['id']], ['target' => '_blank']) : '' ?>
                <?= Html::a(Html::encode($category['name']), $category['link']) ?>
            </div>
        </div>
        <?php if ($category['items']) { ?>
        <div class="product_category_item_show_all product_category_item_show_all<?= $category['id'] ?>" data-id="<?= $category['id'] ?>">
             Смотреть все подкатегории &nbsp;&nbsp;<i class="fa fa-angle-down"></i>

            <div class="product_category_item_subcats product_category_item_subcats<?= $category['id'] ?>">
                <div>
                <?php for ($i = 0; $i < count($category['items']) && $i < count($category['items']); $i++) { ?>
                    <?= Html::a(Html::encode($category['items'][$i]['name']), $category['items'][$i]['link']) ?>
                <?php } ?>
                </div>
            </div>
        <?php } else { ?>
        <div class="product_category_item_bottom_all">
             &nbsp;
        <?php } ?>
        </div>
    </div>

    </div>

  <?php } ?>

  <div class="row category_description_row">
      <div class="category_description col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <?= $cat_model ? $cat_model->category_description : '' ?>
      </div>
      <div class="category_description_shadow col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
      <div class="category_description_link col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
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