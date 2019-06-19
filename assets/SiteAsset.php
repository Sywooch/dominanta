<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use Yii;
use yii\web\AssetBundle;
use yii\helpers\Url;
use app\models\ActiveRecord\Option;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SiteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'/css/bootstrap.css',
        //'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,900',
    ];
    public $js = [
      //  'js/detectmobilebrowser.js',
    ];
    public $depends = [
        'app\assets\BootAsset',
    ];

    public function init()
    {
        parent::init();
        $filename = $this->getPageFilename();
        $this->getPageJs($filename);
        $this->getPageCss($filename);
    }

    public function getPageCss($filename)
    {
        $css_dir = 'css';

        if (file_exists(Yii::getAlias($this->basePath).'/'.$css_dir.'/'.$filename.'.css')) {
            $this->css[] = [
                $css_dir.'/'.$filename.'.css',
                'id' => 'pagesheet',
            ];
        } elseif (file_exists(Yii::getAlias($this->basePath).'/'.$css_dir.'/default.css')) {
            $this->css[] = $css_dir.'/default.css';
        }
    }

    public function getPageJs($filename)
    {
        $js_dir = 'js';

        if (file_exists(Yii::getAlias($this->basePath).'/'.$js_dir.'/'.$filename.'.js')) {
            $this->js[] = $js_dir.'/'.$filename.'.js';
        } elseif (file_exists(Yii::getAlias($this->basePath).'/'.$js_dir.'/default.js')) {
            $this->js[] = $js_dir.'/default.js';
        }
    }

    public function getPageFilename()
    {
        $filename = trim(Url::to(), '/');

        if (!$filename) {
            $filename = Option::getByKey('main_page');
        }

        $filename = str_replace('/', '_', $filename);

        $page_ext = Option::getByKey('page_extension');

        if ($page_ext) {
            $filename = str_replace($page_ext, '', $filename);
        }

        return $filename;
    }
}
