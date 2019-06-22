<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin();

if ($model) {

?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => [
        'data' => [
            'pjax' => '1',
        ],
    ],
]); ?>

<?= $form->field($model['login'], '[login]email')->textInput()->label('Ваша электронная почта') ?>

<?= $form->field($model['login'], '[login]password')->passwordInput() ?>

<?= $form->field($model['login'], '[login]remember_me')->checkbox([
    'template' => "{input} {label}<div>{error}</div>",
]) ?>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <div>
                <?= Html::submitButton(Yii::t('app', 'Sign in'), ['name' => 'sended_form', 'value' => 'login_form']) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 restore_link">
        <a href="#tab_panel_restore" class="modal_tab">Забыл пароль</a>
    </div>
</div>



<?php ActiveForm::end(); ?>

<?php

} else {

?>
<div class="alert alert-success" role="alert">
    <i class="fa fa-check"></i> Вход успешно выполнен!
</div>
<div id="account_menu_tmp" class="hidden">
    <?= $account_menu ?>
</div>
<script>
function login_success() {
    $('#top_personal_link').data('toggle', '');
    mainPage.showAccountMenu();
    $('#account_menu').html($('#account_menu_tmp').html());
    $('#modal_auth').modal('hide');
    setTimeout("$('#modal_auth').remove()", 700);
}

setTimeout('login_success()', 1500);
</script>

<?php

}

Pjax::end();

?>