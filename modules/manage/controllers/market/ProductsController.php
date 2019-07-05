<?php

namespace app\modules\manage\controllers\market;

use Yii;
use yii\db\Query;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\web\Response;
use yii\web\UploadedFile;
use Cocur\Slugify\Slugify;
use app\modules\manage\controllers\AbstractManageController;
use app\models\ActiveRecord\ProductCategory;
use app\models\ActiveRecord\ProductCategoryFilter;
use app\models\ActiveRecord\ProductPhoto;
use app\models\ActiveRecord\ProductProperty;
use app\models\ActiveRecord\Property;

class ProductsController extends AbstractManageController
{
    protected $__model = 'app\models\ActiveRecord\Product';

    public function actionIndex($status = NULL, $cat_id = NULL)
    {
        $category = false;

        if ($cat_id) {
            $category = $this->getById($cat_id, 'app\models\ActiveRecord\ProductCategory');
        }

        $controller_model = $this->__model;

        if ($status == $controller_model::STATUS_DELETED) {
            $cond = ['status' => $status];

            if ($cat_id) {
                $cond['cat_id'] = $cat_id;
            }
        } else {
            $cond = [
                'query' => [
                    ['!=', 'status', $controller_model::STATUS_DELETED],
                ]
            ];

            if ($cat_id) {
                $cond['query'][] = ['cat_id' => $cat_id];
            }
        }

        $data_provider = $controller_model->search($cond, ['defaultOrder' => ['product_name' => SORT_ASC]]);
        return $this->render('index', [
            'data_provider' => $data_provider,
            'search' => $this->__model,
            'model' => $controller_model,
            'status' => $status,
            'category' => $category,
        ]);
    }


    public function actionEdit($id = 0)
    {
        if ($id) {
            $model = $this->getById($id);
        } else {
            $model = $this->__model->create();
        }

        $properties = [];

        if (isset($model->id)) {
            $query = new Query;

            $properties = $query->select(['prop_id' => 'property.id', 'property_value', 'title', 'slug', 'filter_order'])
                  ->from(ProductProperty::tableName())
                  ->innerJoin(Property::tableName(), Property::tableName().'.id='.ProductProperty::tableName().'.property_id')
                  ->innerJoin(ProductCategoryFilter::tableName(), Property::tableName().'.id='.ProductCategoryFilter::tableName().'.property_id')
                  ->where([ProductCategoryFilter::tableName().'.category_id' => $model->cat_id])
                  ->andWhere(['product_id' => $model->id])
                  ->indexBy('prop_id')
                  ->orderBy(['filter_order' => SORT_ASC])
                  ->all();
        }

        $properties_list = Property::find()->indexBy('id')->all();

        $model->scenario = $model::SCENARIO_FORM;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);

            $save_properties = Yii::$app->request->post('property', []);

            foreach ($save_properties AS $prop_id => $save_property) {
                if ($properties[$prop_id] != $save_property) {
                    ProductProperty::updateAll(['property_value' => $save_property], ['product_id' => $model->id, 'property_id' => $prop_id]);
                }

                unset($properties[$prop_id]);
            }

            $new_properties = Yii::$app->request->post('new_property', []);

            foreach ($new_properties AS $prop_name => $prop_value) {
                if (strpos($prop_name, 'propId::') === 0) {
                    $new_prop_id = str_replace('propId::', '', $prop_name);

                    if (isset($properties_list[$new_prop_id])) {
                        $new_cat_filter = ProductCategoryFilter::findOne(['category_id' => $model->cat_id, 'property_id' => $new_prop_id]);

                        if (!$new_cat_filter) {
                            $new_cat_filter = ProductCategoryFilter::createAndSave([
                                'category_id'  => $model->cat_id,
                                'property_id'  => $new_prop_id,
                                'filter_order' => 0,
                                'filter_view'  => $properties_list[$new_prop_id]->title,
                            ]);
                        }

                        ProductProperty::createAndSave([
                            'product_id' => $model->id,
                            'property_id' => $new_prop_id,
                            'property_value' => $prop_value,
                        ]);

                        if (isset($properties[$new_prop_id])) {
                            unset($properties[$new_prop_id]);
                        }
                    }
                } else {
                    $new_prop = Property::createAndSave([
                        'title' => $prop_name,
                        'slug'  => (new Slugify())->slugify($prop_name),
                    ]);

                    $new_cat_filter = ProductCategoryFilter::createAndSave([
                        'category_id'  => $model->cat_id,
                        'property_id'  => $new_prop->id,
                        'filter_order' => 0,
                        'filter_view'  => $prop_name,
                    ]);

                    ProductProperty::createAndSave([
                        'product_id' => $model->id,
                        'property_id' => $new_prop->id,
                        'property_value' => $prop_value,
                    ]);
                }

            }

            foreach ($properties AS $prop_id => $prop) {
                ProductProperty::deleteAll(['product_id' => $model->id, 'property_id' => $prop_id]);
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been saved'));

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/market/products', 'cat_id' => $model->cat_id], 301);
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'is_modal' => true,
            'properties' => $properties,
            'properties_list' => $properties_list,
        ]);
    }

    public function actionPhotos($id)
    {
        $model = $this->getById($id);

        $current_photos = $model->getAllPhotos('id');

        if (Yii::$app->request->isPost) {
            $save_photos = Yii::$app->request->post('photo', []);

            foreach ($save_photos AS $photo_id => $save_photo) {
                if ($current_photos[$photo_id]->photo_order != $save_photo) {
                    $current_photos[$photo_id]->photo_order = $save_photo;
                    $current_photos[$photo_id]->save();
                }

                unset($current_photos[$photo_id]);
            }

            foreach ($current_photos AS $photo_id => $del_photo) {
                $del_photo->delete();
            }

            if (Yii::$app->request->isPjax) {
                $model = false;
            } else {
                return $this->redirect(['/manage/market/products', 'cat_id' => $model->cat_id], 301);
            }
        }

        return $this->render('photos', [
            'model' => $model,
            'is_modal' => true,
            'current_photos' => $current_photos,
        ]);
    }

    public function actionDelete($id)
    {
        $model = parent::actionDelete($id);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been deleted'));
        return $this->redirect(['/manage/market/products', 'cat_id' => $model->pid], 301);
    }

    public function actionShow($id)
    {
        $model = $this->getById($id);
        $model->status = $model::STATUS_ACTIVE;
        $model->save(false);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been published'));
        return $this->redirect(['/manage/market/products', 'cat_id' => $model->pid], 301);
    }

    public function actionHide($id)
    {
        $model = $this->getById($id);
        $model->status = $model::STATUS_INACTIVE;
        $model->save(false);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been hidden'));
        return $this->redirect(['/manage/market/products', 'cat_id' => $model->pid], 301);
    }

    public function actionUpload($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $product = $this->getById($id);
        $model = new ProductPhoto;
        $photo = UploadedFile::getInstance($model, 'upload');

        $ext = str_replace('.jpeg', 'jpg', strtolower($photo->extension));

        if ($photo) {
            if ($ext != 'jpg') {
                return [
                        'status' => 'error',
                        'message' => Yii::t('app', 'The file must be with the extension "{ext}"', ['ext' => '.jpg']).'=='.$ext.'==',
                      ];
            }

            $directory = $model->uploadFolder.DIRECTORY_SEPARATOR.$id;

            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }


            $fileName = $model::generateFilename($model->upload).'.'.$ext;
            $filePath = $directory .DIRECTORY_SEPARATOR. $fileName;
            $webPath  = str_replace(Yii::getAlias('@webroot'), '', $filePath);

            if ($photo->saveAs($filePath)) {

                $photo_model = $model::createAndSave([
                    'product_id' => $id,
                    'photo_order' => $model::find()->where(['product_id' => $id])->max('photo_order') + 1,
                ]);

                $newFilePath = $directory.DIRECTORY_SEPARATOR.$photo_model->id.'.'.$ext;

                rename($filePath, $newFilePath);

                return [
                    'files' => [
                        [
                            'fname' => $photo_model->id.'.'.$ext,
                            //'size' => $photo->size,
                            //'url' => str_replace(Yii::getAlias('@webroot'), '', $newFilePath),
                            'thumbnail' => str_replace(Yii::getAlias('@webroot'), '', $product->getPreview($newFilePath, 150, 150)),
                            'photo_id' => $photo_model->id,

                            //'deleteUrl' => '/manage/market/products/delphoto/'.$photo_model->id,
                            //'deleteType' => 'POST',
                        ],
                    ],
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