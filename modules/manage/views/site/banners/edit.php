<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;
use rmrevin\yii\fontawesome\component\Icon;
use dosamigos\fileupload\FileUpload;
use dosamigos\gallery\Gallery;
use app\components\widgets\SummernoteWidget;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add');
    $this->params['select_menu'] = Url::to(['/manage/site/banners']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $form_config['options']['onsubmit'] = '$("#banner-photo").remove(); return true;';

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    $form = ActiveForm::begin($form_config);

?>

<div class="row">
    <div class="col-md-2 hidden-xs"></div>
    <div class="col-md-8 col-xs-12">

        <?= $form->field($model, 'status')->dropdownList([
            $model::STATUS_ACTIVE => Yii::t('app', 'Published'),
            $model::STATUS_INACTIVE => Yii::t('app', 'Hidden')
         ], ['prompt' => '']) ?>

        <div id="banner_photo">
            <?= file_exists($model->uploadFolder.'/'.$model->id.'.jpg') ? Html::img($model->getPreview($model->uploadFolder.'/'.$model->id.'.jpg', 720, 250)) : '' ?>
        </div>

        <?= FileUpload::widget([
            'model' => $model,
            'attribute' => 'photo',
            'url' => ['/manage/site/banners/upload'],
            'clientOptions' => [
                'accept' => 'image/jpeg'
            ],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                        $("#banner_photo").html(\'<img src="\' + data.result.message + \'" alt="" />\');
                                        $(".fileinput-button input[type=hidden]").val(data.result.fname);
                                        return false;
                                    }',
                'fileuploadfail' => 'function(e, data) {
                                        console.log(e);
                                        console.log(data);
                                    }',
            ],
        ]); ?>

        <?= Yii::$app->request->isAjax ? $form->field($model, 'banner_text')->textarea() : $form->field($model, 'banner_text')->widget(SummernoteWidget::classname()) ?>

        <?= $form->field($model, 'link') ?>

        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group text-center">
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/site/banners'], ['class' => 'btn btn-round btn-default cancel-button']) ?>
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
