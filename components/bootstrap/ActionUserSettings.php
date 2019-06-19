<?php

namespace app\components\bootstrap;

use Yii;
use yii\base\Component;

class ActionUserSettings extends Component
{
    /**
     * Initializes this component.
     */
    public function init()
    {
        parent::init();

        if (!Yii::$app->user->isGuest) {
            Yii::$app->language = Yii::$app->user->identity->language;
            Yii::$app->timeZone = Yii::$app->user->identity->timeZone;
            Yii::$app->user->identity->setActivity();
        }
    }
}
