<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;
use rmrevin\yii\fontawesome\component\Icon;
use dosamigos\fileupload\FileUpload;
use dosamigos\gallery\Gallery;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add').($model->id ? ' «'.$model->title.'»' : '');
    $this->params['select_menu'] = Url::to(['/manage/market/vendors']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    $form = ActiveForm::begin($form_config);

?>

<div class="row">
    <div class="col-md-2 hidden-xs"></div>
    <div class="col-md-8 col-xs-12">

        <?= $form->field($model, 'title') ?>

        <div id="vendor_photo">
            <?= $model->photo ? Html::img($model->photo) : '' ?>
        </div>

        <?= FileUpload::widget([
            'model' => $model,
            'attribute' => 'photo',
            'url' => ['/manage/market/vendors/upload'],
            'clientOptions' => [
                'accept' => 'image/jpeg'
            ],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                        $("#vendor_photo").html(\'<img src="\' + data.result.message + \'" alt="" />\');
                                        $(".fileinput-button input[type=hidden]").val(data.result.fname);
                                        return false;
                                    }',
                'fileuploadfail' => 'function(e, data) {
                                        console.log(e);
                                        console.log(data);
                                    }',
            ],
        ]); ?>


        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group text-center">
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/market/vendors'], ['class' => 'btn btn-round btn-default cancel-button']) ?>
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
