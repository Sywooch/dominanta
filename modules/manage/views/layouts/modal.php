<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$bundle = yiister\gentelella\assets\Asset::register($this);

?>
<?php $this->beginPage(); ?>
<?php $this->head() ?>
<?php $this->beginBody(); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
</div>
<div class="modal-body">
    <div id="primary_modal_content">
    <?= $content ?>
    </div>
    <div id="secondary_modal_content"></div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-round btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
    <?= (isset($this->params['submit_button']))?$this->params['submit_button']:'' ?>
</div>

<?php $this->endBody(); ?>
<?php $this->endPage(); ?>