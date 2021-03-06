<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<div class="product_list_action_items hidden-xs">
    <?= Html::a('20', $link.($filter ? '?'.$filter.'&show=20' : '?show=20'), ['class' => $show_count == '20' ? 'product_list_action_active' : '']) ?>

    <?= Html::a('40', $link.($filter ? '?'.$filter.'&show=40' : '?show=40'), ['class' => $show_count == '40' ? 'product_list_action_active' : '']) ?>

    <?= Html::a('60', $link.($filter ? '?'.$filter.'&show=60' : '?show=60'), ['class' => $show_count == '60' ? 'product_list_action_active' : '']) ?>
</div>

<div id="product_list_action_items_mobile_show"  class="product_list_action_items product_list_action_items_mobile hidden hidden-lg hidden-md hidden-sm">
    <?= Html::a('20', $link.($filter ? '?'.$filter.'&show=20' : '?show=20'), ['class' => ($show_count == '20' ? 'product_list_action_active' : '')]) ?>
    <?= Html::a('40', $link.($filter ? '?'.$filter.'&show=40' : '?show=40'), ['class' => ($show_count == '40' ? 'product_list_action_active' : '')]) ?>
    <?= Html::a('60', $link.($filter ? '?'.$filter.'&show=60' : '?show=60'), ['class' => ($show_count == '60' ? 'product_list_action_active' : '')]) ?>
</div>