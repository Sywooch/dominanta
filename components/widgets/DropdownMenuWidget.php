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

        foreach ($main_cats AS $main_cat) {

        }


        return $this->render('dropdown_menu', ['main_cats' => $main_cats]);
    }
}