<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use app\assets\SummernoteAsset;

class SummernoteWidget extends Widget
{
    private $_view, $_langFile;

    public $options = [], $model, $attribute;

    public static $name = 'summernote';

    public $form_name = 'summernote';

    public $call_model;

    public static function getName()
    {
        return self::$name;
    }

    private function getLangFile()
    {
        $lang_path = 'summernote/lang/summernote-'.Yii::$app->language.'.js';

        if (file_exists(Yii::getAlias('@webroot').'/'.$lang_path)) {
            $this->_langFile = $lang_path;
        } else {
            echo Yii::getAlias('@webroot').'/'.$lang_path;
        }
    }

    public function init()
    {
        parent::init();

        if (!$this->model) {
            return;
        }

        if (empty($this->options['class'])) {
            $this->options['class'] = 'form-control';
        }

        $this->getLangFile();
        $this->_view = $this->getView();

        if (!empty($this->_langFile)) {
            SummernoteAsset::register($this->_view)->js[] = $this->_langFile;
        } else {
            SummernoteAsset::register($this->_view);
        }
    }

    public function run()
    {
        $sn_options = [];

        if (!empty($this->_langFile)) {
            $sn_options = [
                'lang' => Yii::$app->language,
            ];
        }

        $js = '$(document).ready(function() {$("#'.Html::getInputId($this->model, $this->attribute).'").summernote('.json_encode($sn_options).');});';
        $this->_view->registerJs($js, \yii\web\View::POS_END);
        return Html::activeTextarea($this->model, $this->attribute, $this->options);
    }
}