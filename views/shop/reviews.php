<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

foreach ($reviews AS $review) {

?>
    <div class="product_review_row">
        <span class="product_review_date"><?= $review->getPageDate($review->add_time) ?></span>
        <span class="product_reviewer"><?= Html::encode($review->reviewer) ?></span>
        <div class="product_review_rate">
        <?php for ($s = 1; $s <= $review->rate; $s++) { ?>
            <span class="review_star_active"></span>
        <?php } ?>
        <?php if ($review->rate < 5) { for ($s = $review->rate + 1; $s <= 5; $s++) { ?>
            <span class="review_star_inactive"></span>
        <?php } } ?>
        </div>
        <div class="product_review_text">
            <?= Html::encode($review->review_text) ?>
        </div>
    </div>
<?php } ?>