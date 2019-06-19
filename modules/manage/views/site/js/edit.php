<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use rmrevin\yii\fontawesome\component\Icon;
use dosamigos\fileupload\FileUpload;
use app\components\widgets\EditareaWidget;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add').($model->id ? ' «'.$model->js_name.'»' : '');
    $this->params['select_menu'] = Url::to(['/manage/site/js']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    $form = ActiveForm::begin($form_config);

?>

<div class="row">
    <div class="col-md-2 col-xs-hidden"></div>
    <div class="col-md-8 col-xs-12">

        <?= $form->field($model, 'js_name') ?>

        <div class="" role="tabpanel">
            <ul id="js_form_tabs" class="nav nav-tabs bar_tabs" role="tablist">
                <li role="presentation"<?= $model->path ? '' : ' class="active"'?>>
                    <a href="#tab_content_embedded" id="form-tab-embedded" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('file-text-o') ?> <span class="hidden-xs"><?= Yii::t('app', 'Embedded script') ?></span>
                    </a>
                </li>
                <li role="presentation"<?= $model->path ? ' class="active"' : ''?>>
                    <a href="#tab_content_external" id="form-tab-external" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('files-o') ?> <span class="hidden-xs"><?= Yii::t('app', 'External script') ?></span>
                    </a>
                </li>
            </ul>

            <div id="js_form_content" class="tab-content">
                <div role="tabpanel" class="tab-pane fade<?= $model->path ? '' : ' active in'?>" id="tab_content_embedded" aria-labelledby="form-tab-embedded">

                    <?= Yii::$app->request->isAjax ? $form->field($model, 'content')->textarea() : $form->field($model, 'content')->widget(EditareaWidget::classname(), ['syntax' => 'js']) ?>

                </div>
                <div role="tabpanel" class="tab-pane fade<?= $model->path ? ' active in' : ''?>" id="tab_content_external" aria-labelledby="form-tab-external">

                    <?= $form->field($model, 'path') ?>

                    <?= FileUpload::widget([
                        'model' => $model,
                        'attribute' => 'upload',
                        'url' => ['/manage/site/js/upload'],
                        'options' => ['accept' => 'text/javascript'],
                        'clientEvents' => [
                            'fileuploaddone' => 'function(e, data) {
                                if (data.result.status == "ok") {
                                    $("#js-path").val(data.result.message);
                                    $(".field-js-path .help-block").html("");
                                    $(".field-js-path").addClass("has-success");
                                    $(".field-js-path").removeClass("has-error");
                                } else {
                                    $(".field-js-path").removeClass("has-success");
                                    $(".field-js-path").addClass("has-error");
                                    $(".field-js-path .help-block").html(data.result.message);
                                }
                            }',
                            'fileuploadfail' => 'function(e, data) {
                                                    console.log(e);
                                                    console.log(data);
                                                }',
                        ],
                    ]); ?>


                </div>
            </div>
        </div>

        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group text-center">
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/site/js'], ['class' => 'btn btn-round btn-default cancel-button']) ?>
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
