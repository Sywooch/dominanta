<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\helpers\Html;
use yii\console\Controller;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\Product;
use app\models\ActiveRecord\ProductCategory;
use app\models\ActiveRecord\ProductPhoto;
use app\models\ActiveRecord\ProductProperty;
use app\models\ActiveRecord\Property;
use app\models\ActiveRecord\Vendor;


class SitemapController extends Controller
{
    protected $xml, $root, $webpath, $sitemap, $market, $main_page, $scheme, $site_address, $yml, $yml_root;

    protected $lastmod_format = 'Y-m-d';

    protected $version = '0.9';

    public function actionUpdate()
    {
       // if (file_exists(Page::staticUploadFolder().'/sitemap.ind')) {
            $st = microtime(1);

            if ($this->generate()) {
            //    unlink(Page::staticUploadFolder().'/sitemap.ind');
                $end = (microtime(1) - $st);
                echo "Complete! ".$end.' sec.'.PHP_EOL;
            } else {
                echo "Error.".PHP_EOL;
            }
       // } else {
      //      echo "No changes.".PHP_EOL;
       // }
    }

    public function actionYml()
    {
        if (file_exists(Product::staticUploadFolder().'/catalog.ind')) {
            $st = microtime(1);

            if ($this->generateYml()) {
                unlink(Product::staticUploadFolder().'/catalog.ind');
                $end = (microtime(1) - $st);
                echo "Complete! ".$end.' sec.'.PHP_EOL;
            } else {
                echo "Error.".PHP_EOL;
            }
        } else {
            echo "No changes.".PHP_EOL;
        }
    }

    protected function configuration()
    {
        $this->webpath = isset(Yii::$aliases['@webroot']) ? Yii::getAlias('@webroot') : Yii::getAlias('@app').'/web';;
        $this->sitemap = $this->webpath.'/sitemap.xml';
        $this->market  = $this->webpath.'/yandex-market.xml';

        $options = Yii::$app->site_options;

        if (isset($options->main_page)) {
            $this->main_page = $options->main_page;
        }

        $this->scheme = isset($options->scheme) ? $options->scheme : 'http';
        $this->site_address = $options->site_address;
    }

    protected function checkConf()
    {
        if (file_exists($this->sitemap)) {
            if (!is_writable($this->sitemap)) {
                return false;
            }
        } elseif (!is_writable($this->webpath)) {
            return false;
        }

        if (file_exists($this->market)) {
            if (!is_writable($this->market)) {
                return false;
            }
        } elseif (!is_writable($this->webpath)) {
            return false;
        }

        return true;
    }

    protected function createRoot()
    {
        $this->root = $this->xml->createElement('urlset');
        $this->root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/'.$this->version);
        $this->xml->appendChild($this->root);
    }

    protected function addPage($page)
    {
        $url = $this->xml->createElement('url');
        $this->root->appendChild($url);

        foreach ($page AS $attr => $data) {
            $element = $this->xml->createElement($attr, $data);
            $url->appendChild($element);
        }
    }

    public function generate()
    {
        $this->configuration();

        if (!$this->checkConf()) {
            return false;
        }

        $this->xml = new \domDocument('1.0', 'utf-8');
        $this->createRoot();

        $pages = Page::find()->where(['status' => Page::STATUS_ACTIVE])->andWhere(['sitemap_inc' => 1])->all();

        foreach ($pages AS $page) {
            $this->addPage([
                'loc'     => $this->scheme.'://'.$this->site_address.($this->main_page == $page->slug && $page->pid === NULL ? '/' : $page->absoluteUrl),
                'lastmod' => (new \DateTime($page->last_update))->format($this->lastmod_format),
            ]);
        }

        $this->generateShopCats();
        $this->xml->save($this->sitemap);

        return true;
    }

    protected function generateShopCats($cat_id = NULL, $url = '/shop')
    {
        $items = Product::find()->where(['status' => Product::STATUS_ACTIVE])->andWhere(['cat_id' => $cat_id])->all();
        $generate_subitems = false;

        if (!$items) {
            $items = ProductCategory::find()->where(['status' => ProductCategory::STATUS_ACTIVE])->andWhere(['pid' => $cat_id])->all();
            $generate_subitems = true;
        }

        foreach ($items AS $item) {
            $this->addPage([
                'loc'     => $this->scheme.'://'.$this->site_address.$url.'/'.$item->slug,
                'lastmod' => (new \DateTime($item->last_update))->format($this->lastmod_format),
            ]);

            if ($generate_subitems) {
                $this->generateShopCats($item->id, $url.'/'.$item->slug);
            }
        }
    }

    protected function getCatList($pid = NULL, $url = '/shop')
    {
        $categories = [];
        $cats = ProductCategory::find()->where(['status' => ProductCategory::STATUS_ACTIVE])->andWhere(['pid' => $pid])->all();

        foreach ($cats AS $cat) {
            $subcats = $this->getCatList($cat->id, $url.'/'.$cat->slug);

            $categories[$cat->id] = [
                'name'    => $cat->category_name,
                'pid'     => $cat->pid,
                'subcats' => $subcats ? true : false,
                'url'     => $url.'/'.$cat->slug,
            ];

            foreach ($subcats AS $sid => $subcat) {
                $categories[$sid] = $subcat;
            }
        }

        return $categories;
    }

    protected function generateYml()
    {
        $this->configuration();

        if (!$this->checkConf()) {
            return false;
        }

        $this->yml = new \domDocument('1.0', 'utf-8');

        $yml_catalog = $this->yml->createElement('yml_catalog');
        $yml_catalog->setAttribute('date', date('Y-m-d H:i:s'));
        $this->yml->appendChild($yml_catalog);

        $shop = $this->yml->createElement('shop');
        $yml_catalog->appendChild($shop);

        $name = $this->yml->createElement('name', 'Доминанта');
        $shop->appendChild($name);

        $company = $this->yml->createElement('company', 'ООО Доминанта');
        $shop->appendChild($company);

        $url = $this->yml->createElement('url', $this->scheme.'://'.$this->site_address);
        $shop->appendChild($url);

        $email = $this->yml->createElement('email', 'info@inter-projects.ru');
        $shop->appendChild($email);

        $currencies = $this->yml->createElement('currencies');
        $shop->appendChild($currencies);

        $currency = $this->yml->createElement('currency');
        $currency->setAttribute('id', 'RUR');
        $currency->setAttribute('rate', '1');
        $currencies->appendChild($currency);

        $cat_list = $this->getCatList();

        $categories = $this->yml->createElement('categories');
        $shop->appendChild($categories);

        foreach ($cat_list AS $cid => $one_cat) {
            $category = $this->yml->createElement('category', Html::encode($one_cat['name']));
            $category->setAttribute('id', $cid);

            if ($one_cat['pid']) {
                $category->setAttribute('parentId', $one_cat['pid']);
            }

            $categories->appendChild($category);
        }

        $delivery_options = $this->yml->createElement('delivery-options');
        $shop->appendChild($delivery_options);

        $option = $this->yml->createElement('option');
        $option->setAttribute('cost', '300');
        $option->setAttribute('days', '1');
        $delivery_options->appendChild($option);

        $offers = $this->yml->createElement('offers');
        $shop->appendChild($offers);

        $vendors = Vendor::find()->indexBy('id')->all();
        $properties = Property::find()->indexBy('id')->all();

        foreach ($cat_list AS $cid => $one_cat) {
            if ($one_cat['subcats']) {
                continue;
            }

            $products = Product::find()->where(['status' => Product::STATUS_ACTIVE])
                                       ->andWhere(['cat_id' => $cid])
                                       ->all();

            foreach ($products AS $product) {
                $offer = $this->yml->createElement('offer');
                $offer->setAttribute('id', $product->id);
                $offer->setAttribute('available', 'true');
                $offers->appendChild($offer);

                if ($product->vendor_id) {
                    $vendor = $this->yml->createElement('vendor', Html::encode($vendors[$product->vendor_id]->title));
                    $offer->appendChild($vendor);
                }

                $categoryId = $this->yml->createElement('categoryId', intval($cid));
                $offer->appendChild($categoryId);

                $url = $this->yml->createElement('url', $this->scheme.'://'.$this->site_address.$one_cat['url'].'/'.$product->slug);
                $offer->appendChild($url);

                $currencyId = $this->yml->createElement('currencyId', 'RUR');
                $offer->appendChild($currencyId);

                $name = $this->yml->createElement('name', Html::encode($product->product_name));
                $offer->appendChild($name);

                $description = $this->yml->createElement('description', Html::encode(strip_tags($product->product_desc)));
                $offer->appendChild($description);

                $price_x = $this->yml->createElement('price', $product->price - ($product->price * ($product->discount / 100)));
                $offer->appendChild($price_x);

                if (!intval($product->discount) && intval($product->old_price)) {
                    $oldprice = $this->yml->createElement('oldprice', $product->old_price);
                    $offer->appendChild($oldprice);
                }

                $pickup = $this->yml->createElement('pickup', 'true');
                $offer->appendChild($pickup);

                $delivery = $this->yml->createElement('delivery', 'true');
                $offer->appendChild($delivery);

                $store = $this->yml->createElement('store', 'true');
                $offer->appendChild($store);

                $photo = ProductPhoto::find()->where(['product_id' => $product->id])->orderBy(['photo_order' => SORT_ASC])->one();

                if ($photo) {
                    $picture = $this->yml->createElement('picture', $this->scheme.'://'.$this->site_address.str_replace($this->webpath, '', $photo->photoPath));
                    $offer->appendChild($picture);
                }

                $product_properties = ProductProperty::find()->where(['product_id' => $product->id])->all();

                foreach ($product_properties AS $prop) {
                    $param = $this->yml->createElement('param', Html::encode($properties[$prop->property_id]->title));
                    $param->setAttribute('name', Html::encode($prop->property_value));
                    $offer->appendChild($param);
                }
            }
        }

        $this->yml->save($this->market);
        return true;
    }
}