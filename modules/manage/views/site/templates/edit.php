<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use rmrevin\yii\fontawesome\component\Icon;
use app\components\widgets\SummernoteWidget;
use app\models\ActiveRecord\Template;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add').($model->id ? ' «'.$model->template_name.'»' : '');
    $this->params['select_menu'] = Url::to(['/manage/site/templates']);
    $this->registerJsFile('/js/manage/templates.js?t='.time(), ['depends' => 'yii\jui\JuiAsset']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    $form = ActiveForm::begin($form_config);

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
                <li role="presentation">
                    <a href="#tab_content_css" id="form-tab-css" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('css3') ?> <span class="hidden-xs"><?= Yii::t('app', 'CSS') ?></span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab_content_js" id="form-tab-js" role="tab" data-toggle="tab" aria-expanded="true">
                        <?= new Icon('file-code-o') ?> <span class="hidden-xs"><?= Yii::t('app', 'JS') ?></span>
                    </a>
                </li>
            </ul>

            <div id="page_form_content" class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="tab_content_basic" aria-labelledby="form-tab-basic">

                    <?= $form->field($model, 'template_name') ?>

                    <?= $form->field($model, 'layout')->dropdownList(Template::getLayouts()); ?>

                    <?= Yii::$app->request->isAjax ? $form->field($model, 'template_content')->textarea() : $form->field($model, 'template_content')->widget(SummernoteWidget::classname()) ?>

                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab_content_css" aria-labelledby="form-tab-css">
                    <div class="text-center">
                        <div class="form-group">
                            <select name="css-select" id="css-select" class="form-control">
                                <option value="" id="emp_css_sel" selected disabled><?= Yii::t('app', 'Select value') ?></option>
                                <?php foreach ($all_css AS $css_id => $css) { ?>
                                <option value="<?= $css_id ?>"<?= isset($page_css[$css_id]) ? ' disabled' :'' ?>>
                                    <?= Html::encode($css->css_name) ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <ul class="list-group" id="page_css_list">
                            <?php foreach ($page_css AS $css_id => $css) { ?>
                            <li class="list-group-item text-left" data-css="<?= $css_id ?>" id="page_css_<?= $css_id ?>">
                                <?= new Icon('reorder', ['style' => 'cursor: move']) ?> &nbsp;
                                <span style="float: right">
                                    <button type="button" class="close" aria-label="Close" data-css="<?= $css_id ?>">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </span>
                                <?= Html::encode($all_css[$css_id]->css_name) ?>
                                <input type="hidden" name="css[<?= $css_id ?>]" value="<?= $css->s_order ?>" />
                            </li>
                            <?php } ?>
                        </ul>
                        <div class="well text-center<?= $page_css ? ' hidden' : '' ?>" id="no_page_css">
                            <h3><?= Yii::t('app', 'No items') ?></h3>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab_content_js" aria-labelledby="form-tab-js">
                    <div class="text-center">
                        <div class="form-group">
                            <select name="js-select" id="js-select" class="form-control">
                                <option value="" id="emp_js_sel" selected disabled><?= Yii::t('app', 'Select value') ?></option>
                                <?php foreach ($all_js AS $js_id => $js) { ?>
                                <option value="<?= $js_id ?>"<?= isset($page_js[$js_id]) ? ' disabled' :'' ?> data-ext="<?= $js->path ? 1 : 0 ?>">
                                    <?= Html::encode($js->js_name) ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php foreach ($page_js_positions AS $js_pos => $js_data) { ?>
                        <div id="js_position_<?= $js_pos ?>">
                            <h5><?= Html::encode($js_data['open_tag']) ?></h5>
                            <ul class="list-group page_js_list" id="page_js_list_<?= $js_pos ?>" data-pos="<?= $js_pos ?>">
                            <?php foreach ($js_data['items'] AS $js_id => $js) { ?>
                                <li class="list-group-item text-left<?= $all_js[$js_id]->path ? '' : ' js-reorder' ?> js-item" data-js="<?= $js_id ?>"  id="page_js_<?= $js_id ?>">
                                    <?= $all_js[$js_id]->path ? '' : new Icon('reorder', ['style' => 'cursor: move']) ?> &nbsp;
                                    <span style="float: right">
                                        <button type="button" class="close" aria-label="Close" data-js="<?= $js_id ?>">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </span>
                                    <?= Html::encode($all_js[$js_id]->js_name) ?>
                                    <input type="hidden" name="js[<?= $js_id ?>]" value="<?= $js_pos ?>|<?= $js->s_order ?>" />
                                </li>
                            <?php } ?>
                                <li id="no_page_js_<?= $js_pos ?>"<?= $js_data['items'] ? ' class="hidden"' : '' ?> style="list-style-type: none">
                                    <div class="well text-center">
                                        <h3><?= Yii::t('app', 'No items') ?></h3>
                                    </div>
                                </li>
                            </ul>
                            <h5><?= Html::encode($js_data['close_tag']) ?></h5>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group text-center">
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/site/templates'], ['class' => 'btn btn-round btn-default cancel-button']) ?>
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
