<?php

namespace app\components\filters;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\base\ActionFilter;

class ActionAdminFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if (!self::checkUser()) {
            throw new ForbiddenHttpException("Undefined user");
        }

        if (!self::checkAdmin()) {
            throw new ForbiddenHttpException('Only for admin!');
        }

        return true;
    }

    public static function checkUser()
    {
        return !Yii::$app->user->isGuest;
    }

    public static function checkAdmin()
    {
        return Yii::$app->user->identity->role->is_admin;
    }

    public static function checkAdminRules()
    {
        return self::checkUser() && self::checkAdmin();
    }
}

?>