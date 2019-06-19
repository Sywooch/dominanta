<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use rmrevin\yii\fontawesome\component\Icon;

if (!$model) {
    $this->title = 'Ok';
    $this->registerJs('location.reload()', yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', $model->id ? 'Edit' : 'Add').($model->id ? ' «'.$model->role_name.'»' : '');
    $this->params['select_menu'] = Url::to(['/manage/access/roles']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $js = '
      $("#role-is_admin").on("change", function() {
          if ($(this).prop("checked")) {
              $(".rules_list").removeClass("hidden");
          } else {
              $(".rules_list").addClass("hidden");
          }
      })
    ';

    $this->registerJs($js);

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    $form = ActiveForm::begin($form_config);
?>

<div class="row">
    <div class="col-md-3 col-xs-hidden"></div>
    <div class="col-md-6 col-xs-12">

        <?= $form->field($model, 'role_name') ?>

        <?= $form->field($model, 'pid')->dropdownList($page_model::find()
                                                     ->where(['!=', 'id', $model->id ? $model->id :0 ])
                                                     ->andWhere(['status' => $page_model::STATUS_ACTIVE])
                                                     ->select(['role_name', 'id'])
                                                     ->indexBy('id')
                                                     ->column(), ['prompt' => '']) ?>

        <?= $form->field($model, 'is_admin')->checkbox(['class' => 'js-switch-custom']) ?>

        <table class="table table-condensed<?= !$model->is_admin ? ' hidden' : '' ?> rules_list">
            <tr>
                <th></th>
                <th><?= Yii::t('app', 'View') ?></th>
                <th><?= Yii::t('app', 'Adding') ?></th>
                <th><?= Yii::t('app', 'Editing') ?></th>
                <th><?= Yii::t('app', 'Deleting') ?></th>
            </tr>


        <?php foreach ($role_rules AS $one_model => $rule) { ?>
            <tr>
                <td><?= Yii::t('app', $rule['modelname']) ?></td>
                <td>
                    <?= Html::checkbox('rule['.$one_model.'][is_view]', $rule['is_view'], ['class' => 'js-switch-custom']) ?>
                </td>
                <td>
                    <?= Html::checkbox('rule['.$one_model.'][is_add]', $rule['is_add'], ['class' => 'js-switch-custom']) ?>
                </td>
                <td>
                    <?= Html::checkbox('rule['.$one_model.'][is_edit]', $rule['is_edit'], ['class' => 'js-switch-custom']) ?>
                </td>
                <td>
                    <?= Html::checkbox('rule['.$one_model.'][is_delete]', $rule['is_delete'], ['class' => 'js-switch-custom']) ?>
                </td>
            </tr>
        <?php } ?>

        </table>

        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group text-center">
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/access/roles'], ['class' => 'btn btn-round btn-default cancel-button']) ?>
            <?= $this->params['submit_button'] ?>
        </div>
        <?php } ?>

    </div>
    <div class="col-md-3 col-xs-hidden"></div>
</div>

<?php

    ActiveForm::end();

    if (Yii::$app->request->isAjax) {
        Pjax::end();
    }

?>

<?php } ?>
