<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin();

?>

<?php $form = ActiveForm::begin([
    'id' => 'subscribe-form',
    'options' => [
        'class' => 'footer_subsribe_form',
        'data' => [
            'pjax' => '1',
        ],
    ],
]); ?>

<?= Html::submitButton('Подписаться на рассылку', ['name' => 'sended_form', 'value' => 'subscribe_form', 'class' => 'footer_subsribe_button pull-right hidden-xs']) ?>

<?= $form->field($model, 'email', ['template' => '{input}{error}'])->textInput(['class' => 'form-control footer_subsribe_field', 'placeholder' => 'Ваша электронная почта']) ?>

<?= Html::submitButton('Подписаться на рассылку', ['name' => 'sended_form', 'value' => 'subscribe_form', 'class' => 'footer_subsribe_button hidden-lg hidden-md hidden-sm']) ?>

<?php ActiveForm::end(); ?>

<?php

if ($success) {

?>
<script>
    $('#modal_subscribe').modal('show');
</script>

<?php

}

Pjax::end();

?>