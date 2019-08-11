<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use rmrevin\yii\fontawesome\component\Icon;

if (!$model) {
    $this->title = 'Ok';
    $ok_js = 'location.reload()';
    $this->registerJs($ok_js, yii\web\View::POS_END);
} else {
    $this->title = Yii::t('app', Yii::t('app', 'Edit').' заказ №'.$model->id.' от '.$model->getPageDate($model->add_time));
    $this->params['select_menu'] = Url::to(['/manage/market/orders']);

    if (Yii::$app->request->isAjax) {
        Pjax::begin($pjax_conf);
    }

    $this->params['submit_button'] = Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), $submit_options);
    $form = ActiveForm::begin($form_config);

    $status_list = $model->statuses;

    if ($model->status >= 0) {
        unset($status_list[-1]);
    }

    $sum_pos = 0;
    $sum_price = 0;
    $sum_total = 0;

?>

<div class="row">
    <div class="col-md-2 col-xs-hidden"></div>
    <div class="col-md-8 col-xs-12">

        <?= $form->field($model, 'status')->dropdownList($status_list); ?>

        <table class="table table-condensed">
            <tr>
                <th>Товар</th>
                <th>Кол-во</th>
                <th>Стоимость</th>
                <th>Сумма</th>
            </tr>
            <?php foreach ($model->shopOrderPosition AS $position) {
                $pos_amount = $position->price * $position->quantity;
                $sum_pos += $position->quantity;
                $sum_price += $position->price;
                $sum_total += $pos_amount;
            ?>
            <tr>
                <td><?= Html::a(Html::encode($position->product->product_name), $position->product->productLink, ['target' => '_blank']) ?></td>
                <td class="text-right"><?= $position->quantity ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($position->price, 2) ?> <?= new Icon('ruble') ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($pos_amount, 2) ?> <?= new Icon('ruble') ?></td>
            </tr>
            <?php } ?>
            <tr>
                <th class="text-right">Всего:</th>
                <td class="text-right"><?= $sum_pos ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($sum_price, 2) ?> <?= new Icon('ruble') ?></td>
                <td class="text-right"><?= Yii::$app->formatter->asDecimal($sum_total, 2) ?> <?= new Icon('ruble') ?></td>
            </tr>
        </table>

        <?= $form->field($model, 'fio') ?>

        <?= $form->field($model, 'phone')->label(Yii::t('app', 'Phone')) ?>

        <?= $form->field($model, 'address')->textarea() ?>

        <?= $form->field($model, 'email')->label('Email') ?>

        <?= $form->field($model, 'payment_type')->dropdownList($model->payment_types); ?>

        <?= $form->field($model, 'delivery_type')->dropdownList($model->delivery_types); ?>

        <?= $form->field($model, 'order_comment')->textarea() ?>

        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group text-center">
            <?= Html::a((new Icon('remove')).' '.Yii::t('app', 'Cancel'), ['/manage/market/orders'], ['class' => 'btn btn-round btn-default cancel-button']) ?>
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
