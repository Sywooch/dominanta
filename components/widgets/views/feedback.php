<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use himiklab\yii2\recaptcha\ReCaptcha3;

Pjax::begin();



?>

<?php $form = ActiveForm::begin([
    'id' => 'feedback-form',
    'options' => [
        'data' => [
            'pjax' => '1',
        ],
    ],
]); ?>

<div class="feedback_header">
    Обратная связь
</div>

<?php if ($model) { ?>

<?= $form->field($model, 'f_name') ?>

<?= $form->field($model, 'phone') ?>

<?= $form->field($model, 'email') ?>

<?= $form->field($model, 'message')->textarea(['rows' => 3]) ?>

<?= $form->field($model, 'reCaptcha', ['template' => '{input}{error}'])->widget(ReCaptcha3::className(), ['action' => 'homepage']) ?>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <div>
                <?= Html::submitButton('Отправить', ['name' => 'sended_form', 'value' => 'feedback_form']) ?>
            </div>
        </div>
    </div>
</div>

<?php

} else {

?>
<div class="alert alert-success" role="alert" style="margin: 40px;">
    <i class="fa fa-check"></i> Ваше сообщение отправлено!
</div>

<?php

}

ActiveForm::end();
Pjax::end();

?>