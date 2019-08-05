<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\widgets\Alert;

?>

<?php

$form = ActiveForm::begin(['id' => 'shopcart_form']);

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shopcart_form_header">
        Информация о покупателе
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $form->field($model, 'fio') ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?= $form->field($model, 'phone') ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?= $form->field($model, 'email') ?>
    </div>
</div>

<div class="row">
    <?php if ($addresses) { ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label class="control-label"><?= $model->getAttributeLabel('address') ?></label>
        </div>

        <?php foreach ($addresses AS $address) { ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 address_row">
            <span class="order_address <?= $model->address == '' && $address['selected'] ? 'order_address_selected' : '' ?>" data-id="<?= $address['id'] ?>">&nbsp;<br />&nbsp;</span>
            <b><?= Html::encode($address['name']) ?>, </b>
            <span><?= Html::encode($address['address']) ?></span>
        </div>
        <?php } ?>
        <?= Html::hiddenInput('sel_address', $sel_address, ['id' => 'selected_address']) ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <a href="#" class="new_address_link">Новый адрес доставки</a>
            <div class="new_address_input<?= $sel_address ? ' hidden' : '' ?>">
                <?php $model->address = $model->address == '' ? '1' : $model->address ?>
                <?= $form->field($model, 'address')->label('') ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $form->field($model, 'address') ?>
        </div>
    <?php } ?>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $form->field($model, 'order_comment')->textarea(['rows' => 3]) ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shopcart_form_header">
        Способ доставки
    </div>
</div>
<div class="row custom_sel_row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="custom_form_selector<?= $model->delivery_type == 0 ? ' custom_form_selector_active' : '' ?>" data-id="0">Самовывоз</div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="custom_form_selector<?= $model->delivery_type == 1 ? ' custom_form_selector_active' : '' ?>" data-id="1">Доставка курьером</div>
    </div>
    <div class="hidden">
        <?= $form->field($model, 'delivery_type')->hiddenInput() ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 shopcart_form_header">
        Способ оплаты
    </div>
</div>
<div class="row custom_sel_row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="custom_form_selector<?= $model->payment_type == 0 ? ' custom_form_selector_active' : '' ?>" data-id="0">Безналичный расчёт</div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="custom_form_selector<?= $model->payment_type == 1 ? ' custom_form_selector_active' : '' ?>" data-id="1">Наличными</div>
    </div>
    <div class="hidden">
        <?= $form->field($model, 'payment_type')->hiddenInput() ?>
    </div>
</div>

<div class="row agreement_row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <span class="custom_checkbox checkbox_<?= $model->agreement ? 'active' : 'inactive' ?>"></span>
        Я согласен на <a href="/agreement.pdf" target="_blank">обработку персональных данных.</a>
        <?= $form->field($model, 'agreement', ['template' => '{input}{error}'])->hiddenInput() ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <span class="order_total_label">Итого с учетом доставки</span>
        <div class="order_total_amount">
            <?= Yii::$app->formatter->asDecimal($total, 2) ?> <i class="fa fa-ruble"></i>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= Html::submitButton('Оформить заказ') ?>
    </div>
</div>

<?php

ActiveForm::end();

?>