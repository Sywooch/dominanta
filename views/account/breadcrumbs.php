<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<a href="/">Главная</a> <i class="fa fa-angle-right"></i>

<?php for ($l = count($links) - 1; $l >= 0; $l--) { ?>

<?= !$l ? '<span>'.Html::encode($links[$l]['name']).'</span>' : Html::a(Html::encode($links[$l]['name']), $links[$l]['link']).'  <i class="fa fa-angle-right"></i>' ?>

<?php } ?>