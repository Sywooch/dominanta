<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Menu;
use app\models\ActiveRecord\Option;

class MenuWidget extends Widget
{
    public static $name = 'Меню';

    public $call_model, $controller;

    public $menu_view = 'menu';

    public $link_class = '';

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $items = Menu::find()->where(['pid' => NULL])->orderBy(['item_order' => SORT_ASC])->all();
        return $this->render($this->menu_view, ['items' => $items, 'link_class' => $this->link_class]);
    }
}