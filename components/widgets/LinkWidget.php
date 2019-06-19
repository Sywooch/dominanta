<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class LinkWidget extends Widget
{
    public static $name = 'link';

    public $call_model, $url;

    public static function getName()
    {
        return self::$name;
    }

    public function run()
    {
        return (Url::to() == $this->url)?'#':$this->url;
    }
}