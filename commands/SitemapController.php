<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\Product;
use app\models\ActiveRecord\ProductCategory;


class SitemapController extends Controller
{
    protected $xml, $root, $webpath, $sitemap, $main_page, $scheme, $site_address;

    protected $lastmod_format = 'Y-m-d';

    protected $version = '0.9';

    public function actionUpdate()
    {
        if (file_exists(Page::staticUploadFolder().'/sitemap.ind')) {
            if ($this->generate()) {
                unlink(Page::staticUploadFolder().'/sitemap.ind');
            }
        }

    }

    protected function configuration()
    {
        $this->webpath = isset(Yii::$aliases['@webroot']) ? Yii::getAlias('@webroot') : Yii::getAlias('@app').'/web';;
        $this->sitemap = $this->webpath.'/sitemap.xml';

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
}