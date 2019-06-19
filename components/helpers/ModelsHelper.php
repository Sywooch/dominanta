<?php

namespace app\components\helpers;

use Yii;

class ModelsHelper
{
    /**
     * Get app models
     */
    public static function get()
    {
        $models_path = '/models/ActiveRecord';
        $models_namespace   = "app".str_replace('/', "\\", $models_path);
        $models_dir  = scandir(Yii::getAlias('@app').$models_path);
        $models = [];

        foreach ($models_dir AS $one_file) {
            if (strpos($one_file, '.php') === false || strpos($one_file, 'Abstract') !== false || strpos($one_file, 'Interface') !== false) {
                continue;
            }

            $model_name = str_replace('.php', '', $one_file);
            $model_classname = $models_namespace."\\".$model_name;

            $models[$model_name] = [
                'classname' => $model_classname,
                'realname'  => $model_classname::getEntitiesName(),
            ];
        }

        return $models;
    }
}