<?php

namespace app\modules\manage\controllers\site;

use Yii;
use app\modules\manage\controllers\AbstractManageController;

class SettingsController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Option';

    public static $site_options = [
        'site_title' => 'Site title',
        'site_title_position' => 'Site title position',
        'site_title_separator' => 'Site title separator',
        'main_page' => 'Main page',
        'page_extension' => 'Page extension',
        'scheme' => 'Scheme',
    ];

    public function actionIndex()
    {
        $options = $this->__model->find()->where(['option' => array_keys(self::$site_options)])->indexBy('option')->all();

        foreach (self::$site_options AS $option => $title) {
            if (!isset($options[$option])) {
                $options[$option] = $this->__model->create();
                $options[$option]->option = $option;
                $options[$option]->option_name = $title;
            }
        }

        if (Yii::$app->request->isPost) {
            $optionsLoad = Yii::$app->request->post($this->__model->modelName, []);

            foreach ($optionsLoad AS $name_value => $value) {
                $model = $options[$name_value];

                if ($model->load([$this->__model->modelName => $value]) && $model->validate()) {
                    $model->save();
                }
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));
            return $this->redirect(['/manage/site/settings'], 301);
        }

        return $this->render('index', ['site_options' => $options]);
    }
}