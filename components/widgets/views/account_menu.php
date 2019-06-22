<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use Yii;
use yii\helpers\Html;

?>

                            <a href="#" id="top_personal_link"<?= Yii::$app->user->isGuest ? ' data-toggle="modal" data-target="#modal_auth"' : '' ?>>
                                <span class="top_personal_marker"></span>
                                <span class="top_personal_link">Личный&nbsp;кабинет</span>
                            </a>
                            <div id="account_menu">
                                <?= $account_menu ?>
                            </div>