<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<a href="/">Главная</a> <i class="fa fa-angle-right"></i>

<?= $models ? Html::a('Каталог товаров', $url).' <i class="fa fa-angle-right"></i>' : '<span>Каталог товаров</span>' ?>

<?php

for ($i = 0; $i < count($models); $i++) {
    if ($i == count($models) - 1) {
        $model_name = $models[$i]->modelName == 'Product' ? $models[$i]->product_name : $models[$i]->category_name;

?>

        <span><?= Html::encode($model_name) ?></span>

<?php

    } else {
        $url .= '/'.$models[$i]->slug;

?>

    <?= Html::a(Html::encode($models[$i]->category_name), $url) ?> <i class="fa fa-angle-right"></i>

<?php

    }
}

?>