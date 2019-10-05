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

if ($model) {

?>

<?php $form = ActiveForm::begin([
    'id' => 'callback-form',
    'options' => [
        'data' => [
            'pjax' => '1',
        ],
    ],
]); ?>

<?= $form->field($model, 'fio') ?>

<?= $form->field($model, 'phone') ?>

<?= $form->field($model, 'reCaptcha', ['template' => '{input}{error}'])->widget(ReCaptcha2::className()) ?>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <div>
                <?= Html::submitButton('Отправить', ['name' => 'sended_form', 'value' => 'callback_form']) ?>
            </div>
        </div>
    </div>
</div>



<?php ActiveForm::end(); ?>

<?php

} else {

?>
<div class="alert alert-success" role="alert" style="margin: 40px;">
    <i class="fa fa-check"></i> Заявка на звонок отправлена!
</div>
<script>
function callback_success() {
    $('#modal_callback').modal('hide');
}

setTimeout('callback_success()', 1500);
</script>

<?php

}

Pjax::end();

?>