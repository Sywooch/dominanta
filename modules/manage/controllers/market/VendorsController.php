<?php

namespace app\modules\manage\controllers\market;

use Yii;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\UploadedFile;
use app\modules\manage\controllers\AbstractManageController;

class VendorsController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Vendor';

    public function actionIndex()
    {
        $controller_model = $this->__model;
        $data_provider = $this->__model->search([], ['defaultOrder' => ['title' => SORT_ASC]]);
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
            $model->save(false);

            if ($model->photo) {
                if (basename($model->photo) != $model->id.'.jpg') {
                    rename($model->uploadFolder.'/'.basename($model->photo), $model->uploadFolder.'/'.$model->id.'.jpg');
                }
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/market/vendors'], 301);
            }
        }

        return $this->render('edit', ['model' => $model, 'is_modal' => true]);
    }

    public function actionDelete($id)
    {
        $model = $this->getById($id);
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/market/vendors'], 301);
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
                $webPath = $model->getPreview($filePath, 100, false, true);
                unlink($filePath);

                return [
                    'status' => 'ok',
                    'message' => $webPath,
                    'fname' => basename($webPath),
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