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
class TopAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];
    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
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

        if (file_exists(Yii::getAlias($this->basePath).'/'.$css_dir.'/top_'.$filename.'.css')) {
            $this->css[] = [
                $css_dir.'/top_'.$filename.'.css',
                'id' => 'pagesheet',
            ];
        }
    }

    public function getPageJs($filename)
    {
        $js_dir = 'js';

        if (file_exists(Yii::getAlias($this->basePath).'/'.$js_dir.'/top_'.$filename.'.js')) {
            $this->js[] = $js_dir.'/top_'.$filename.'.js';
        } else {
            $this->js[] = $js_dir.'/top_default.js';
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
