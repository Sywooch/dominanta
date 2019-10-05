<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use himiklab\yii2\recaptcha\ReCaptcha2;

Pjax::begin();

if ($error) {

?>

<div class="alert alert-danger" role="alert"><i class="fa fa-remove"></i> Пользователь не найден.</div>

<?php

}

if ($model) {

?>

<?php $form = ActiveForm::begin([
    'id' => 'restore-form',
    'options' => [
        'data' => [
            'pjax' => '1',
        ],
    ],
]); ?>

<?= $form->field($model['restore'], '[restore]email_or_phone')->textInput()->label('Электронная почта или телефон') ?>

<?= $form->field($model['restore'], '[restore]reCaptcha', ['template' => '{input}{error}'])->widget(ReCaptcha2::className()) ?>


<div class="form-group">
    <div>
        <?= Html::submitButton(Yii::t('app', 'Send'), ['name' => 'sended_form', 'value' => 'restore_form']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php

} else {

?>
<div class="alert alert-success" role="alert">
    <i class="fa fa-check"></i> На вашу почту отправлена ссылка для восстановления пароля.
</div>
<script>
function restore_success() {
    $('#modal_auth').modal('hide');
}

setTimeout('restore_success()', 3500);
</script>

<?php

}

Pjax::end();

?>