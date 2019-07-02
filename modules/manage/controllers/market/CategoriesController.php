<?php

namespace app\modules\manage\controllers\market;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\web\Response;
use yii\web\UploadedFile;
use app\modules\manage\controllers\AbstractManageController;
use app\models\ActiveRecord\ProductCategoryFilter;

class CategoriesController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\ProductCategory';

    public function actionIndex($status = NULL, $cat_id = NULL)
    {
        if ($cat_id) {
            $controller_model = $this->getById($cat_id);
        } else {
            $controller_model = $this->__model;
        }

        if ($status == $controller_model::STATUS_DELETED) {
            $cond = ['status' => $status, 'pid' => $cat_id];
        } else {
            $cond = [
                'query' => [
                    ['!=', 'status', $controller_model::STATUS_DELETED],
                    ['pid' => $cat_id],
                ]
            ];
        }

        $data_provider = $controller_model->search($cond, ['defaultOrder' => ['category_name' => SORT_ASC]]);
        return $this->render('index', [
            'data_provider' => $data_provider,
            'search' => $this->__model,
            'model' => $controller_model,
            'status' => $status,
        ]);
    }


    public function actionEdit($id = 0)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();
        }

        $model->scenario = $model::SCENARIO_FORM;

        $filter = ProductCategoryFilter::find()->where(['category_id' => $id])
                                               ->orderBy(['filter_order' => SORT_ASC])
                                               ->indexBy('id')
                                               ->all();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);

            $save_filter = Yii::$app->request->post('cat_filter', []);

            foreach ($save_filter AS $save_filter_id => $filter_pos) {
                if ($filter[$save_filter_id]->filter_order != $filter_pos) {
                    $filter[$save_filter_id]->filter_order = $filter_pos;
                    $filter[$save_filter_id]->save();
                }
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/market/categories', 'cat_id' => $model->pid], 301);
            }
        }

        $category_filter = [];

        if ($filter) {
            $category_filter = [
                1  => [],
                0  => [],
                -1 => [],
            ];
        }

        foreach ($filter AS $one_filter) {
            $category_filter[$one_filter->filter_order > 0 ? 1 : $one_filter->filter_order][$one_filter->id] = $one_filter;
        }

        return $this->render('edit', [
            'model' => $model,
            'is_modal' => true,
            'category_filter' => $category_filter,
        ]);
    }

    public function actionDelete($id)
    {
        $model = parent::actionDelete($id);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/market/categories', 'cat_id' => $model->pid], 301);
    }

    public function actionShow($id)
    {
        $model = $this->getById($id);
        $model->status = $model::STATUS_ACTIVE;
        $model->save(false);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been published'));
        return $this->redirect(['/manage/market/categories', 'cat_id' => $model->pid], 301);
    }

    public function actionHide($id)
    {
        $model = $this->getById($id);
        $model->status = $model::STATUS_INACTIVE;
        $model->save(false);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been hidden'));
        return $this->redirect(['/manage/market/categories', 'cat_id' => $model->pid], 301);
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