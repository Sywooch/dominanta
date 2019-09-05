<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use rmrevin\yii\fontawesome\component\Icon;
use app\components\widgets\SummernoteWidget;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add').($model->id ? ' «'.$model->mail_subject.'»' : '');
    $this->params['select_menu'] = Url::to(['/manage/mail/subscribes']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    if ($model->status <= $model::STATUS_ACTIVE) {
        $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    }

    $form = ActiveForm::begin($form_config);

?>

<div class="row">
    <div class="col-md-2 col-xs-hidden"></div>
    <div class="col-md-8 col-xs-12">

        <?php if ($model->status <= $model::STATUS_ACTIVE) { ?>
        <?= $form->field($model, 'status')->dropdownList([
            $model::STATUS_INACTIVE => Yii::t('app', $model->statusTexts()[$model::STATUS_INACTIVE]),
            $model::STATUS_ACTIVE   => Yii::t('app', $model->statusTexts()[$model::STATUS_ACTIVE]),
         ], ['prompt' => '']) ?>
         <?php } else { ?>
         <div class="alert alert-danger" role="alert">
             Рассылка уже отправлена, либо находится в процессе отправки. Редактирование невозможно.
         </div>
         <div class="text-center">
             <?= new Icon($model->statusIcon) ?> <?= $model->statusText ?>
         </div>
         <?php } ?>

        <?= $form->field($model, 'mail_subject') ?>

        <?= Yii::$app->request->isAjax ? $form->field($model, 'mail_text')->textarea() : $form->field($model, 'mail_text')->widget(SummernoteWidget::classname()) ?>

        <?php if (!Yii::$app->request->isAjax && $model->status <= $model::STATUS_ACTIVE) { ?>
        <div class="form-group text-center">
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/mail/subscribes'], ['class' => 'btn btn-round btn-default cancel-button']) ?>
            <?= $this->params['submit_button'] ?>
        </div>
        <?php } ?>

    </div>
    <div class="col-md-2 col-xs-hidden"></div>
</div>

<?php

    ActiveForm::end();

    if (Yii::$app->request->isAjax) {
        Pjax::end();
    }

?>

<?php } ?>
