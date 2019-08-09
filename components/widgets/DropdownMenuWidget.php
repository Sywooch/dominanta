<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\ProductCategory;

class DropdownMenuWidget extends Widget
{
    public static $name = 'Каталог категорий';

    public $call_model, $controller;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $main_cats = ProductCategory::find()->where(['status' => ProductCategory::STATUS_ACTIVE])
                                            ->andWhere(['pid' => NULL])
                                            ->orderBy(['category_name' => SORT_ASC])
                                            ->all();

        $main_links = [];
        $sub_links = [];

        foreach ($main_cats AS $main_cat) {
            $main_links[$main_cat->id] = [
                'name' => $main_cat->category_name,
                'link' => '/shop/'.$main_cat->slug,
            ];

            $sub_links[$main_cat->id] = [];

            $sub_cats = ProductCategory::find()->where(['status' => ProductCategory::STATUS_ACTIVE])
                                               ->andWhere(['pid' => $main_cat->id])
                                               ->orderBy(['category_name' => SORT_ASC])
                                               ->all();

            foreach ($sub_cats AS $sub_cat) {
                $sub_links[$main_cat->id][] = [
                    'name' => $sub_cat->category_name,
                    'link' => '/shop/'.$main_cat->slug.'/'.$sub_cat->slug,
                ];
            }
        }

        return $this->render('dropdown_menu', ['main_links' => $main_links, 'sub_links' => $sub_links]);
    }
}