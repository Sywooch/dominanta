<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

foreach ($all_properties AS $slug => $one_filter) {

?>

<div class="product_filter_block row">
    <div class="product_filter_header_text <?= $one_filter['active'] ? 'product_filter_header' : 'product_filter_header_collapsed' ?>" data-filter="<?= $slug ?>">
        <?= $one_filter['title'] ?>
    </div>
    <div class="product_filter_values<?= !$one_filter['active'] ? ' collapse' : '' ?>" id="product_filter_<?= $slug ?>">

        <?php foreach ($one_filter['items'] AS $value_slug => $filter_item) { ?>

            <div class="product_filter_value" data-filter="<?= $slug ?>" data-value="<?= $value_slug ?>">
                <span class="product_filter_checkbox<?= $filter_item['active'] ? '_active' : '' ?>"></span>
                <?= $filter_item['name'] ?>

                <?= $filter_item['active'] ? '<input type="hidden" name="filter['.$slug.'][]" value="'.$value_slug.'" data-filter="'.$slug.'" />' : '' ?>
            </div>

        <?php } ?>

    </div>
</div>

<?php } ?>
<?php

if ($vendors) {

?>

<div class="product_filter_block row">
    <div class="product_filter_header_text <?= $vendor_filter ? 'product_filter_header' : 'product_filter_header_collapsed' ?>" data-filter="vendor">
        Производители
    </div>
    <div class="product_filter_values<?= !$vendor_filter ? ' collapse' : '' ?>" id="product_filter_vendor">

        <?php foreach ($vendors AS $vendor) { ?>

            <div class="product_filter_value" data-filter="vendor" data-value="<?= $vendor['id'] ?>">
                <span class="product_filter_checkbox<?= $vendor['active'] ? '_active' : '' ?>"></span>
                <?= Html::encode($vendor['title']) ?>

                <?= $vendor['active'] ? '<input type="hidden" name="vendor[]" value="'.$vendor['id'].'" data-filter="vendor" />' : '' ?>
            </div>

        <?php } ?>

    </div>
</div>


<?php

}

?>