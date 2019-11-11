<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;
use rmrevin\yii\fontawesome\component\Icon;
use dosamigos\fileupload\FileUploadUI;
use app\components\widgets\SummernoteWidget;
use app\models\ActiveRecord\ProductCategory;
use app\models\ActiveRecord\ProductPhoto;
use app\models\ActiveRecord\Vendor;

$category_object = new ProductCategory;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add').($model->id ? ' «'.Html::encode($model->product_name).'»' : '');
    $this->params['select_menu'] = Url::to(['/manage/market/products']);
    $this->registerJsFile('/js/manage/products.js?t='.time(), ['depends' => 'yii\jui\JuiAsset']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $form_config['options']['onsubmit'] = "\$('#yml_message').removeClass('hidden')";

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    $form = ActiveForm::begin($form_config);
    //$slug_tpl = '{label}<div class="input-group"><div class="input-group-addon">'.$model->parentUrl.'</div>{input}'.($model->page_extension ? '<div class="input-group-addon">'.Html::encode($model->page_extension).'</div>' : '').'</div><div class="help-block">{error}</div>';

?>

<style type="text/css">
  .save_properties .input-group-addon, #product-new-propval .input-group-addon {
      cursor: pointer;
  }

  .save_properties .input-group-addon:hover, #product-new-propval .input-group-addon {
      color: #aaa;
  }
</style>

<div class="row">
    <div class="col-md-1 hidden-xs"></div>
    <div class="col-md-10 col-xs-12">

        <div class="" role="tabpanel">
            <ul id="user_form_tabs" class="nav nav-tabs bar_tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab_content_basic" id="form-tab-basic" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('file-text-o') ?> <span class="hidden-xs"><?= Yii::t('app', 'Main') ?></span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab_content_promo" id="form-tab-promo" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('percent') ?> <span class="hidden-xs"><?= Yii::t('app', 'Promo') ?></span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab_content_specifications" id="form-tab-specifications" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('sliders') ?> <span class="hidden-xs"><?= Yii::t('app', 'Specifications') ?></span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab_content_warehouse" id="form-tab-warehouse" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('cubes') ?> <span class="hidden-xs"><?= Yii::t('app', 'Warehouse') ?></span>
                    </a>
                </li>
                <?php /*<li role="presentation">
                    <a href="#tab_content_photos" id="form-tab-photos" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('image') ?> <span class="hidden-xs"><?= Yii::t('app', 'Photos') ?></span>
                    </a>
                </li> */ ?>
                <li role="presentation">
                    <a href="#tab_content_seo" id="form-tab-seo" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('globe') ?> <span class="hidden-xs"><?= Yii::t('app', 'SEO') ?></span>
                    </a>
                </li>

            </ul>

            <div id="page_form_content" class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="tab_content_basic" aria-labelledby="form-tab-basic">
                    <?= $form->field($model, 'product_name') ?>

                    <?= $form->field($model, 'cat_id')->dropdownList($category_object->getListCat(0), ['prompt' => '', 'options' => $category_object->disabled_cats]); ?>

                    <?= $form->field($model, 'slug') ?>

                    <?= $form->field($model, 'price') ?>

                    <?= $form->field($model, 'old_price') ?>

                    <?= Yii::$app->request->isAjax ? $form->field($model, 'product_desc')->textarea() : $form->field($model, 'product_desc')->widget(SummernoteWidget::classname()) ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab_content_promo" aria-labelledby="form-tab-promo">

                    <?= $form->field($model, 'discount') ?>

                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab_content_specifications" aria-labelledby="form-tab-specifications">

                    <?= $form->field($model, 'vendor_id')
                             ->dropdownList(Vendor::find()->select(['title', 'id'])
                                                          ->orderBy(['title' => SORT_ASC])
                                                          ->indexBy('id')->column(), ['prompt' => '']) ?>

                    <hr />

                    <div class="save_properties">

                        <?php foreach ($properties AS $prop_id => $property) {
                                  if ($property['filter_order'] < 1) {
                                      continue;
                                  }

                        ?>
                        <div class="form-group" id="save_property<?= $prop_id ?>">
                            <label class="control-label" for="product-prop-<?= $property['slug'] ?>">
                                <?= Html::encode($property['title']) ?>
                            </label>
                            <div class="input-group">
                                <?= Html::textInput('property['.$prop_id.']',
                                                    $property['property_value'],
                                                    ['class' => 'form-control', 'id' => 'product-prop-'.$property['slug']]) ?>
                                <span class="input-group-addon" data-id="<?= $prop_id ?>"><?= new Icon('remove') ?></span>
                            </div>
                        </div>



                        <?php } ?>

                        <?php foreach ($properties AS $prop_id => $property) {
                                  if ($property['filter_order'] > 0) {
                                      continue;
                                  }

                                  if ($property['filter_order'] == 0) {
                        ?>
                        <div class="form-group" id="save_property<?= $prop_id ?>">
                            <label class="control-label" for="product-prop-<?= $property['slug'] ?>">
                                <?= Html::encode($property['title']) ?>
                            </label>
                            <div class="input-group">
                                <?= Html::textInput('property['.$prop_id.']',
                                                    $property['property_value'],
                                                    ['class' => 'form-control', 'id' => 'product-prop-'.$property['slug']]) ?>
                                <span class="input-group-addon" data-id="<?= $prop_id ?>"><?= new Icon('remove') ?></span>
                            </div>
                        </div>

                            <?php } else { ?>
                                <?= Html::hiddenInput('property['.$prop_id.']', $property['property_value']) ?>
                            <?php } ?>
                        <?php } ?>
                    </div>

                    <hr />
                    <h4 class="text-center"><?= Yii::t('app', 'Add') ?></h4>
                    <div class="form-group">
                        <label class="control-label" for="product-new-prop-select"></label>
                        <select name="properties_list" class="form-control" id="product-new-prop-select">
                            <option value="new"><?= Yii::t('app', 'New property') ?></option>
                            <optgroup label="">
                            <?php foreach ($properties_list AS $prop_id => $prop) { ?>
                                <option id="prop_list_val<?= $prop_id ?>" value="<?= $prop_id ?>"<?= isset($properties[$prop_id]) ? ' disabled="disabled"': '' ?>>
                                    <?= Html::encode($prop->title) ?>
                                </option>
                            <?php } ?>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group" id="product-new-prop">
                        <label class="control-label" for="product-new-propname"><?= Yii::t('app', 'New property') ?></label>
                        <?= Html::textInput('propname', '', ['class' => 'form-control', 'id' => 'product-new-propname']) ?>
                    </div>
                    <div class="form-group" id="product-new-propval">
                        <label class="control-label" for="product-new-propvalue"><?= Yii::t('app', 'New value') ?></label>
                        <?= Html::textInput('propvalue', '', ['class' => 'form-control', 'id' => 'product-new-propvalue']) ?>
                    </div>

                    <?= Html::button((new Icon('plus')).' '.Yii::t('app', 'Add'), ['class' => 'add_prop_btn btn btn-round btn-default']) ?>

                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab_content_warehouse" aria-labelledby="form-tab-warehouse">
                    <?= $form->field($model, 'quantity') ?>

                    <?= $form->field($model, 'unit') ?>

                    <?= $form->field($model, 'packing_quantity') ?>

                    <?= $form->field($model, 'ext_code') ?>

                    <?= $form->field($model, 'int_code') ?>
                </div>
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
                <?php if ($model->status != $model::STATUS_ACTIVE) { ?>
                    <?= Html::a(new Icon('eye').' '.($model->status == $model::STATUS_INACTIVE ? Yii::t('app', 'Hidden') : Yii::t('app', 'Deleted')).'. '.Yii::t('app', 'Show'), ['show', 'id' => $model->id]) ?>
                <?php } ?>
                <?php if ($model->status != $model::STATUS_INACTIVE) { ?>
                    <?= Html::a(new Icon('eye-slash').' '.($model->status == $model::STATUS_ACTIVE ? Yii::t('app', 'Published') : Yii::t('app', 'Deleted')).'. '.Yii::t('app', 'Hide'), ['hide', 'id' => $model->id]) ?>
                <?php } ?>


                &nbsp;&nbsp;
                <?= Html::a(new Icon('tag').' '.Yii::t('app', 'Labels'), ['labels', 'id' => $model->id]) ?>
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
    <div class="col-md-1 hidden-xs"></div>
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
