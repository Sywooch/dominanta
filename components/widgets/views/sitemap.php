<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

function getLinks($pages, $slug) {
    if (!$pages) {
        return '';
    }

    $html = '<ul class="list-group">';

    if ($slug == '') {
        $html .= '<li class="list-group-item">';
        $html .= Html::a('Главная страница', '/');
        $html .= '</li>';
        $html .= '<li class="list-group-item">';
        $html .= Html::a('Каталог товаров', '/shop');
        $html .= '</li>';
    }

    foreach ($pages AS $page) {
        $html .= '<li class="list-group-item">';
        $html .= Html::a(Html::encode($page['page']->page_name), $slug.'/'.$page['page']->slug);
        $html .= getLinks($page['subpages'], $slug.'/'.$page['page']->slug);
        $html .= '</li>';
    }

    $html .= '</ul>';

    return $html;
}

?>

<?= getLinks($links, '') ?>