<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$active_dropdown_left = false;
$active_dropdown_right = false;

?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="dropdown_menu">

                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="dropdown_left_col">
                        <?php foreach ($main_links AS $id => $main_link) { ?>
                            <?= Html::a(Html::encode($main_link['name']), $main_link['link'], [
                                'data' => [
                                    'category' => $id
                                ],
                                'class' => !$active_dropdown_left ? 'active_dropdown_left' : '',
                            ]) ?>
                        <?php $active_dropdown_left = true; } ?>
                        </div>
                    </div>

                    <?php foreach ($sub_links AS $id => $sub_link_block) { ?>
                    <div id="dropdown_subcat_menu_<?= $id ?>" class="dropdown_subcat_menu hidden-xs<?= $active_dropdown_right ? ' hidden' : '' ?>">

                        <div class="hidden-lg hidden-md hidden-sm col-xs-12">
                            <div class="dropdown_right_col">
                                <b><?= Html::a(Html::encode($main_links[$id]['name']), $main_links[$id]['link']) ?></b>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="dropdown_right_col">
                            <?php for ($i = 0; $i <= count($sub_link_block); $i += 2) { ?>
                                <?= isset($sub_link_block[$i]) ? Html::a(Html::encode($sub_link_block[$i]['name']), $sub_link_block[$i]['link']) : '' ?>
                            <?php } ?>
                            </div>
                        </div>

<!--                        <div class="hidden-lg hidden-md col-sm-6 hidden-xs">

                        </div>-->

                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="dropdown_right_col">
                            <?php for ($i = 1; $i <= count($sub_link_block); $i += 2) { ?>
                                <?= isset($sub_link_block[$i]) ? Html::a(Html::encode($sub_link_block[$i]['name']), $sub_link_block[$i]['link']) : '' ?>
                            <?php } ?>
                            </div>
                        </div>

                    </div>
                    <?php $active_dropdown_right = true; } ?>

                </div>
            </div>
        </div>
    </div>