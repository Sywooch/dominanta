<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');
?>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Yii::t('app', 'Login') ?></h3>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => [
                    'class' => 'panel-body',
                ],
            ]); ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'remember_me')->checkbox([
                'template' => "{input} {label}<div>{error}</div>",
            ]) ?>

            <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <div>
                    <?= Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
                </div>
            </div>
            <?php } ?>

        <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
