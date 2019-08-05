<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

foreach ($properties AS $property) {

?>

    <b><?= $property['title'] ?>:</b> <?= $property['property_value'] ?><br />

<?php } ?>