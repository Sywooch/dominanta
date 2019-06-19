<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use rmrevin\yii\fontawesome\component\Icon;
use yiister\gentelella\widgets\Panel;

$this->title = Yii::t('app', $page_model->entitiesName);

$form = ActiveForm::begin();
$this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);

$js = '$(document).ready(function() {
    $("#items_list").sortable({
        item: "> .main_item_panel",
        handle: ".fa-list",
        update: function() {
            $(".menuItemOrder input").each(function(idx, el){
                var sort = idx + 1;
                $(this).val(sort);
            });
        }
    });

    $(".subitems_panel").sortable({
        item: "> .sub_item_panel",
        handle: ".fa-align-justify",
        update: function() {
            $(this).find(".menuSubitemOrder input").each(function(idx, el){
                var sort = idx + 1;
                $(this).val(sort);
            });
        }
    });

    $("#items_list").disableSelection();
});';

$this->registerJs($js);

?>

<div class="row">
    <div class="col-md-2 col-xs-hidden"></div>
    <div class="col-md-8 col-xs-12">
        <div id="items_list">

        <?php foreach ($items AS $id => $item) { ?>

            <?php if ($item->pid) continue; ?>

            <?php
                Panel::begin([
                    'header' => $item->item,
                    'icon' => 'list',
                    'removable' => true,
                    'expandable' => true,
                    'options' => ['class' => 'x_panel main_item_panel']
                ])
            ?>

            <?= $form->field($item, "[$id]item") ?>

            <?= $form->field($item, "[$id]link") ?>

            <?= $form->field($item, "[$id]pid")
                     ->dropdownList($page_model::find()
                     ->where(['!=', 'id', $item->id])
                     ->andWhere(['pid' => NULL])
                     ->select(['item', 'id'])
                     ->orderBy(['item_order' => SORT_ASC])
                     ->indexBy('id')->column(), ['prompt' => '']) ?>

            <?= $form->field($item, "[$id]item_order", ['template' => '{input}', 'options' => ['class' => 'menuItemOrder']])->hiddenInput() ?>

            <?= $form->field($item, "[$id]id", ['template' => '{input}'])->hiddenInput() ?>

            <div class="subitems_panel">
            <?php foreach ($page_model::find()->where(['pid' => $item->id])->orderBy(['item_order' => SORT_ASC])->all() AS $subitem) { ?>

                <?php
                    Panel::begin([
                        'header' => $subitem->item,
                        'icon' => 'align-justify',
                        'removable' => true,
                        'expandable' => true,
                        'options' => ['class' => 'x_panel sub_item_panel']
                    ])
                ?>

                <?= $form->field($subitem, "[".$subitem->id."]item") ?>

                <?= $form->field($subitem, "[".$subitem->id."]link") ?>

                <?= $form->field($subitem, "[".$subitem->id."]pid")
                         ->dropdownList($page_model::find()
                         ->where(['pid' => NULL])
                         ->select(['item', 'id'])
                         ->orderBy(['item_order' => SORT_ASC])
                         ->indexBy('id')->column(), ['prompt' => '']) ?>

                <?= $form->field($subitem, "[".$subitem->id."]item_order", ['template' => '{input}', 'options' => ['class' => 'menuSubitemOrder']])->hiddenInput() ?>

                <?= $form->field($subitem, "[".$subitem->id."]id", ['template' => '{input}'])->hiddenInput() ?>

                <?php Panel::end() ?>

            <?php } ?>
            </div>

            <?php Panel::end() ?>

        <?php } ?>

        </div>

        <hr />

        <div>
            <?php
                Panel::begin([
                    'header' => Yii::t('app', 'Add'),
                    'icon' => 'plus',
                ])
            ?>

            <?= $form->field($model, "[0]item", ['enableClientValidation' => false]) ?>

            <?= $form->field($model, "[0]link", ['enableClientValidation' => false]) ?>

            <?= $form->field($model, "[0]pid", ['enableClientValidation' => false])
                     ->dropdownList($page_model::find()
                     ->where(['pid' => NULL])
                     ->select(['item', 'id'])
                     ->orderBy(['item_order' => SORT_ASC])
                     ->indexBy('id')->column(), ['prompt' => '']) ?>

            <?php Panel::end() ?>
        </div>

        <div class="form-group text-center" style="margin-top: 50px;">
            <?= $this->params['submit_button'] ?>
        </div>

    </div>
    <div class="col-md-2 col-xs-hidden"></div>
</div>

<style type="text/css">
  .x_panel .x_title .fa-list {
      cursor: move;
  }
</style>

<?php

ActiveForm::end();

?>