<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BootAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap/dist';
    public $js = [
        'js/bootstrap.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
