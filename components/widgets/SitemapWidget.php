<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Page;

class SitemapWidget extends Widget
{
    public static $name = 'Карта сайта';

    public $call_model, $controller;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        return $this->render('sitemap', ['links' => $this->getPages()]);
    }

    private function getPages($pid = NULL)
    {
        $pages = Page::find()->where(['status' => Page::STATUS_ACTIVE])->andWhere(['pid' => $pid])->all();

        $links = [];

        foreach ($pages AS $page) {
            if ($pid == NULL && $page->slug == 'index') {
                continue;
            }

            $links[] = [
                'page'     => $page,
                'subpages' => $this->getPages($page->id),
            ];
        }

        return $links;
    }
}