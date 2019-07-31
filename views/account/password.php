<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\widgets\Alert;

?>

<br /><br />

<?= Alert::widget() ?>

<?php

$form = ActiveForm::begin(['id' => 'password_form']);

?>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

        <?= $form->field($model, 'old_password')->passwordInput()->label('Старый пароль') ?>

        <?= $form->field($model, 'password')->passwordInput()->label('Новый пароль') ?>

        <?= Html::submitButton('Сохранить изменения') ?>
    </div>
    <div class="col-lg-6 col-md-6 hidden-sm hidden-xs">

    </div>
</div>

<?php

ActiveForm::end();

?>