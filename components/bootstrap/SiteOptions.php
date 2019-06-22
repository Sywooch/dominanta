<?php

namespace app\components\bootstrap;

use Yii;
use yii\base\Component;
use app\models\ActiveRecord\Option;

class SiteOptions extends Component
{
    protected $list = [];

    public function __get($option)
    {
        return isset($this->list[$option]) ? $this->list[$option] : null;
    }

    /**
     * Initializes this component.
     */
    public function init()
    {
        parent::init();
        $options = Option::find()->all();

        foreach ($options AS $option) {
            $this->list[$option->option] = $option->option_value;
        }
    }
}
