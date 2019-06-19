<?php

namespace app\models\Service;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use app\models\ActiveRecord\Page;
use app\models\ActiveRecord\Option;

class Sitemap extends Model
{
    protected $xml, $root, $webpath, $sitemap, $main_page, $scheme;

    protected $lastmod_format = 'Y-m-d';

    protected $version = '0.9';

    protected function configuration()
    {
        $this->webpath = Yii::getAlias('@webroot');
        $this->sitemap = $this->webpath.'/sitemap.xml';

        $options = Option::find()->select(['option', 'option_value'])->where(['option' => ['main_page', 'scheme']])->indexBy('option')->column();

        $main_page = Option::findOne(['option' => 'main_page']);

        if (isset($options['main_page'])) {
            $this->main_page = $main_page->option_value;
        }

        $this->scheme = isset($options['scheme']) ? $options['scheme'] : 'http';
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
                'loc'     => $this->scheme.'://'.$_SERVER['SERVER_NAME'].(($this->main_page == $page->slug && $page->pid === NULL)?'/':$page->absoluteUrl),
                'lastmod' => (new \DateTime($page->last_update))->format($this->lastmod_format),
            ]);
        }

        $this->xml->save($this->sitemap);
    }

}