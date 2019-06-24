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
use GuzzleHttp\Client;
use Cocur\Slugify\Slugify;
use app\models\ActiveRecord\ProductCategory;

class ImportController extends Controller
{
    private $site = 'https://kaz.saturn.net';

    private $client, $slugify, $parser;

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

        $this->getCategories($this->catalog_url);

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

    private function getCategories($url, $pid = NULL)
    {
        $current_catalog = ProductCategory::find()->where(['pid' => $pid])->indexBy('link')->all();
        $page = $this->get($url);
        $html = $this->parser->load($page);
        $categories = $html->find('a._category_level2_nav-link');

        if (count($categories)) {
            foreach ($categories AS $category) {
                if (isset($category->href)) {
                    $category_name = trim($category->title);
                    $category_slug = $this->slugify->slugify($category_name);
                    $category_link = $this->site.$category->href;

echo $category->href.' : '.$category_name.' ::: '.$category_slug;

                    if (!isset($current_catalog[$category_link])) {
echo " - ADD".PHP_EOL;

                        $newProductCat = ProductCategory::createAndSave([
                            'pid' => $pid,
                            'category_name' => $category_name,
                            'slug' => $category_slug,
                            'title' => $category_name,
                            'link' => $category_link,
                        ]);

                        $current_catalog[$category_link] = $newProductCat;
                    } else {
echo " - EXISTS".PHP_EOL;
                    }

                    sleep(2);
                    $this->getCategories($category->href, $current_catalog[$category_link]->id);
                }
            }
        } else {
            $this->getProducts($html, $pid);
        }
    }

    private function getProducts($html, $cat_id = NULL) {
        $products = $html->find('div.goods-card a.goods-photo');
        echo "Products: ".count($products).PHP_EOL;
    }
}