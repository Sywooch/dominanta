<?php

namespace app\modules\manage\controllers\market;

use Yii;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\UploadedFile;
use app\modules\manage\controllers\AbstractManageController;

class CategoriesController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\ProductCategory';

    public function actionIndex($cat_id = NULL)
    {
        if ($cat_id) {
            $controller_model = $this->getById($cat_id);
        } else {
            $controller_model = $this->__model;
        }

        $data_provider = $controller_model->search(['pid' => $cat_id], ['defaultOrder' => ['category_name' => SORT_ASC]]);
        return $this->render('index', ['data_provider' => $data_provider, 'search' => $this->__model, 'model' => $controller_model]);
    }


    public function actionEdit($id = 0)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();
        }

        $model->scenario = $model::SCENARIO_FORM;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/site/categories'], 301);
            }
        }

        return $this->render('edit', ['model' => $model, 'is_modal' => true]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/site/categories'], 301);
    }

    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->__model->create();
        $cssFile = UploadedFile::getInstance($model, 'upload');

        if ($cssFile) {
            if ($cssFile->extension != 'css') {
                return [
                        'status' => 'error',
                        'message' => Yii::t('app', 'The file must be with the extension "{ext}"', ['ext' => 'css']),
                      ];
            }

            $directory = $model->uploadFolder;

            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }


            $fileName = $model::generateFilename($model->upload).'.'.$cssFile->extension;
            $filePath = $directory .'/'. $fileName;
            $webPath  = str_replace(Yii::getAlias('@webroot'), '', $filePath);

            if ($cssFile->saveAs($filePath)) {
                return [
                    'status' => 'ok',
                    'message' => $webPath,
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => Yii::t('app', 'Error saving file'),
                ];
            }
        }

        return [
            'status' => 'error',
            'message' => Yii::t('app', 'File upload error'),
        ];
    }
}