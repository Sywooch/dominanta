<?php

namespace app\components\helpers;

use Yii;

class WidgetHelper
{
    /**
     * Get app widgets
     */
    public static function get()
    {
        $widget_path = '/components/widgets';
        $widget_ns   = "app".str_replace('/', "\\", $widget_path);
        $widget_dir  = scandir(Yii::getAlias('@app').$widget_path);
        $widgets = [];

        foreach ($widget_dir AS $one_file) {
            if (strpos($one_file, 'Widget.php') === false) {
                continue;
            }

            $widget_name = str_replace('.php', '', $one_file);
            $widget_classname = $widget_ns."\\".$widget_name;

            $widgets[$widget_name] = [
                'classname' => $widget_classname,
                'realname'  => $widget_classname::getName(),
            ];
        }

        return $widgets;
    }
}