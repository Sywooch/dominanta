<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

if ($services) {

    foreach ($services AS $service) {

    $preview = $service->getPreview($service->uploadFolder.DIRECTORY_SEPARATOR.$service->id.'.jpg', 410, 280);

?>

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <a href="<?= $service->absoluteUrl ?>" class="thumbnail s_image_block"<?= $preview ? 'style="background: url(\''.$preview.'\') no-repeat;"' : '' ?>>
                        <div class="s_icon"></div>
                        <div class="s_overlay"><?= Html::encode($service->page_name) ?></div>
                        <div class="s_more">
                            Подробнее об услуге &nbsp;&nbsp;<i class="fa fa-angle-right"></i>
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
