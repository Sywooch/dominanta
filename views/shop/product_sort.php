<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<div class="product_list_action_items hidden-xs">
    <?= Html::a('Названию', $link.($filter ? '?'.$filter.'&sort=name' : '?sort=name'), ['class' => $product_sort == 'name' ? 'product_list_action_active' : '']) ?>

    <?= Html::a('Цене&nbsp;по&nbsp;возрастанию', $link.($filter ? '?'.$filter.'&sort=cheap' : '?sort=cheap'), ['class' => $product_sort == 'cheap' ? 'product_list_action_active' : '']) ?>

    <?= Html::a('Цене&nbsp;по&nbsp;убыванию', $link.($filter ? '?'.$filter.'&sort=expensive' : '?sort=expensive'), ['class' => $product_sort == 'expensive' ? 'product_list_action_active' : '']) ?>
</div>

<div id="product_list_action_items_mobile_sort" class="product_list_action_items product_list_action_items_mobile hidden hidden-lg hidden-md hidden-sm">
    <?= Html::a('Названию', $link.($filter ? '?'.$filter.'&sort=name' : '?sort=name'), ['class' => ($product_sort == 'name' ? 'product_list_action_active' : '')]) ?>
    <?= Html::a('Цене&nbsp;по&nbsp;возрастанию', $link.($filter ? '?'.$filter.'&sort=cheap' : '?sort=cheap'), ['class' => ($product_sort == 'cheap' ? 'product_list_action_active' : '')]) ?>
    <?= Html::a('Цене&nbsp;по&nbsp;убыванию', $link.($filter ? '?'.$filter.'&sort=expensive' : '?sort=expensive'), ['class' => ($product_sort == 'expensive' ? 'product_list_action_active' : '')]) ?>
</div>