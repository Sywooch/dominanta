<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<?php if ($product_page == 1) { ?>
    <span class="pager_prev_page hidden-xs hidden-sm">Предыдущая страница</span>
    <span class="pager_prev_page hidden-lg hidden-md"><i class="fa fa-angle-left"></i></span>
    <span>1</span>
<?php } else { ?>
    <?= Html::a('Предыдущая страница', $base_link.$prev_page, ['class' => 'pager_prev_page hidden-xs hidden-sm']) ?>
    <?= Html::a('<i class="fa fa-angle-left"></i>', $base_link.$prev_page, ['class' => 'pager_prev_page hidden-lg hidden-md']) ?>
    <?= Html::a('1', $base_link.'1') ?>
<?php } ?>

<?= $product_page > 4 ? '<span class="pager_dots">...</span>' : '' ?>

<?php

    for ($p = ($prev_page == $pages - 1 ? $pages - 1 : $prev_page); $p <= $prev_page + 2; $p++) {
        if ($p == 1 || $p >= $pages) {
            continue;
        }

?>

        <?= $product_page == $p ? '<span>'.$p.'</span>' : Html::a($p, $base_link.$p) ?>

<?php } ?>

<?= $product_page < $pages - 2 && $product_page > 4 ? '<span class="pager_dots">...</span>' : '' ?>

<?php if ($product_page == $pages) { ?>
    <span><?= $pages ?></span>
    <span class="pager_next_page hidden-xs hidden-sm">Следующая страница</span>
    <span class="pager_next_page hidden-lg hidden-md"><i class="fa fa-angle-right"></i></span>
<?php } else { ?>
    <?= Html::a($pages, $base_link.$pages) ?>
    <?= Html::a('Следующая страница', $base_link.$next_page, ['class' => 'pager_next_page hidden-xs hidden-sm']) ?>
    <?= Html::a('<i class="fa fa-angle-right"></i>', $base_link.$next_page, ['class' => 'pager_next_page hidden-lg hidden-md']) ?>
<?php } ?>