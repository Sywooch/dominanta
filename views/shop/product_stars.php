<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

for ($s = 1; $s <= $avg_rate; $s++) {

?>

            <i class="fa fa-star product_star_active"></i>

<?php

}

if ($avg_rate < 5) {
    for ($s = $avg_rate + 1; $s <= 5; $s++) {

?>

            <i class="fa fa-star product_star_inactive"></i>

<?php

    }
}

?>

            <span class="product_star_count">(<?= $rate_count ?>)</span>