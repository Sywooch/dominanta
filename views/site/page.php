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

$directory = $page->uploadFolder;

$current_photo = $directory.DIRECTORY_SEPARATOR.$page->id.'.jpg';

if ($page->template) {
    $template_content = $page->template->getHtmlContent($page->template->template_content, $controller);
    $page_content = $page->getHtmlContent($page->page_content, $controller);
    $page_content = str_replace('{{{content}}}', $page_content, $template_content);
} else {
    $page_content = $page->getHtmlContent($page->page_content, $controller);
}

/*
, rgba(0,0,0,0.1);
            background-blend-mode: color;
*/

if (file_exists($current_photo)) {
    $page_background = $page->getPreview($current_photo, 1440, 500);
    $css = "
        .content_block_fluid {
            background: url('".str_replace(Yii::getAlias('@webroot'), '', $page_background)."') no-repeat center top;
        }

        .breadcrumbs-line {
            position: absolute;
            z-index: 20;
        }

        .breadcrumbs-line a, .breadcrumbs-line a:visited, .breadcrumbs-line a:active, .breadcrumbs-line span, .breadcrumbs-line i {
            color: #fff !important;
        }

        .page_title {
          color: #fff;
          margin-top: 370px;
          position: absolute;
          z-index: 20;
        }

        .container_content_block {
            margin-top: 530px;
        }

        .background_overlay {
            display: block;
        }
    ";
    $this->registerCss($css, [], 'background_page_css');
}

echo $page_content;

?>

