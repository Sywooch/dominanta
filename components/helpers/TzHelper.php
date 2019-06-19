<?php

namespace app\components\helpers;

use Yii;
use yii\base\Component;

class TzHelper extends Component
{
    public static function getZones()
    {
        $tz_list = \DateTimeZone::listIdentifiers();

        $timezones = [];

        foreach ($tz_list AS $tz) {
            $tz_time = new \DateTime(NULL, new \DateTimeZone($tz));
            $timezones[$tz] = $tz.' ('.$tz_time->format('P').')';
        }

        return $timezones;
    }

}
