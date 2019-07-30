<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

?>

                            <div class="shopping_cart_text_amount">
                                <span class="shopping_cart_text">Корзина</span><br />
                                <span class="shopping_cart_amount"><span><?= $sum ?></span> <i class="fa fa-rub"></i></span>
                            </div>
                            <div class="text-right">
                                <span class="shopping_cart_badge"><?= $cnt ?></span>
                            </div>