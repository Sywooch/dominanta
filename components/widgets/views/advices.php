<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

foreach ($advices AS $advice) {

$preview = $advice->getPreview($advice->uploadFolder.DIRECTORY_SEPARATOR.$advice->id.'.jpg', 410, 210);

?>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="thumbnail s_image_block"<?= $preview ? 'style="background: url(\''.$preview.'\') no-repeat left top;"' : '' ?>>
                    <div class="s_more" style="background: transparent">
                        <?= Html::a(Html::encode($advice->page_name), $advice->absoluteUrl) ?>
                        <span><?= (new \DateTime($advice->create_time))->format('d') ?> <?= Yii::t('app', 'of '.(new \DateTime($advice->create_time))->format('F')) ?> <?= (new \DateTime($advice->create_time))->format('Y') ?></span>
                    </div>
                </div>
            </div>

<?php } ?>