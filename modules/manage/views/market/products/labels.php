<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;
use rmrevin\yii\fontawesome\component\Icon;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add').($model->id ? ' «'.Html::encode($model->product_name).'»' : '');
    $this->params['select_menu'] = Url::to(['/manage/market/products']);
    $this->registerJsFile('/js/manage/labels.js?t='.time(), ['depends' => 'yii\jui\JuiAsset']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    $form = ActiveForm::begin($form_config);
?>

<style type="text/css">
    .current_labels_list span.label, .all_labels_list span.label {
        margin: 5px;
        cursor: pointer;
    }
</style>

<div class="row">
    <div class="col-md-2 hidden-xs"></div>
    <div class="col-md-8 col-xs-12">

        <div>Текущие метки <i>(кликните, чтобы удалить)</i></div>

        <div class="current_labels_list">
        <?php foreach ($current_labels AS $label_id => $label) { ?>
            <span class="label label-default" id="cur_label_<?= $label->id ?>" data-id="<?= $label->id ?>">
                <?= Html::encode($label->label) ?>
                <input type="hidden" name="labels[<?= $label->id ?>]" value="1" />
            </span>
        <?php } ?>
        </div>

        <hr />

        <div>Доступные метки <i>(кликните, чтобы добавить)</i></div>

        <div class="all_labels_list">
        <?php foreach ($all_labels AS $label_id => $label) { ?>
            <span class="label label-default<?= isset($current_labels[$label->id]) ? ' hidden' : '' ?>" id="add_label_<?= $label->id ?>" data-id="<?= $label->id ?>">
                <?= Html::encode($label->label) ?>
            </span>
        <?php } ?>
        </div>

        <?php if (isset($model->id)) { ?>
        <div class="well text-center">
            <b>
                <?= Html::a(new Icon('pencil').' '.Yii::t('app', 'Edit'), ['edit', 'id' => $model->id]) ?>
                &nbsp;&nbsp;
                <?= Html::a(new Icon('image').' '.Yii::t('app', 'Photo'), ['photos', 'id' => $model->id]) ?>
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
