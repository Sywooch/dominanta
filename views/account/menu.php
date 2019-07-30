<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<div class="row user_account_menu">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <?= $active_link == 'index' ? '<span class="user_acc_index">Личные данные</span>' : Html::a('Личные данные', ['/account'], ['class' => 'user_acc_index']) ?>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <?= $active_link == 'orders' ? '<span class="user_acc_orders">История заказов</span>' : Html::a('История заказов', ['/account/orders'], ['class' => 'user_acc_orders']) ?>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <?= $active_link == 'delivery' ? '<span class="user_acc_delivery">Адреса доставок</span>' : Html::a('Адреса доставок', ['/account/delivery'], ['class' => 'user_acc_delivery']) ?>
    </div>
</div>