<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\Panel;
use app\models\ActiveRecord\Page;

$this->title = Yii::t('app', $page_model->entitiesName);

$form = ActiveForm::begin();
$this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);

$dropdowns = [
    'site_title_position' => [
        'before' => Yii::t('app', 'Before'),
        'after' => Yii::t('app', 'After'),
    ],
    'scheme' => [
        'http' => 'http://',
        'https' => 'https://',
    ],
    'main_page' => Page::find()->select(['page_name', 'slug'])->where(['status' => Page::STATUS_ACTIVE, 'pid' => NULL])->indexBy('slug')->column(),
];

?>

<div class="row">
    <div class="col-md-2 col-xs-hidden"></div>
    <div class="col-md-8 col-xs-12">
        <div id="items_list">

        <?php foreach ($site_options AS $name => $option) { ?>

            <?php
                Panel::begin([
                    'header' => Yii::t('app', $option->option_name),
                    'icon' => 'cogs',
                ])
            ?>

            <?php if (isset($dropdowns[$name])) { ?>

            <?= $form->field($option, "[$name]option_value")->dropdownList($dropdowns[$name])->label('') ?>

            <?php } else { ?>

            <?= $form->field($option, "[$name]option_value")->label('') ?>

            <?php } ?>

            <?php Panel::end() ?>

        <?php } ?>

        </div>

        <div class="form-group text-center" style="margin-top: 50px;">
            <?= $this->params['submit_button'] ?>
        </div>

    </div>
    <div class="col-md-2 col-xs-hidden"></div>
</div>

<?php

ActiveForm::end();

?>