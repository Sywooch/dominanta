<?php

namespace app\modules\manage\controllers\site;

use Yii;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\UploadedFile;
use app\modules\manage\controllers\AbstractManageController;

class JsController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Js';

    public function actionIndex()
    {
        $controller_model = $this->__model;
        $data_provider = $this->__model->search([], ['defaultOrder' => ['js_name' => SORT_ASC]]);
        return $this->render('index', ['data_provider' => $data_provider, 'search' => $this->__model]);
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
                return $this->redirect(['/manage/site/js'], 301);
            }
        }

        return $this->render('edit', ['model' => $model, 'is_modal' => true]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/site/js'], 301);
    }

    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->__model->create();
        $jsFile = UploadedFile::getInstance($model, 'upload');

        if ($jsFile) {
            if ($jsFile->extension != 'js') {
                return [
                        'status' => 'error',
                        'message' => Yii::t('app', 'The file must be with the extension "{ext}"', ['ext' => 'js']),
                      ];
            }

            $directory = $model->uploadFolder;

            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }


            $fileName = $model::generateFilename($model->upload).'.'.$jsFile->extension;
            $filePath = $directory .'/'. $fileName;
            $webPath  = str_replace(Yii::getAlias('@webroot'), '', $filePath);

            if ($jsFile->saveAs($filePath)) {
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