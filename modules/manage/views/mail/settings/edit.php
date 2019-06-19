<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use rmrevin\yii\fontawesome\component\Icon;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add').($model->id ? ' «'.$model->service_name.'»' : '');
    $this->params['select_menu'] = Url::to(['/manage/mail/settings']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    $form = ActiveForm::begin($form_config);

?>

<div class="row">
    <div class="col-md-3 col-xs-hidden"></div>
    <div class="col-md-6 col-xs-12">

        <?= $form->field($model, 'service_name') ?>

        <?= $form->field($model, 'smtp_host') ?>

        <?= $form->field($model, 'smtp_port') ?>

        <?= $form->field($model, 'smtp_user') ?>

        <?= $form->field($model, 'smtp_password') ?>

        <?= $form->field($model, "smtp_secure")->dropdownList(['SSL' => 'SSL', 'TLS' => 'TLS'], ['prompt' => '']) ?>

        <?= $form->field($model, 'from_email') ?>

        <?= $form->field($model, 'from_name') ?>

        <?= $form->field($model, 'reply_to') ?>

        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group text-center">
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/mail/settings'], ['class' => 'btn btn-round btn-default cancel-button']) ?>
            <?= $this->params['submit_button'] ?>
        </div>
        <?php } ?>

    </div>
    <div class="col-md-3 col-xs-hidden"></div>
</div>

<?php

    ActiveForm::end();

    if (Yii::$app->request->isAjax) {
        Pjax::end();
    }

?>

<?php } ?>
