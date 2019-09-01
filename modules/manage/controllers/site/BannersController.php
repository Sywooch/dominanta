<?php

namespace app\modules\manage\controllers\site;

use Yii;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\UploadedFile;
use app\modules\manage\controllers\AbstractManageController;

class BannersController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Banner';

    public function actionIndex()
    {
        $controller_model = $this->__model;
        $data_provider = $this->__model->search();
        return $this->render('index', ['data_provider' => $data_provider, 'search' => $this->__model]);
    }


    public function actionEdit($id = 0)
    {
        if ($id) {
            $model = $this->getById($id);

            if (file_exists($model->uploadFolder.'/'.$model->id.'.jpg')) {
                $model->photo = str_replace(Yii::getAlias('@webroot'), '',  $model->uploadFolder.'/'.$model->id.'.jpg');
            }
        } else {
            $model = $this->__model->create();
        }

        $model->scenario = $model::SCENARIO_FORM;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $uploadFolder = $model->uploadFolder;
            $model->save(false);

            if ($model->photo) {
                if (basename($model->photo) != $model->id.'.jpg') {
                    rename($uploadFolder.'/'.basename($model->photo), $model->uploadFolder.'/'.$model->id.'.jpg');
                    $model->getPreview($model->uploadFolder.'/'.$model->id.'.jpg', 360, 125, true);
                    $model->getPreview($model->uploadFolder.'/'.$model->id.'.jpg', 720, 250, true);
                }
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/site/banners'], 301);
            }
        }

        return $this->render('edit', ['model' => $model, 'is_modal' => true]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/site/banners'], 301);
    }

    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->__model->create();
        $photo = UploadedFile::getInstance($model, 'photo');
        $ext = str_replace('.jpeg', 'jpg', strtolower($photo->extension));

        if ($photo) {
            $ext = str_replace('.jpeg', 'jpg', strtolower($photo->extension));

            if ($ext != 'jpg') {
                return [
                        'status' => 'error',
                        'message' => Yii::t('app', 'The file must be with the extension "{ext}"', ['ext' => '.jpg']),
                      ];
            }

            $directory = $model->uploadFolder;

            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }


            $fileName = $model::generateFilename($model->photo).'.'.$ext;
            $filePath = $directory .'/'. $fileName;


            if ($photo->saveAs($filePath)) {
                $webPath = $model->getPreview($filePath, 720, 250, true);

                return [
                    'status' => 'ok',
                    'message' => $webPath,
                    'fname' => basename($filePath),
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