<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Response;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\Product;
use app\models\ActiveRecord\ProductCategory;
use app\models\ActiveRecord\ProductCategoryFilter;
use app\models\ActiveRecord\ProductPhoto;
use app\models\ActiveRecord\ProductProperty;
use app\models\ActiveRecord\Property;
use app\models\ActiveRecord\User;

class ShopController extends AbstractController
{
    /** @var app\models\ActiveRecord\Page */
    protected $page = false;

    //public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }

    public function actionIndex($url = '')
    {
        $url = trim($url, '/');

        if ($url == '') {
            return $this->getProductCategory(NULL);
        }

        $url_parts = explode('/', $url);

        $models = [];

        foreach ($url_parts AS $url_part) {
            if (!$models) {
                $main_category = ProductCategory::findOne([
                    'slug'   => $url_part,
                    'status' => ProductCategory::STATUS_ACTIVE,
                    'pid'    => NULL,
                ]);

                if (!$main_category) {
                    return $this->actionPage('shop/'.$url);
                }

                $models[] = $main_category;
            } else {
                $parent_model = $models[count($models) - 1];

                $try_find_cat = ProductCategory::findOne([
                    'slug'   => $url_part,
                    'status' => ProductCategory::STATUS_ACTIVE,
                    'pid'    => $parent_model->id,
                ]);

                if ($try_find_cat) {
                    $models[] = $try_find_cat;
                } else {
                    $try_find_product = Product::findOne([
                        'slug'   => $url_part,
                        'status' => Product::STATUS_ACTIVE,
                        'cat_id'    => $parent_model->id,
                    ]);

                    if (!$try_find_product) {
                        return $this->actionPage('shop/'.$url);
                    }

                    $models[] = $try_find_product;
                }
            }
        }

        $model = $models[count($models) - 1];

        if ($model->modelName == 'Product') {
            return $this->getProduct($model, $models);
        } else {
            return $this->getProductCategory($model, $models);
        }
    }

    protected function getProductCategory($model = NULL, $models = [])
    {
        if ($model && $model->countProducts) {
            return $this->getProducts($model, $models);
        }

        $subcatsQuery = ProductCategory::find()->where(['status' => ProductCategory::STATUS_ACTIVE]);

        if ($model) {
            $subcatsQuery->andWhere(['pid' => $model->id]);
        } else {
            $subcatsQuery->andWhere(['pid' => NULL]);
        }

        $subcats = $subcatsQuery->orderBy(['category_name' => SORT_ASC])->all();

        $links = [];
        $parent_link = $this->getParentLink($models);

        foreach ($subcats AS $subcat) {
            $links[] = Html::a(Html::encode($subcat->category_name), $parent_link.'/'.$subcat->slug);
        }

        $this->page = Page::findByAddress('/shop/product_category', false);

        if ($this->page->template) {
            $this->layout = $this->page->template->layout;
        }

        if ($model) {
            $this->page->title = $model->title;
        } else {
            $this->page->title = 'Каталог товаров';
        }

        $site_options = Yii::$app->site_options;
        $request = Yii::$app->getRequest();
        $rendered_page = $this->render('page', [
            'page' => $this->page,
            'controller' => $this,
            'site_options' => $site_options,
            'csrfParam' => $request->csrfParam,
            'csrfToken' => $request->getCsrfToken(),
        ]);

        $replace = [
            '{{{breadcrumbs}}}' => $this->shopBreadcrumbs($models),
            '{{{page_title}}}' => $model ? $model->category_name : 'Каталог товаров',
            '{{{cats_list}}}' => implode('<br />', $links),
        ];

        return str_replace(array_keys($replace), $replace, $rendered_page);
    }

    protected function getProducts($model, $models)
    {
        $products = Product::find()->where(['status' => Product::STATUS_ACTIVE])
                                   ->andWhere(['cat_id' => $model->id])
                                   ->orderBy(['product_name' => SORT_ASC])
                                   ->all();

        $query = new Query;
        $all_properties = $query->select([
            'prop_id' => 'property.id',
            'property_value',
            Property::tableName().'.title',
            Property::tableName().'.slug',
            'filter_order'
         ])->distinct()
           ->from(ProductProperty::tableName())
           ->innerJoin(Property::tableName(), Property::tableName().'.id='.ProductProperty::tableName().'.property_id')
           ->innerJoin(ProductCategoryFilter::tableName(), Property::tableName().'.id='.ProductCategoryFilter::tableName().'.property_id')
           ->innerJoin(Product::tableName(), Product::tableName().'.id='.ProductProperty::tableName().'.product_id')
           ->where([ProductCategoryFilter::tableName().'.category_id' => $model->id])
           ->andWhere([Product::tableName().'.cat_id' => $model->id])
           ->andWhere([Product::tableName().'.status' => Product::STATUS_ACTIVE])
           ->andWhere(['>', 'filter_order', 0])
           ->orderBy(['filter_order' => SORT_ASC])
           ->all();

        $all_filter = [];

        foreach ($all_properties AS $prop) {
            if (!isset($all_filter[$prop['slug']])) {
                $all_filter[$prop['slug']] = [
                    'title' => $prop['title'],
                    'items' => [],
                ];
            }

            $all_filter[$prop['slug']]['items'][] = $prop['property_value'];
        }


//print_r($all_filter);






        $links = [];
        $parent_link = $this->getParentLink($models);

        foreach ($products AS $product) {
            $links[] = Html::a(Html::encode($product->product_name), $parent_link.'/'.$product->slug);
        }

        $this->page = Page::findByAddress('/shop/products', false);

        if ($this->page->template) {
            $this->layout = $this->page->template->layout;
        }

        $this->page->title = $model->title;

        $site_options = Yii::$app->site_options;
        $request = Yii::$app->getRequest();
        $rendered_page = $this->render('page', [
            'page' => $this->page,
            'controller' => $this,
            'site_options' => $site_options,
            'csrfParam' => $request->csrfParam,
            'csrfToken' => $request->getCsrfToken(),
        ]);

        $replace = [
            '{{{breadcrumbs}}}' => $this->shopBreadcrumbs($models),
            '{{{page_title}}}' => $model->category_name,
            '{{{products_list}}}' => implode('<br />', $links),
            '{{{filter_categories}}}' => $this->getCatsList($model, $models),
        ];

        return str_replace(array_keys($replace), $replace, $rendered_page);
    }

    function getProduct($model, $models)
    {
        $this->page = Page::findByAddress('/shop/product', false);

        if ($this->page->template) {
            $this->layout = $this->page->template->layout;
        }

        $this->page->title = $model->title;

        $site_options = Yii::$app->site_options;

        $request = Yii::$app->getRequest();

        $rendered_page = $this->render('page', [
            'page' => $this->page,
            'controller' => $this,
            'site_options' => $site_options,
            'csrfParam' => $request->csrfParam,
            'csrfToken' => $request->getCsrfToken(),
        ]);

        $vendor = $model->vendor_id ? $model->vendor : false;

        $photos = $this->productPhotos($model);

        $replace = [
            '{{{breadcrumbs}}}' => $this->shopBreadcrumbs($models),
            '{{{page_title}}}' => $model->product_name,
            '{{{product_price}}}' => Yii::$app->formatter->asDecimal(floatval($model->price), 2),
            '{{{product_old_price}}}' => $model->old_price > 0 ? Yii::$app->formatter->asDecimal(floatval($model->old_price), 2).' <i class="fa fa-ruble"></i>' : '',
            '{{{product_discount}}}' => $model->discount > 0 ? '- '.$model->discount : '',
            '{{{product_code}}}' => $model->id,
            '{{{vendor_photo}}}' => $vendor && file_exists($vendor->uploadFolder.'/'.$model->vendor_id.'.jpg') ? Html::img(str_replace(Yii::getAlias('@webroot'), '',  $vendor->uploadFolder.'/'.$model->vendor_id.'.jpg'), ['alt' => $vendor->title]) : '',
            '{{{product_description}}}' => $model->product_desc,
            '{{{product_properties}}}' => $this->productProperties($model),
            '{{{product_photo_preview}}}' => $photos['previews'],
            '{{{product_photo_slides}}}' => $photos['slides'],
        ];

        return str_replace(array_keys($replace), $replace, $rendered_page);
    }

    protected function getCatsList($model, $models)
    {
        $cats = ProductCategory::find()->where(['pid' => $model->pid])
                                       ->andWhere(['status' => ProductCategory::STATUS_ACTIVE])
                                       ->orderBy(['category_name' => SORT_ASC])
                                       ->all();

        $links = [];

        unset($models[count($models) - 1]);

        foreach ($cats AS $category) {
            $links[] = Html::a($category->category_name, $this->getParentLink($models).'/'.$category->slug, ['class' => $category->id == $model->id ? 'product_filter_subcats_active' : '']);
        }

        return implode("\n", $links);
    }

    protected function shopBreadcrumbs($models)
    {
        $url = '/shop';
        $breadcrumbs = [];

        if ($models) {
            $breadcrumbs[] = '<a href="'.$url.'">Каталог товаров</a> <i class="fa fa-angle-right"></i>';
        } else {
            $breadcrumbs[] = '<span>Каталог товаров</span>';
        }

        for ($i = 0; $i < count($models); $i++) {
            if ($i == count($models) - 1) {
                $model_name = $models[$i]->modelName == 'Product' ? $models[$i]->product_name : $models[$i]->category_name;
                $breadcrumbs[] = '<span>'.Html::encode($model_name).'</span>';
            } else {
                $url .= '/'.$models[$i]->slug;
                $breadcrumbs[] = '<a href="'.$url.'">'.Html::encode($models[$i]->category_name).'</a> <i class="fa fa-angle-right"></i>';
            }
        }

        return implode("\n", $breadcrumbs);
    }

    protected function productProperties($model)
    {
        $property_strings = [];

        $query = new Query;
        $properties = $query->select(['prop_id' => 'property.id', 'property_value', 'title', 'slug', 'filter_order'])
              ->from(ProductProperty::tableName())
              ->innerJoin(Property::tableName(), Property::tableName().'.id='.ProductProperty::tableName().'.property_id')
              ->innerJoin(ProductCategoryFilter::tableName(), Property::tableName().'.id='.ProductCategoryFilter::tableName().'.property_id')
              ->where([ProductCategoryFilter::tableName().'.category_id' => $model->cat_id])
              ->andWhere(['product_id' => $model->id])
              ->andWhere(['>=', 'filter_order', 0])
              ->indexBy('prop_id')
              ->orderBy(['filter_order' => SORT_ASC])
              ->all();

        foreach ($properties AS $property) {
            $property_strings[] = '<b>'.$property['title'].':</b> '.$property['property_value'];
        }

        return implode('<br />', $property_strings);
    }

    protected function productPhotos($model)
    {
        $photos = ProductPhoto::find()->where(['product_id' => $model->id])->orderBy(['photo_order' => SORT_ASC])->all();

        $preview = [];
        $slides = [];

        foreach ($photos AS $idx => $photo) {
            $data_slide = $idx + 1;
            $preview[] = Html::img(str_replace(Yii::getAlias('@webroot'), '', $model->getPreview($photo->photoPath, 150, 150)), [
                'class' => $preview ? '' : 'product_photo_active_slide',
                'data'  => [
                    'slide' => $data_slide,
                ]
            ]);


            $slides[] = "<div class=\"product_photo_big_slide\" data-slide=\"".$data_slide."\" style=\"background-image: url('".str_replace(Yii::getAlias('@webroot'), '', $model->getPreview($photo->photoPath, 450, 450))."')\"></div>";
        }

        return [
            'previews' => implode('', $preview),
            'slides'  => implode('', $slides),
        ];
    }

    protected function getParentLink($models)
    {
        $url = '/shop';

        for ($i = 0; $i < count($models); $i++) {
            $url .= '/'.$models[$i]->slug;
        }

        return $url;
    }
}
