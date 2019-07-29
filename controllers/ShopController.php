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
        $request = Yii::$app->request;
        $session = Yii::$app->session;

        $get_count = $request->get('get_count', false);

        $query = new Query;
        $all_properties = $query->select([
            'prop_id' => 'property.id',
            'property_value',
            'value_slug' => ProductProperty::tableName().'.slug',
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
           ->orderBy(['filter_order' => SORT_ASC, ProductProperty::tableName().'.slug' => SORT_ASC])
           ->all();

        $all_filter = [];
        $active_filter = $request->get('filter', []);

        $cat_max_price = $this->getCatMaxPrice($model);
        $cat_min_price = $this->getCatMinPrice($model);

        $filter_price_max = Yii::$app->request->get('filter_price_max', $cat_max_price);
        $filter_price_min = Yii::$app->request->get('filter_price_min', $cat_min_price);

        $count_query = new Query;

        if (!$get_count) {
            $product_query = new Query;

            $product_query->select([
                'prod_id' => Product::tableName().'.id',
                'product_name',
                'product_slug' => Product::tableName().'.slug',
                'real_price' => '(price - (price * (discount / 100)))',
                'old_price',
                'discount',
            ])->distinct()
              ->from(Product::tableName())
              ->where([Product::tableName().'.status' => Product::STATUS_ACTIVE])
              ->andWhere([Product::tableName().'.cat_id' => $model->id])
              ->andWhere('(price - (price * (discount / 100))) >='.intval($filter_price_min))
              ->andWhere('(price - (price * (discount / 100))) <='.intval($filter_price_max));
        }

        $count_query->select('COUNT(*) AS cnt')
                    ->from(Product::tableName())
                    ->where([Product::tableName().'.status' => Product::STATUS_ACTIVE])
                    ->andWhere([Product::tableName().'.cat_id' => $model->id])
                    ->andWhere('(price - (price * (discount / 100))) >='.intval($filter_price_min))
                    ->andWhere('(price - (price * (discount / 100))) <='.intval($filter_price_max));

        $property_filter = false;
        $additional_filter = [];

        foreach ($all_properties AS $prop) {
            if (!isset($all_filter[$prop['slug']])) {
                $all_filter[$prop['slug']] = [
                    'title' => $prop['title'],
                    'active' => isset($active_filter[$prop['slug']]) ? true : false,
                    'items' => [],
                ];
            }

            $active_filter_value = isset($active_filter[$prop['slug']]) && in_array($prop['value_slug'], $active_filter[$prop['slug']]) ? true : false;

            $all_filter[$prop['slug']]['items'][$prop['value_slug']] = [
                'name'   => $prop['property_value'],
                'active' => $active_filter_value,
            ];

            if ($active_filter_value) {
                if (!$property_filter) {
                    $property_filter = true;

                    if (!$get_count) {
                        $product_query->innerJoin(ProductProperty::tableName(), ProductProperty::tableName().'.product_id='.Product::tableName().'.id');
                    }

                    $count_query->innerJoin(ProductProperty::tableName(), ProductProperty::tableName().'.product_id='.Product::tableName().'.id');
                }

                $additional_filter[] = ProductProperty::tableName().'.property_id='.intval($prop['prop_id'])
                                       .' AND '.ProductProperty::tableName().'.slug="'.$prop['value_slug'].'"';
            }
        }

        if ($additional_filter) {
            if (!$get_count) {
                $product_query->andWhere('(('.implode(') OR (', $additional_filter).'))');
            }

            $count_query->andWhere('(('.implode(') OR (', $additional_filter).'))');
        }

        $product_count = $count_query->one()['cnt'];

        if ($get_count) {
            return $product_count;
        }

        $product_sort = $request->get('sort', false);

        if ($product_sort) {
            $session->set('sort', $product_sort);
        } else {
            $product_sort = $session->get('sort', false);
        }

        switch ($product_sort) {
            case 'cheap':
                $prod_sort = ['real_price' => SORT_ASC];
                break;
            case 'expensive':
                $prod_sort = ['real_price' => SORT_DESC];
                break;
            case 'name':
            default:
                $prod_sort = ['product_name' => SORT_ASC];
                $product_sort = 'name';
        }

        $show_count = $request->get('show', false);

        if ($show_count) {
            $session->set('show_count', $show_count);
        } else {
            $show_count = $session->get('show_count', false);
        }

        switch ($show_count) {
            case '60':
                $limit = 60;
                break;
            case '40':
                $limit = 40;
                break;
            case '20':
            default:
                $limit = 20;
                $show_count = 20;
        }

        $product_page = $request->get('page', 1);
        $offset = ($product_page - 1) * $limit;

        $products = $product_query->limit($limit)->offset($offset)->orderBy($prod_sort)->all();

        $product_list = $this->getProductList($products, $models);

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
            '{{{products_list}}}' => $product_list,
            '{{{products_sort}}}' => $this->getProductSort($models, $product_sort),
            '{{{filter_categories}}}' => $this->getCatsList($model, $models),
            '{{{show_count}}}' => $this->getProductShowCount($models, $show_count),
            '{{{all_filters}}}' => $this->getFilters($all_filter),
            '{{{pager}}}' => $this->getPager($models, $product_page, $limit, $product_count),
            '{{{cat_min_price}}}' => $cat_min_price,
            '{{{cat_max_price}}}' => $cat_max_price,
            '{{{filter_min_price}}}' => $filter_price_min,
            '{{{filter_max_price}}}' => $filter_price_max,
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

    protected function getProductList($products, $models)
    {
        $html = '';

        foreach ($products AS $product) {
            $img = ProductPhoto::find()->where(['product_id' => $product['prod_id']])
                                       ->limit(1)
                                       ->orderBy(['photo_order' => SORT_ASC])
                                       ->one();

            if ($img) {
                $product_obj = new Product();
                $product_obj->id = $product['prod_id'];
                $photo = Html::img(str_replace(Yii::getAlias('@webroot'), '', $product_obj->getPreview($img->photoPath, 142, 142)));
            } else {
                $photo = Html::img("/images/product_item.png");
            }

            $html .= '<div class="product_item"><div class="product_item_image">';
            $html .=  Html::a($photo, $this->getParentLink($models).'/'.$product['product_slug']);
            $html .= '</div><div class="product_item_title">';
            $html .= Html::a(Html::encode($product['product_name']), $this->getParentLink($models).'/'.$product['product_slug']);
            $html .= '</div><div class="product_item_unit">Цена за шт.</div><div class="product_item_price">';
            $html .= Yii::$app->formatter->asDecimal($product['real_price'], 2);
            $html .= ' <i class="fa fa-ruble"></i></div><div class="product_item_button"><button class="add_shopcart" data-id="'.$product['prod_id'].'" data-cnt="1">В корзину</button></div></div>';
        }

        return $html;
    }

    protected function getPager($models, $product_page, $limit, $product_count)
    {
        $pages = ceil($product_count / $limit);
        $html = '';

        if ($pages == 1) {
            return $html;
        }

        $base_link = $this->getParentLink($models);
        $filter = $this->getFilterLink();
        $base_link .= $filter ? $filter.'&page=' : '?page=';
        $prev_page = $product_page - 1;

        if (!$prev_page) {
            $prev_page = 1;
        }

        $next_page = $product_page + 1;

        if ($next_page > $pages) {
            $next_page = $pages;
        }

        $html .= $product_page == 1 ? '<span class="pager_prev_page">Предыдущая страница</span>' : Html::a('Предыдущая страница', $base_link.$prev_page, ['class' => 'pager_prev_page']);
        $html .= $product_page == 1 ? '<span>1</span>' : Html::a('1', $base_link.'1');

        if ($product_page > 3) {
            $html .= '<span class="pager_dots">...</span>';
        }

        for ($p = $prev_page == $pages - 1 ? $pages - 2 : $prev_page; $p <= $prev_page + 2; $p++) {
            if ($p == 1 || $p >= $pages) {
                continue;
            }

            $html .= $product_page == $p ? '<span>'.$p.'</span>' : Html::a($p, $base_link.$p);
        }

        if ($product_page < $pages - 2) {
            $html .= '<span class="pager_dots">...</span>';
        }

        $html .= $product_page == $pages ? '<span>'.$pages.'</span>' : Html::a($pages, $base_link.$pages);
        $html .= $product_page == $pages ? '<span class="pager_next_page">Следующая страница</span>' : Html::a('Следующая страница', $base_link.$next_page, ['class' => 'pager_next_page']);

        return $html;
    }

    protected function getFilterLink()
    {
        $filter = Yii::$app->request->get('filter', []);

        if (!$filter) {
            return '';
        }

        return http_build_query(['filter' => $filter]);
    }

    protected function getProductSort($models, $product_sort)
    {
        $filter = $this->getFilterLink();

        $html = ' '.Html::a('Названию', $this->getParentLink($models).($filter ? '?'.$filter.'&sort=name' : '?sort=name'), ['class' => $product_sort == 'name' ? 'product_list_action_active' : '']);
        $html .= ' '.Html::a('Цене по возрастанию', $this->getParentLink($models).($filter ? '?'.$filter.'&sort=cheap' : '?sort=cheap'), ['class' => $product_sort == 'cheap' ? 'product_list_action_active' : '']);
        $html .= ' '.Html::a('Цене по убыванию', $this->getParentLink($models).($filter ? '?'.$filter.'&sort=expensive' : '?sort=expensive'), ['class' => $product_sort == 'expensive' ? 'product_list_action_active' : '']);

        return $html;
    }

    protected function getProductShowCount($models, $show_count)
    {
        $filter = $this->getFilterLink();

        $html = ' '.Html::a('20', $this->getParentLink($models).($filter ? '?'.$filter.'&show=20' : '?show=20'), ['class' => $show_count == '20' ? 'product_list_action_active' : '']);
        $html .= ' '.Html::a('40', $this->getParentLink($models).($filter ? '?'.$filter.'&show=40' : '?show=40'), ['class' => $show_count == '40' ? 'product_list_action_active' : '']);
        $html .= ' '.Html::a('60', $this->getParentLink($models).($filter ? '?'.$filter.'&show=60' : '?show=60'), ['class' => $show_count == '60' ? 'product_list_action_active' : '']);

        return $html;
    }

    protected function getCatMaxPrice($model)
    {
        $query = new Query;

        $max_price = $query->select('MAX(price - (price * (discount / 100))) AS max_price')
                           ->from(Product::tableName())
                           ->where(['status' => Product::STATUS_ACTIVE])
                           ->andWhere(['cat_id' => $model->id])
                           ->one();

        return ceil($max_price['max_price']);
    }

    protected function getCatMinPrice($model)
    {
        $query = new Query;

        $min_price = $query->select('MIN(price - (price * (discount / 100))) AS min_price')
                           ->from(Product::tableName())
                           ->where(['status' => Product::STATUS_ACTIVE])
                           ->andWhere(['cat_id' => $model->id])
                           ->one();

        return floor($min_price['min_price']);
    }

    protected function getFilters($all_properties)
    {
        $html = '';

        foreach ($all_properties AS $slug => $one_filter) {
            $html .= '<div class="product_filter_block row">';
            $html .= '<div class="product_filter_header">'.$one_filter['title'].'</div>';
            $html .= '<div class="product_filter_values">';

            foreach ($one_filter['items'] AS $value_slug => $filter_item) {
                $html .= '<div class="product_filter_value" data-filter="'.$slug.'" data-value="'.$value_slug.'">';
                $html .= '<span class="product_filter_checkbox'.($filter_item['active'] ? '_active' : '').'"></span> '.$filter_item['name'];

                if ($filter_item['active']) {
                    $html .= '<input type="hidden" name="filter['.$slug.'][]" value="'.$value_slug.'" />';
                }

                $html .= '</div>';
            }

            $html .='</div></div>';
        }

        return $html;
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
        $properties = $query->select(['prop_id' =>  Property::tableName().'.id', 'property_value', 'title', 'filter_order'])
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
