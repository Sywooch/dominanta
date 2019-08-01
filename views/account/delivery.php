<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\widgets\Alert;

?>

<br /><br />



<?php

$form = ActiveForm::begin(['options' => ['class' => 'delivery_form form-inline']]);

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <?= $form->field($model, 'address_name') ?>

        <?= $form->field($model, 'address') ?>

        <?= isset($model->id) ? Html::hiddenInput('id', $model->id) : '' ?>

        <?= Html::submitButton('Сохранить изменения') ?>
    </div>
    <div class="col-lg-6 col-md-6 hidden-sm hidden-xs">

    </div>
</div>

<?php

ActiveForm::end();

?>