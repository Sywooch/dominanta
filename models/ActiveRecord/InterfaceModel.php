<?php

namespace app\models\ActiveRecord;

use Yii;

interface InterfaceModel
{
    public static function getEntityName();

    public static function getEntitiesName();
}
