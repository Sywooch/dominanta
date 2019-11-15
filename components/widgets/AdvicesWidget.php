<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Page;

class AdvicesWidget extends Widget
{
    public static $name = 'Список советов';

    public $call_model, $controller;

    public $limit = false;

    public $parent_page = false;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        if (!$this->parent_page) {
            $this->parent_page = $this->call_model->id;
        }

        $advices_query = Page::find()->where(['pid' => $this->parent_page])
                                     ->andWhere(['status' => Page::STATUS_ACTIVE])
                                     ->orderBy(['create_time' => SORT_DESC]);

        if ($this->limit) {
            $advices_query->limit($this->limit);
        }

        $advices = $advices_query->all();

        return $this->render('advices', ['advices' => $advices]);
    }
}