<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\widgets\Alert;

?>

<br /><br />

<?= Alert::widget() ?>

<?php

$form = ActiveForm::begin(['id' => 'account_form']);

?>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

        <?= $form->field($model, 'realname')->label('ФИО') ?>

        <?= $form->field($model, 'phone')->label('Ваш телефон') ?>

        <?= $form->field($model, 'email')->label('Ваша электронная почта') ?>

        <?= Html::submitButton('Сохранить изменения') ?>
    </div>
    <div class="col-lg-6 col-md-6 hidden-sm hidden-xs">

    </div>
</div>

<?php

ActiveForm::end();

?>