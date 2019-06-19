<?php

namespace app\modules\manage\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\web\Response;
use app\components\filters\ActionAdminFilter;
use app\models\ActiveRecords\SocialNetwork;
use app\models\Services\VkApi;

/**
 * This class is needed for inheritance by real admin controllers.
 *
 * @interface
 * @autor Roman Serov <info@inter-projects.ru>
 * @package Yii2
 */
interface AbstractManageInterface
{
    public function getModel();
}