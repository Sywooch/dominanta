<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use app\models\ActiveRecord\Page;

class AdvicesWidget extends Widget
{
    public static $name = 'Список советов';

    public $call_model, $controller;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        $advices = Page::find()->where(['pid' => $this->call_model->id])
                                ->andWhere(['status' => Page::STATUS_ACTIVE])
                                ->orderBy(['create_time' => SORT_DESC])
                                ->all();

        return $this->render('advices', ['advices' => $advices]);
    }
}