<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use rmrevin\yii\fontawesome\component\Icon;
use app\components\helpers\TzHelper;
use app\models\ActiveRecord\Role;

if (!$model) {
    $this->title = 'Ok';
    $this->registerJs('location.reload()', yii\web\View::POS_BEGIN);
} else {
    $this->title = Yii::t('app', is_array($model) ? 'Edit' : 'Add');
    $this->params['select_menu'] = Url::to(['/manage/access/users']);
  //  $this->registerJsFile('/js/manage/warehouse/vendors.js', ['depends' => 'yii\jui\JuiAsset']);

    unset($form_config['id']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
        $submit_options['onclick'] = "\$('#user_form_content .tab-pane').each(function(){ if (\$(this).hasClass('active')) { \$('#user_form_' + \$(this).data('form')).submit() }}); return false";
    }

    $icons = [
        'add' => 'plus',
        'edit' => 'pencil',
        'password' => 'lock',
        'settings' => 'cogs',
    ];

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);

?>

<div class="row">
    <div class="col-md-2 col-xs-hidden"></div>
    <div class="col-md-8 col-xs-12">

        <div class="" role="tabpanel">
            <?php if (is_array($model)) { ?>
            <ul id="user_form_tabs" class="nav nav-tabs bar_tabs" role="tablist">
                <?php foreach ($model AS $idx => $one_model) { ?>
                <li role="presentation" <?= $idx == $sel_model ? 'class="active"' : '' ?>>
                    <a href="#tab_content_<?= $idx ?>" id="form-tab-<?= $idx ?>" role="tab" data-toggle="tab" aria-expanded="true"><?= new Icon($icons[$idx]) ?> <span class="hidden-xs"><?= Yii::t('app', ucfirst($idx)) ?></span></a>
                </li>
                <?php } ?>
            </ul>
            <?php
                } else {
                    $model = [
                        'add' => $model
                    ];
                }
            ?>
            <div id="user_form_content" class="tab-content">
                <?php foreach ($model AS $idx => $one_model) { ?>
                <div role="tabpanel" class="tab-pane fade <?= $idx == $sel_model ? 'active in': '' ?>" id="tab_content_<?= $idx ?>" aria-labelledby="form-tab-<?= $idx ?>" data-form="<?= $idx ?>">

                <?php $form = ActiveForm::begin(array_merge($form_config, ['id' => 'user_form_'.$idx])); ?>

                    <?php if ($one_model->scenario == $one_model::SCENARIO_ADD || $one_model->scenario == $one_model::SCENARIO_EDIT) { ?>

                        <?= $form->field($one_model, 'email'); ?>

                        <?= $form->field($one_model, 'realname'); ?>

                        <?= $form->field($one_model, 'phone'); ?>

                        <?= $form->field($one_model, 'role_id')->dropdownList(Role::find()
                                                                             ->where(['status' => Role::STATUS_ACTIVE])
                                                                             ->select(['role_name', 'id'])
                                                                             ->indexBy('id')
                                                                             ->column(), ['prompt' => '']) ?>

                    <?php } ?>

                    <?php if ($one_model->scenario == $one_model::SCENARIO_ADD || $one_model->scenario == $one_model::SCENARIO_PASSWORD) { ?>

                        <?= $form->field($one_model, 'password')->passwordInput(); ?>

                        <?= $form->field($one_model, 'repassword')->passwordInput(); ?>

                    <?php } ?>

                    <?php if ($one_model->scenario == $one_model::SCENARIO_SETTINGS) { ?>

                        <?= $form->field($one_model, 'timeZone')->dropdownList(TzHelper::getZones()); ?>

                        <?= $form->field($one_model, 'language')->dropdownList(['ru-RU' => 'Русский', 'en-US' => 'English']); ?>

                        <?php foreach ($notify AS $n_key => $n_data) { ?>
                            <div class="field-user-notify-<?= strtolower($n_key) ?>">
                                <label class="control-label" for="user-notify-<?= strtolower($n_key) ?>"><?= $n_data['name'] ?></label>

                                <input type="checkbox" id="user-notify-<?= strtolower($n_key) ?>" class="js-switch-custom" name="User[notify][<?= $n_key ?>]" value="1" <?= $n_data['checked']?'checked="checked"':'' ?> >
                                <div class="help-block"></div>
                            </div>
                        <?php } ?>

                    <?php } ?>

                    <input type="hidden" name="scenario" value="<?= $idx ?>" />

                    <?php if (!Yii::$app->request->isAjax) { ?>
                    <div class="form-group text-center">
                        <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/access/users'], ['class' => 'btn btn-round btn-default cancel-button']) ?>
                        <?= $this->params['submit_button'] ?>
                    </div>
                    <?php } ?>

                <?php ActiveForm::end(); ?>

                </div>
                <?php } ?>
            </div>
        </div>





    </div>
    <div class="col-md-2 col-xs-hidden"></div>
</div>

<?php } ?>
