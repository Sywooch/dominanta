<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\widgets\Alert;

?>

<?php

$form = ActiveForm::begin(['options' => ['class' => 'delivery_form']]);

?>

<?= isset($model->id) && Yii::$app->session->hasFlash('delivery_'.$model->id) ? '<div class="alert alert-success" role="alert">'.Yii::$app->session->getFlash('delivery_'.$model->id).'</div>' : '' ?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 address_name">
        <?= $model->id ? Html::encode($model->address_name) : 'Новый адрес' ?>
        <?= $model->id ? Html::a('Удалить адрес', ['/account/delivery', 'delete' => $model->id], ['data' => ['confirm' => 'Вы уверены, что хотите удалить этот адрес?', 'method' => 'post']]) : '' ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <?= $form->field($model, 'address_name') ?>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $form->field($model, 'address') ?>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-right">
        <?= Html::submitButton('Сохранить') ?>
    </div>

    <?= isset($model->id) ? Html::hiddenInput('id', $model->id) : '' ?>
</div>

<?php

ActiveForm::end();

?>