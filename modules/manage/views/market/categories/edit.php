<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;
use rmrevin\yii\fontawesome\component\Icon;
use app\components\widgets\SummernoteWidget;
use app\models\ActiveRecord\Template;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);

?>

<?php

} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add').($model->id ? ' «'.$model->category_name.'»' : '');
    $this->params['select_menu'] = Url::to(['/manage/market/categories']);
    $this->registerJsFile('/js/manage/categories.js?t='.time(), ['depends' => 'yii\jui\JuiAsset']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);

    $form_config['options']['onsubmit'] = "\$('#yml_message').removeClass('hidden')";

    $form = ActiveForm::begin($form_config);
    //$slug_tpl = '{label}<div class="input-group"><div class="input-group-addon">'.$model->parentUrl.'</div>{input}'.($model->page_extension ? '<div class="input-group-addon">'.Html::encode($model->page_extension).'</div>' : '').'</div><div class="help-block">{error}</div>';

?>

<div class="row">
    <div class="col-md-2 col-xs-hidden"></div>
    <div class="col-md-8 col-xs-12">

        <div class="" role="tabpanel">
            <ul id="user_form_tabs" class="nav nav-tabs bar_tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab_content_basic" id="form-tab-basic" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('file-text-o') ?> <span class="hidden-xs"><?= Yii::t('app', 'Main') ?></span>
                    </a>
                </li>
                <?php if ($category_filter) { ?>
                <li role="presentation">
                    <a href="#tab_content_filter" id="form-tab-filter" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('filter') ?> <span class="hidden-xs"><?= Yii::t('app', 'Category filter') ?></span>
                    </a>
                </li>
                <?php } ?>
                <li role="presentation">
                    <a href="#tab_content_seo" id="form-tab-seo" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('globe') ?> <span class="hidden-xs"><?= Yii::t('app', 'SEO') ?></span>
                    </a>
                </li>
            </ul>

            <div id="page_form_content" class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="tab_content_basic" aria-labelledby="form-tab-basic">
                    <?= $form->field($model, 'category_name') ?>

                    <?= $form->field($model, 'pid')->dropdownList($model->getListCat(0, NULL, isset($model->id) ? $model->id : NULL), ['prompt' => '', 'options' => $model->cats_with_products]); ?>

                    <?= $form->field($model, 'slug') ?>

                    <?= Yii::$app->request->isAjax ? $form->field($model, 'category_description')->textarea() : $form->field($model, 'category_description')->widget(SummernoteWidget::classname()) ?>

                </div>
                <?php if ($category_filter) { ?>
                <div role="tabpanel" class="tab-pane fade" id="tab_content_filter" aria-labelledby="tab_content_filter">
                    <?php foreach ($category_filter AS $p_status => $filter) { ?>
                    <h5>
                        <?= Yii::t('app', $p_status == 1 ? 'In the filter' : 'Not in filter') ?>
                        /
                        <?= Yii::t('app', $p_status >= 0 ? 'In the item card' : 'Not in the item card') ?>
                    </h5>
                    <ul class="list-group page_prop_list" id="page_prop_list_<?= $p_status ?>" data-pos="<?= $p_status ?>" style="padding-bottom: 15px;">
                        <?php foreach ($filter AS $filter_id => $property) { ?>
                        <li class="list-group-item text-left prop-item" data-filter="<?= $filter_id ?>"  id="filter_property_<?= $filter_id ?>">
                            <?= new Icon('reorder', ['style' => 'cursor: move']) ?>
                            <?= Html::encode($property->filter_view) ?>
                            <input type="hidden" name="cat_filter[<?= $filter_id ?>]" value="<?= $property->filter_order ?>" />
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </div>
                <?php } ?>
                <div role="tabpanel" class="tab-pane fade" id="tab_content_seo" aria-labelledby="form-tab-seo">
                    <?= $form->field($model, 'title') ?>

                    <?= $form->field($model, 'meta_keywords') ?>

                    <?= $form->field($model, 'meta_description')->textarea() ?>
                </div>
            </div>
        </div>

        <?php if (isset($model->id)) { ?>
        <div class="well text-center">
            <b>
                <?= Html::a(new Icon('image').' '.Yii::t('app', 'Photo'), ['photo', 'id' => $model->id]) ?>
            </b>
        </div>
        <?php } ?>

        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group text-center">
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/market/categories', 'cat_id' => $model->pid], ['class' => 'btn btn-round btn-default cancel-button']) ?>
            <?= $this->params['submit_button'] ?>
        </div>
        <?php } ?>

    </div>
    <div class="col-md-2 col-xs-hidden"></div>
</div>

<div class="text-center hidden" id="yml_message" style="font-size: 18px;">
    <i class="fa fa-spinner fa-pulse fa-fw"></i> Генерация импорта для Яндекс.Маркета...
</div>

<?php

    ActiveForm::end();

    if (Yii::$app->request->isAjax) {
        Pjax::end();
    }

?>

<?php } ?>
