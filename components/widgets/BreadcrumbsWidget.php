<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Option;
use app\models\ActiveRecord\Page;

class BreadcrumbsWidget extends Widget
{
    public static $name = 'Хлебные крошки';

    public $call_model, $main_page, $controller;

    public $items = [];

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $main_page_url = Option::getByKey('main_page').Option::getByKey('page_extension');

        $this->main_page = Page::findByAddress($main_page_url);

        if (!$this->main_page || $this->main_page->id == $this->call_model->id) {
            return '';
        }

        $this->getItems($this->call_model);

        $this->items[] = [
            'name' => $this->main_page->page_name,
            'url'  => '/',
        ];

        return $this->render('breadcrumbs', ['items' => $this->items]);
    }

    public function getItems($page)
    {

        if ($page->id == $this->main_page->id) {
            return;
        }

        $this->items[] = [
            'name' => $page->page_name,
            'url'  => $page->status == Page::STATUS_ACTIVE ? $page->absoluteUrl : false,
        ];

        if (is_object($page->parent)) {
            $this->getItems($page->parent);
        }
    }
}