<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

foreach ($properties AS $property) {

?>
    <div class="product_property_row">
        <span class="product_property_value">
            <?= $property['property_value'] ?>
        </span>
        <span class="product_property_name">
            <?= $property['title'] ?>
        </span>
    </div>
    <div style="clear: both"></div>

<?php } ?>