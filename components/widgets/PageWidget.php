<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Page;

class PageWidget extends Widget
{
    public static $name = 'page';

    public $call_model, $url, $controller;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $page = Page::findByAddress($this->url, false);
        return $page ? $page->getHtmlContent($page->page_content, $this->controller) : '';
    }
}