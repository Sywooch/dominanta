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
    'id' => 'reg-form',
    'options' => [
        'data' => [
            'pjax' => '1',
        ],
    ],
]); ?>

<?= $form->field($model, 'realname')->textInput() ?>

<?= $form->field($model, 'email')->textInput()->label('Ваша электронная почта') ?>

<?= $form->field($model, 'phone', ['enableClientValidation' => false])->textInput()->label('Ваш телефон') ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<?= $form->field($model, 'repassword')->passwordInput()->label('Подтверждение пароля') ?>

<?= $form->field($model, 'reCaptcha', ['template' => '{input}{error}'])->widget(ReCaptcha2::className()) ?>

<?= $form->field($model, 'agree')->checkbox([
    'template' => "{input} {label}<div>{error}</div>",
])->label('Я согласен на <a href="/agreement.pdf" target="_blank">обработку персональных данных</a>') ?>

<div class="form-group">
    <div>
        <?= Html::submitButton('Регистрация', ['name' => 'sended_form', 'value' => 'reg_form']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php if (Yii::$app->request->isAjax) { ?>
<script type="text/javascript">
    $("#user-phone").mask("+7(999) 999-99-99");
</script>
<?php } ?>

<?php

} else {

?>
<div class="alert alert-success" role="alert">
    <i class="fa fa-check"></i> Вы успешно зарегистрировались. На вашу почту отправлена ссылка для активации аккаунта.
</div>
<script>
function reg_success() {
    $('#modal_auth').modal('hide');
}

setTimeout('reg_success()', 3500);
</script>

<?php

}

Pjax::end();

?>