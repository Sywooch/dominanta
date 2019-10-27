<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

if ($advices) {

    foreach ($advices AS $advice) {

    $preview = $advice->getPreview($advice->uploadFolder.DIRECTORY_SEPARATOR.$advice->id.'.jpg', 410, 210);

?>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <a href="<?= $advice->absoluteUrl ?>" class="thumbnail s_image_block"<?= $preview ? 'style="background: url(\''.$preview.'\') no-repeat left top;"' : '' ?>>
                    <div class="s_more" style="background: transparent">
                        <?= Html::encode($advice->page_name) ?>
                        <span><?= (new \DateTime($advice->create_time))->format('d') ?> <?= Yii::t('app', 'of '.(new \DateTime($advice->create_time))->format('F')) ?> <?= (new \DateTime($advice->create_time))->format('Y') ?></span>
                    </div>
                </a>
            </div>

<?php

    }

} else {

?>

<div class="well" style="font-size: 24px">
    <i class="fa fa-clock-o"></i> На данный момент в этот раздел ничего не добавлено.
</div>

<?php } ?>