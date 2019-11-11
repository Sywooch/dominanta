<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;
use rmrevin\yii\fontawesome\component\Icon;
use dosamigos\fileupload\FileUploadUI;
use dosamigos\gallery\Gallery;
use app\models\ActiveRecord\ProductPhoto;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add').($model->id ? ' «'.Html::encode($model->product_name).'»' : '');
    $this->params['select_menu'] = Url::to(['/manage/market/products']);
    $this->registerJsFile('/js/manage/photos.js?t='.time(), ['depends' => 'yii\jui\JuiAsset']);

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
        <?php foreach ($current_photos AS $photo_id => $photo) { ?>
            <span class="img-thumbnail" id="thumb_photo<?= $photo->id ?>">
                <?= Html::img(
                    str_replace(Yii::getAlias('@webroot'), '', $model->getPreview($photo->photoPath, 150, 150)),
                    ['data' => ['id' => $photo->id]]
                ) ?>
                <input type="hidden" name="photo[<?= $photo->id ?>]" id="sort_photo<?= $photo->id ?>" value="<?= $photo->photo_order ?>" />
                <button type="button" class="close" aria-label="Close" style="position: absolute; margin-top: -150px; margin-left: 135px;" data-id="<?= $photo->id ?>"><span aria-hidden="true">&times;</span></button>
            </span>
         <?php } ?>
         </div>

        <hr />

        <?= FileUploadUI::widget([
            'model' => new ProductPhoto,
            'attribute' => 'upload',
            'url' => ['/manage/market/products/upload', 'id' => isset($model->id) ? $model->id : NULL],
            'gallery' => true,
            'fieldOptions' => [
                'accept' => 'image/jpeg'
            ],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                        productPhoto.uploadedPhoto(data.result);
                                        return false;
                                    }',
                'fileuploadfail' => 'function(e, data) {
                                        console.log(e);
                                        console.log(data);
                                    }',
            ],
        ]); ?>

        <?php if (isset($model->id)) { ?>
        <div class="well text-center">
            <b>
                <?= Html::a(new Icon('pencil').' '.Yii::t('app', 'Edit'), ['edit', 'id' => $model->id]) ?>
                &nbsp;&nbsp;
                <?= Html::a(new Icon('tag').' '.Yii::t('app', 'Labels'), ['labels', 'id' => $model->id]) ?>
            </b>
        </div>
        <?php } ?>

        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group text-center">
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/market/products', 'cat_id' => $model->cat_id], ['class' => 'btn btn-round btn-default cancel-button']) ?>
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
