<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

if ($site_options->site_title) {
    if ($site_options->site_title_position == 'before') {
        $this->title = $site_options->site_title.$site_options->site_title_separator.$page->title;
    } else {
        $this->title = $page->title.$site_options->site_title_separator.$site_options->site_title;
    }
} else {
    $this->title = $page->title;
}

if ($page->meta_keywords) {
    $this->registerMetaTag([
        'name' => 'keywords',
        'content' => $page->meta_keywords,
    ]);
}

if ($page->meta_description) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => $page->meta_description,
    ]);
}



if ($page->template) {
    $template_content = $page->template->getHtmlContent($page->template->template_content, $controller);
    $page_content = $page->getHtmlContent($page->page_content, $controller);
    $page_content = str_replace('{{{content}}}', $page_content, $template_content);
} else {
    $page_content = $page->getHtmlContent($page->page_content, $controller);
}

echo $page_content;

?>

