<?php
/**
 * @copyright Copyright (c) 2015 Yiister
 * @license https://github.com/yiister/yii2-gentelella/blob/master/LICENSE
 * @link http://gentelella.yiister.ru
 */

namespace app\assets;

use yii\web\AssetBundle;

class GentellaAsset extends AssetBundle
{
    public $sourcePath = '@bower/gentelella/vendors/';
    public $css = [
        'switchery/dist/switchery.min.css',
    ];
    public $js = [
        'switchery/dist/switchery.min.js',
    ];
    public $depends = [
        'app\assets\ManageAsset',
    ];
}