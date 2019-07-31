<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = '';
/*
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => '',
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => '',
]);
*/

$depends_option = ['depends' => 'app\assets\SiteAsset'];

$this->registerCssFile('/css/multiscroll/jquery.multiscroll.css', $depends_option);
$this->registerCssFile('/css/multiscroll/demo.css', $depends_option);
$this->registerCssFile('/css/main.css', $depends_option);


$this->registerJsFile('/js/multiscroll/jquery.multiscroll.extensions.min.js', $depends_option);
$this->registerJsFile('/js/main.js', $depends_option);

?>


