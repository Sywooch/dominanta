<?php

namespace app\components\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use app\assets\EditareaAsset;

class EditareaWidget extends Widget
{
    private $_view, $_langFile;

    public $options = [], $model, $attribute, $syntax;

    public static $name = 'editarea';

    public $form_name = 'editarea';

    public $call_model;

    public static function getName()
    {
        return self::$name;
    }

    private function getLangFile()
    {
        $lang_parts = explode('-', Yii::$app->language);
        $lang_path = 'js/edit_area/langs/'.$lang_parts[0].'.js';

        if (file_exists(Yii::getAlias('@webroot').'/'.$lang_path)) {
            $this->_langFile = $lang_parts[0];
        } else {
            $this->_langFile = 'en';
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

        if (empty($this->options['rows'])) {
            $this->options['rows'] = '15';
        }

        $this->getLangFile();
        $this->_view = $this->getView();

        EditareaAsset::register($this->_view);
    }

    public function run()
    {
        $editarea_options = [
            'id' => Html::getInputId($this->model, $this->attribute),
            'start_highlight' => true,
            'font_size' => 8,
            'font_family' => 'monospace',
            'allow_resize' => 'both',
            'allow_toggle' => false,
            'language' => $this->_langFile,
            'toolbar' => 'new_document, |, charmap, |, search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight, |, fullscreen, help',
            'plugins' =>  'charmap',
            'charmap_default' => 'arrows',
        ];

        if (!empty($this->syntax)) {
            $editarea_options['syntax'] = $this->syntax;
        }

        $js = '$(document).ready(function() {editAreaLoader.init('.json_encode($editarea_options).')});';

        $this->_view->registerJs($js, \yii\web\View::POS_END);
        return Html::activeTextarea($this->model, $this->attribute, $this->options);
    }
}