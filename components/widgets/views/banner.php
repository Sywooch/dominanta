<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

?>

<div id="main-banners-carousel" class="carousel slide main_banners" data-ride="carousel">
     <?php if (count($banners) > 1) { ?>
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php for ($s = 0; $s < count($banners); $s++) { ?>
        <li data-target="#main-banners-carousel" data-slide-to="<?= $s ?>"<?= !$s ? ' class="active"' : '' ?>></li>
        <?php } ?>
    </ol>
    <?php } ?>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <?php for ($s = 0; $s < count($banners); $s++) { ?>
        <<?= $banners[$s]->link ? 'a href="'.$banners[$s]->link.'"' : 'div' ?> class="item<?= !$s ? ' active' : '' ?>">
            <div class="banner_caption">
                <?= $banners[$s]->banner_text ?>
            </div>
            <?= file_exists($banners[$s]->uploadFolder.'/'.$banners[$s]->id.'.jpg') ? Html::img($banners[$s]->getPreview($banners[$s]->uploadFolder.'/'.$banners[$s]->id.'.jpg', 1440, 500, true)) : '' ?>
            <div class="carousel-caption"></div>
        </<?= $banners[$s]->link ? 'a' : 'div' ?>>
        <?php } ?>
    </div>

    <?php if (count($banners) > 1) { ?>
    <!-- Controls -->
    <a class="left carousel-control" href="#main-banners-carousel" role="button" data-slide="prev"></a>
    <a class="right carousel-control" href="#main-banners-carousel" role="button" data-slide="next"></a>
    <?php } ?>
</div>



