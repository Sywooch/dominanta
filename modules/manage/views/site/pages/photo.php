<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;
use rmrevin\yii\fontawesome\component\Icon;
use dosamigos\fileupload\FileUpload;
use dosamigos\gallery\Gallery;
use app\models\ActiveRecord\Page;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', 'Edit').' «'.Html::encode($model->page_name).'»';
    $this->params['select_menu'] = Url::to(['/manage/site/pages']);
    $this->registerJsFile('/js/manage/page_photo.js?t='.time(), ['depends' => 'yii\jui\JuiAsset']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    $form = ActiveForm::begin($form_config);
?>

<div class="row">
    <div class="col-md-2 hidden-xs"></div>
    <div class="col-md-8 col-xs-12">

        <div class="uploaded_photo_list">
        <?php if (file_exists($current_photo)) { ?>
            <span class="img-thumbnail" id="thumb_photo">
                <?= Html::img(str_replace(Yii::getAlias('@webroot'), '', $model->getPreview($current_photo, 450, 150))) ?>
                <input type="hidden" name="photo" value="<?= $model->id ?>" />
                <button type="button" class="close" aria-label="Close" style="position: absolute; margin-top: -150px; margin-left: 460px;"><span aria-hidden="true">&times;</span></button>
            </span>
         <?php } ?>
         </div>

        <hr />

        <?= FileUpload::widget([
            'model' => new Page,
            'attribute' => 'photo',
            'url' => ['/manage/site/pages/upload', 'id' => $model->id],
            'clientOptions' => [
                'accept' => 'image/jpeg'
            ],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                        pagePhoto.uploadedPhoto(data.result);
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
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/site/pages', 'pid' => $model->pid], ['class' => 'btn btn-round btn-default cancel-button']) ?>
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
