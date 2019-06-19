<?php

namespace app\modules\manage\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class ManageController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\User';

    /**
     * Displays manage panel.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
