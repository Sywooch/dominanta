<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use himiklab\yii2\recaptcha\ReCaptcha3;

Pjax::begin(['enablePushState' => false]);

if ($model) {

?>

<?php $form = ActiveForm::begin([
    'action' => '/shop/add_review',
    'id' => 'review_form',
    'options' => [
        'data' => [
            'pjax' => '1',
        ],
    ],
]); ?>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $form->field($model, 'reviewer')->textInput(['readonly' => !Yii::$app->user->isGuest]) ?>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?= $form->field($model, 'rate', ['template' => '{label}<div class="review_form_stars">'.str_repeat('<span class="review_form_star_inactive"></span>', 5).'</div>{input}{error}'])->hiddenInput() ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?= $form->field($model, 'review_text')->textarea(['rows' => 3]) ?>
    </div>

    <div class="hidden">
        <?= $form->field($model, 'product_id')->hiddenInput() ?>
    </div>
</div>

<?= $form->field($model, 'reCaptcha', ['template' => '{input}{error}'])->widget(ReCaptcha3::className(), ['action' => 'homepage']) ?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <div>
                <?= Html::submitButton('Добавить отзыв', ['name' => 'sended_form', 'value' => 'review_form']) ?>
            </div>
        </div>
    </div>
</div>



<?php ActiveForm::end(); ?>

<?php

} else {

?>
<div style="margin: 40px" class="alert alert-success" role="alert">
    <i class="fa fa-check"></i> Отзыв добавлен!
</div>
<script>
function review_success() {
    $('#modal_review').modal('hide');
    //setTimeout("$('#modal_review').remove()", 700);
}

setTimeout('review_success()', 1500);
</script>

<?php

}

Pjax::end();

?>