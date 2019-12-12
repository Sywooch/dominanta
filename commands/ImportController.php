<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use GuzzleHttp\Client;
use Cocur\Slugify\Slugify;
use app\models\ActiveRecord\Product;
use app\models\ActiveRecord\ProductCategory;
use app\models\ActiveRecord\ProductCategoryFilter;
use app\models\ActiveRecord\ProductDoc;
use app\models\ActiveRecord\ProductPhoto;
use app\models\ActiveRecord\ProductProperty;
use app\models\ActiveRecord\Property;
use app\models\ActiveRecord\Vendor;

class ImportController extends Controller
{
    private $site = 'https://kaz.saturn.net';

    private $client, $slugify, $parser, $vendors, $properties, $cat_products, $filter;

    private $catalog_url = '/catalog/';

    /**
     * This command import products
     */
    public function actionRun()
    {
        include Yii::getAlias('@app').'/lib/simple_html_dom.php';

        $this->parser  = new \simple_html_dom();
        $this->slugify = new Slugify();
        $this->client  = new Client(['base_uri' => $this->site]);
        $this->vendors = Vendor::find()->indexBy('title')->all();
        $this->properties = Property::find()->indexBy('slug')->all();
        $this->filter = [];

        $this->getCategories($this->catalog_url);

    }

    /**
     * This command normalize products
     */
    public function actionNormalize()
    {
        foreach (Product::find()->batch(100) as $products) {
            foreach ($products AS $product) {
                $product->product_name = trim($product->product_name);
                $product->title = $product->product_name;
                $product->product_desc = $this->my_mb_ucfirst($product->product_desc);
                echo $product->id.PHP_EOL;
                $product->save();
            }
        }
    }

    /**
     * This command normalize products property
     */
    public function actionSlug()
    {
        $slugify = new Slugify();

        foreach (ProductProperty::find()->batch(100) as $properties) {
            foreach ($properties AS $property) {
                $property->slug = $slugify->slugify($property->property_value);
                $property->save();
            }
        }
    }

    private function my_mb_ucfirst($str) {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }

    private function get($url, $options = [])
    {
        $default_options = [
            'http_errors' => false,
        ];

        $result      = $this->client->request('GET', $url, array_merge($default_options, $options));
        $status_code = $result->getStatusCode();

        if ($status_code != 200) {
            throw new \Exception(PHP_EOL.'Client error.'.PHP_EOL.'URL: '.$url.PHP_EOL.'Status: '.$status_code.PHP_EOL);
        }

        return $result->getBody();
    }

    private function getCategories($url, $pid = NULL, $pagination = false, $level = 1)
    {
        $current_catalog = ProductCategory::find()->where(['pid' => $pid])->indexBy('link')->all();
        $page = $this->get($url);
        $html = $this->parser->load($page);
        $categories = $html->find('a._category_level2_nav-link');

        if (count($categories)) {
            foreach ($categories AS $category) {
                if (isset($category->href)) {
                    $category_name = trim(Html::decode($category->title));
                    $category_slug = $this->slugify->slugify($category_name);
                    $category_href = $category->href;
                    $category_link = $this->site.$category_href;
echo $category_href.' : '.$category_name.' ::: '.$category_slug.PHP_EOL;

unset($category);
//echo $category_href.' : '.$category_name.' ::: '.$category_slug;

                    if (!isset($current_catalog[$category_link])) {
echo " - ADD".PHP_EOL;

                        $newProductCat = ProductCategory::createAndSave([
                            'pid' => $pid,
                            'category_name' => $category_name,
                            'slug' => $category_slug,
                            'title' => $category_name,
                            'link' => $category_link,
                            'update_sitemap' => false,
                        ]);

                        $current_catalog[$category_link] = $newProductCat;
                    } else {
                        $this->filter[$current_catalog[$category_link]->id] = ProductCategoryFilter::find()->where(['category_id' => $current_catalog[$category_link]->id])->indexBy('property_id')->all();
echo " - EXISTS".PHP_EOL;
                    }

                    if ($level >= 3) {
                        preg_match("|<a href=\"". $category_href ."\".*<img.*src=\"(.*)\"|Umsi", $page, $cat_img);

                        if ($cat_img) {
                            $catObj = $current_catalog[$category_link];
                            $directory = $catObj->uploadFolder;

                            if (!is_dir($directory)) {
                                FileHelper::createDirectory($directory);
                            }

                            $photoId  = $catObj->id;
                            $fileName = $photoId.'.jpg';
                            $filePath = $directory .DIRECTORY_SEPARATOR.$fileName;

                            if (!file_exists($filePath)) {
                                $catPhoto = file_get_contents($this->site.$cat_img[1]);
                                file_put_contents($filePath, $catPhoto);
                            }
                        }
                    }

                    sleep(rand(1, 2));
                    $this->getCategories($category_href, $current_catalog[$category_link]->id, false, $level + 1);
                }

                unset($html);
            }
        } else {
            $this->getProducts($html, $url, $pid, $pagination);
        }
    }

    private function getProducts($html, $url, $cat_id = NULL, $pagination = false) {
        echo "Products url: ".$url.PHP_EOL;

        if (!$pagination) {
            $this->cat_products = Product::find()->where(['cat_id' => $cat_id])->select(['id', 'ext_code'])->indexBy('ext_code')->all();
        }

        $ext_codes = $html->find('div.goods-card div.goods-code');
        $ext_codes_list = [];

        if (count($ext_codes)) {
            foreach ($ext_codes AS $ext_code_html) {
                $ext_codes_list[] = trim(strip_tags($ext_code_html));
            }
        }

        unset($ext_codes);

        $products = $html->find('div.goods-card a.goods-photo');

        if (count($products) != count($ext_codes_list)) {
            throw new \Exception('Invalid count ext codes ('.count($ext_codes_list).') and products ('.count($products).')');
        }

        if (count($products)) {
            foreach ($products AS $idx => $product) {
                if (isset($this->cat_products[$ext_codes_list[$idx]])) {
                    //echo "Has ext code: ".$ext_codes_list[$idx].PHP_EOL;
                    //continue;
                }

                if ($product->href) {
                    $this->getProduct($product->href, $cat_id);
                    sleep(rand(1, 5));
                }
            }
        }

        if (!$pagination) {
            $pages = $html->find('ul.pagination li a');
//var_dump($pages);
            unset($html);
echo "Pages: ".count($pages).PHP_EOL;
            if (count($pages)) {
                for ($i = 2; $i <= count($pages); $i++) {
                    echo "PAGE ".$i.PHP_EOL;
                    $this->getCategories($url.'?sort_type=desc&sort=popular&page='.$i, $cat_id, true);
                }
            }
        }
    }

    private function getProduct($href, $cat_id) {
        echo "Product: ".$href.PHP_EOL;
        $page = $this->get($href);
        $html = $this->parser->load($page);

        $good_id = $html->find('span._goods-id');

        $ext_code = trim($good_id[0]->innertext);

        if (isset($this->cat_products[$ext_code])) {
            echo "HAS PRODUCT: ".$ext_code.PHP_EOL;

            $price_block = count($html->find('div.price-base')) ? 'price-base' : 'block-price-special';
            $price_value = $html->find('div.'.$price_block.' span.block-price-value');

            if (count($price_value)) {
                $price = trim($price_value[0]->attr['data-price']);
                $unit  = trim(str_replace('Спеццена', '', $html->find('div.'.$price_block.' span.price-title')[0]->innertext));
            } else {
                $unit = 'шт.';
                $price = 0;
            }

            $this->cat_products[$ext_code]->price = $price;
            $this->cat_products[$ext_code]->update_sitemap = false;
            $this->cat_products[$ext_code]->save();

            return;
        }

        return;

        echo "ADD PRODUCT: ".$ext_code.PHP_EOL;

        $title = $html->find('h1._goods-title')[0]->innertext;
        $slug  = $this->slugify->slugify($title);

        $vendor_info = $html->find('div._goods-info-brand img');
        $vendor_id = NULL;

        if (count($vendor_info)) {
            $vendor_img = $html->find('div._goods-info-brand img')[0];
            $vendor_title = trim($vendor_img->alt);

            if (!isset($this->vendors[$vendor_title])) {
                $this->vendors[$vendor_title] = Vendor::createAndSave(['title' => $vendor_title]);
                file_put_contents(Vendor::staticUploadFolder().'/'.$this->vendors[$vendor_title]->id.'.jpg', $this->get($vendor_img->src));
            }

            $vendor_id = $this->vendors[$vendor_title]->id;
        }

        $price_block = count($html->find('div.price-base')) ? 'price-base' : 'block-price-special';
        $price_value = $html->find('div.'.$price_block.' span.block-price-value');

        if (count($price_value)) {
            $price = trim($price_value[0]->attr['data-price']);
            $unit  = trim(str_replace('Спеццена', '', $html->find('div.'.$price_block.' span.price-title')[0]->innertext));
        } else {
            $unit = 'шт.';
            $price = 0;
        }

        $new_product = [
            'cat_id' => $cat_id,
            'product_name' => trim(Html::decode($title)),
            'slug' => $slug,
            'product_desc' => $this->my_mb_ucfirst(trim($html->find('div._goods-description-text')[0]->innertext)),
            'price' => $price,
            'unit' => $unit,
            'ext_code' => $ext_code,
            'link' => $this->site.$href,
            'vendor_id' => $vendor_id,
            'last_update' => date('Y-m-d H:i:s'),
            'title' => trim($title),
            'update_sitemap' => false,
        ];

        $product_model = Product::createAndSave($new_product);

        $properties = $html->find('table._goods-params-table tr');

        foreach ($properties AS $property) {
            preg_match('/<td.*>(.*)<\/td>\s*<td.*>(.*)<\/td>/Umsi', $property, $find_property);
            unset($property);

            if ($find_property) {
                $property_name = trim(Html::decode($find_property[1]), ':');
                $property_slug = $this->slugify->slugify($property_name);
                $property_value = trim($find_property[2]);

                if (!isset($this->properties[$property_slug])) {
                    $this->properties[$property_slug] = Property::createAndSave([
                        'title' => $property_name,
                        'slug'  => $property_slug,
                    ]);
                }

                if (!isset($this->filter[$cat_id])) {
                    $this->filter[$cat_id] = [];
                }

                if (!isset($this->filter[$cat_id][$this->properties[$property_slug]->id])) {
                    $this->filter[$cat_id][$this->properties[$property_slug]->id] = ProductCategoryFilter::createAndSave([
                        'category_id'  => $cat_id,
                        'property_id'  => $this->properties[$property_slug]->id,
                        'filter_order' => count($this->filter[$cat_id]) + 1,
                        'filter_view'  => $this->properties[$property_slug]->title,
                    ]);
                }

                ProductProperty::createAndSave([
                    'product_id'  => $product_model->id,
                    'property_id' => $this->properties[$property_slug]->id,
                    'property_value' => Html::decode($property_value),
                    'slug' => $this->slugify->slugify($property_value),
                ]);
            }
        }

        $photos = $html->find('#goods-photo-list a');

        foreach ($photos AS $photo) {
            $product_photo = ProductPhoto::createAndSave([
                'product_id' => $product_model->id
            ]);

            $product_folder = ProductPhoto::staticUploadFolder().'/'.$product_model->id;

            if (!is_dir($product_folder)) {
                @mkdir($product_folder, 0777, true);
            }

            file_put_contents($product_folder.'/'.$product_photo->id.'.jpg', $this->get($photo->href));
        }

        unset($html);
    }
}