<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;

?>

<?php for ($i = count($items) - 1; $i >= 0; $i--) { ?>
    <?php if ($i == 0 || $items[$i]['url'] === false) { ?>
        <?= Html::encode($items[$i]['name']).($i == 0 ? '' : ' / ') ?>
    <?php } else { ?>
        <a href="<?= Url::to($items[$i]['url']) ?>"><?= Html::encode($items[$i]['name']) ?></a> /
    <?php } ?>
<?php } ?>