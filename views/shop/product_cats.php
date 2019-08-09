<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

if ($links) {

?>

<div class="row">

    <?php foreach ($links AS $category) { ?>

    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <h4><?= Html::a(Html::encode($category['name']), $category['link']) ?></h4>

        <?php foreach ($category['items'] AS $subcat) { ?>

        <div><?= Html::a(Html::encode($subcat['name']), $subcat['link']) ?></div>

        <?php } ?>

    </div>

    <?php } ?>

</div>

<?php

} else {

?>

<div class="jumbotron">
    <p>Товаров в данной категории не обнаружено</p>
</div>


<?php

}

?>