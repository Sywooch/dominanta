<?php

namespace app\models\ActiveRecord;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\StringHelper;
use yii\imagine\Image;
use app\components\helpers\WidgetHelper;

class AbstractModel extends ActiveRecord implements InterfaceModel
{
    public static $entityName = '';

    public static $entitiesName = '';

    public $depends_option = 'app\assets\SiteAsset';

    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED  = -1;

    const SCENARIO_FORM = 'form';
    const SCENARIO_SEARCH = 'search';

    const DB_TIME_FORMAT  = 'Y-m-d H:i:s';

    const PAGE_TIME_FORMAT  = 'd.m.Y H:i:s';

    const PAGE_DATE_FORMAT  = 'd.m.Y';

    public static $notify = false;

    protected $content_vars = [];

    protected $content_templates = [];

    protected $content_pages = [];

    protected $old_values = [];

    protected $isNewRecordFlag = false;

    public static function getEntityName()
    {
        $model = self::className();
        return $model::$entityName;
    }

    public static function getEntitiesName()
    {
        $model = self::className();
        return $model::$entitiesName;
    }

    public function compareValues()
    {
        foreach ($this->oldAttributes AS $attr_name => $attr_value) {
            if ($this->$attr_name != $attr_value) {
                $this->old_values[$attr_name] = $attr_value;
            }
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->compareValues();
            $classname = self::className();

            $ret = true;

            $this->isNewRecordFlag = $this->isNewRecord;

            if ($this->isNewRecordFlag) {
                if (method_exists($classname, 'eventBeforeInsert')) {
                    $ret = $this->eventBeforeInsert();
                }
/*
                if ($classname::$notify && method_exists($classname, 'sendMailNew')) {
                    $users_list = $this->getUsersForNotify();
                    $this->sendMailNew($users_list);
                }
*/
            } else {
                if (method_exists($classname, 'eventBeforeUpdate')) {
                    $ret = $this->eventBeforeUpdate();
                }
/*
                if ($classname::$notify && method_exists($this, 'sendMailEdit')) {
                    $users_list = $this->getUsersForNotify();
                    $this->sendMailEdit($users_list);
                }
*/
            }

            return $ret === false ? false : true;
        }

        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $classname = self::className();

        if ($this->isNewRecordFlag) {
            if (method_exists($classname, 'eventAfterInsert')) {
                $this->eventAfterInsert();
            }
        } else {
            if (method_exists($classname, 'eventAfterUpdate')) {
                $this->eventAfterUpdate();
            }
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $classname = self::className();

            $ret = true;

            foreach ($this->attributes AS $attr_name => $attr_value) {
                $this->old_values[$attr_name] = $attr_value;
            }

            if (method_exists($classname, 'eventBeforeDelete')) {
                $ret = $this->eventBeforeDelete();
            }

            return $ret === false ? false : true;
        }

        return false;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $classname = self::className();

        if (method_exists($classname, 'eventAfterDelete')) {
            $this->eventAfterDelete();
        }
    }

    public function create()
    {
        $modelname = self::className();
        $model = new $modelname;
        $model->load(Yii::$app->request->get());
        return $model;
    }

    public static function createModel($fields = [])
    {
        $modelname = self::className();
        $model = new $modelname;

        foreach ($fields AS $property => $value) {
            $model->$property = $value;
        }

        return $model;
    }

    public static function createAndSave($fields = [])
    {
        $model = self::createModel($fields);
        $model->save();
        return $model;
    }

    public function search($where = null, $sort = [])
    {
        if (isset($where['query']) && isset($where['bind_params'])) {
            $query = self::find()->where($where['query'], $where['bind_params']);
        } elseif (isset($where['query']) && is_array($where['query'])) {
            $query = self::find()->where($where['query'][0]);

            for ($c = 1; $c < count($where['query']); $c++) {
                $query->andWhere($where['query'][$c]);
            }
        } else {
            $query = self::find()->where($where);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
        ]);

        $this->load(Yii::$app->request->queryParams);

        //$this->scenario = self::SCENARIO_SEARCH;

        if (!$this->validate()) {
            return $dataProvider;
        }

        $filter_values = [];

        $scenarios = $this->scenarios();
        $schema    = $this->getTableSchema();

        if (isset($scenarios[self::SCENARIO_SEARCH])) {
            foreach ($scenarios[self::SCENARIO_SEARCH] AS $filter_field) {
                if ($schema->columns[$filter_field]->type == 'integer' || $schema->columns[$filter_field]->type == 'decimal') {
                    $filter_values[$filter_field] = $this->$filter_field;
                } elseif ($this->$filter_field || $this->$filter_field == '0') {
                    $query->andWhere(['like', $filter_field, $this->$filter_field]);
                }
            }
        }

        if ($filter_values) {
            $query->andFilterWhere($filter_values);
        }

        return $dataProvider;
    }

    public function getModelName()
    {
        return StringHelper::basename(self::className());
    }

    public static function getDbTime($time = false)
    {
        if (!is_object($time) || !($time instanceof \DateTime)) {
            $time = new \DateTime;
        }

        return $time->format(self::DB_TIME_FORMAT);
    }

    public static function getPageTime($time)
    {
        $time = new \DateTime($time);
        return $time->format(self::PAGE_TIME_FORMAT);
    }

    public static function getPageDate($time)
    {
        $time = new \DateTime($time);
        return $time->format(self::PAGE_DATE_FORMAT);
    }

    public function getPageFormatTime($attr)
    {
        return self::getPageTime($this->$attr);
    }

    public function saveContentImages($content)
    {
        preg_match_all("/<img(.*)src=\\\"data:image\/(\w+);base64,(\S+)\\\"/Usi", $content, $find, PREG_SET_ORDER);
        $counter = 0;

        foreach ($find AS $image) {
            $counter++;
            $image_ext = str_replace(['jpeg'], ['jpg'], $image[2]);
            $image_content = base64_decode($image[3]);
            $image_src = $this->saveContentImage($image_content, $image_ext, $counter);

            $content = str_replace($image[0], '<img'.$image[1].'src="'.$image_src.'"', $content);
        }

        return $content;
    }

    public function saveContentImage($image, $ext, $idx = '')
    {
        $filename = $this->uploadFolder.'/'.time().($idx?'_'.$idx:'').'.'.$ext;
        file_put_contents($filename, $image);
        return str_replace(Yii::getAlias('@webroot'), '', $filename);
    }

    public function getUploadFolder()
    {
        $folder = Yii::getAlias('@webroot').'/uploads/'.strtolower($this->modelName);

        if (!is_dir($folder)) {
            if (@!mkdir($folder, 0777, true)) {
                throw new InvalidConfigException('Invalid path to create folder '.$folder);
            }
        }

        return $folder;
    }

    public static function generateFilename($filename)
    {
        return time().substr($filename, strrpos($filename, '.'));
    }

    public function getHtmlContent($content, $controller = false)
    {
        $content = $this->replaceVars($content);
        $content = $this->replaceTemplates($content, $controller);
        $content = $this->replacePages($content, $controller);
        $content = $this->replaceWidgets($content, $controller);
        return $content;
    }

    protected function findPages($content)
    {
        preg_match_all('/\\{\\{\\{page\\|[0-9]+\\}\\}\\}/Um', $content, $finds, PREG_SET_ORDER);

        $pages = [];

        foreach ($finds AS $find) {
            $page_id = str_replace(['{{{page|', '}}}'], '', $find[0]);
            $pages[] = $page_id;
        }

        return $pages;
    }

    protected function replaceVars($content)
    {
        preg_match_all('/\\{\\{\\{var\\|[A-z0-9_-]+\\}\\}\\}/Um', $content, $finds, PREG_SET_ORDER);

        $request = Yii::$app->getRequest();
        $this->content_vars['csrfParam'] = $request->csrfParam;
        $this->content_vars['csrfToken'] = $request->getCsrfToken();

        foreach ($finds AS $find) {
            $varname = str_replace(['{{{var|', '}}}'], '', $find[0]);

            if (!isset($this->content_vars[$varname])) {
                $varvalue = Variable::findOne(['name' => $varname]);
                $this->content_vars[$varname] = ($varvalue !== NULL)?$varvalue->value:'{{{var|'.$varname.'}}}';
            }

            $content = str_replace('{{{var|'.$varname.'}}}', $this->content_vars[$varname], $content);
        }

        return $content;
    }

    protected function replaceTemplates($content, $controller = false)
    {
        if ($this->modelName == 'Template') {
            $this->registerJs($this->templateJs, $controller);
            $this->registerCss($this->templateCss, $controller);
        }

        preg_match_all('/\\{\\{\\{template\\|[0-9]+\\}\\}\\}/Um', $content, $finds, PREG_SET_ORDER);

        foreach ($finds AS $find) {
            $template_id = str_replace(['{{{template|', '}}}'], '', $find[0]);

            if (!isset($this->content_templates[$template_id])) {
                $template = Template::findOne($template_id);
                $this->content_templates[$template_id] = ($template !== NULL) ? $this->getHtmlContent($template['template_content'], $controller) : '{{{template|'.$template_id.'}}}';
            }

            $content = str_replace('{{{template|'.$template_id.'}}}', $this->content_templates[$template_id], $content);
        }

        return $content;
    }

    protected function replacePages($content, $controller = false)
    {
        if ($this->modelName == 'Page') {
            $this->registerJs($this->pageJs, $controller);
            $this->registerCss($this->pageCss, $controller);
        }

        $pages = $this->findPages($content);

        foreach ($pages AS $page_id) {
            if (!isset($this->content_pages[$page_id])) {
                $page = Page::findOne($page_id);
                $this->content_pages[$page_id] = ($page !== NULL) ? $this->getHtmlContent($page['page_content'], $controller) : '{{{page|'.$page_id.'}}}';
            }

            $content = str_replace('{{{page|'.$page_id.'}}}', $this->content_pages[$page_id], $content);
        }

        return $content;
    }

    protected function replaceWidgets($content, $controller = false)
    {
        if (!$controller) {
            return $content;
        }

        $widgets = WidgetHelper::get();

        foreach ($widgets AS $widget_name => $widget) {
            $find_widget = str_replace('Widget', '', $widget_name);
            $regexp = "/\\{\\{\\{widget\\|".strtolower($find_widget)."(\\|\\w+=.+)*\\}\\}\\}/Us"; // *

            preg_match_all($regexp, $content, $find, PREG_SET_ORDER);

            foreach ($find AS $one_res) {
                $params = [
                    'call_model' => $this
                ];

                for ($i = 1; $i < count($one_res); $i++) {
                    $arr = explode('=', trim($one_res[$i], '|'));
                    $param_name = $arr[0];
                    unset($arr[0]);
                    $params[$param_name] = implode('=', $arr);
                }

                $widget_classname = $widget['classname'];
                $controller_layout = $controller->layout;
                $controller->layout = 'widget';
                $widget_content = trim($controller->render('widget', ['widget' => $widget_classname, 'params' => $params])); //$this->getWidgetContent($widget_classname, $params);
                $controller->layout = $controller_layout;
                $content = str_replace($one_res[0], $widget_content, $content);
                $content = $this->getHtmlContent($content, $controller);
            }
        }

        return $content;
    }

    protected function registerJs($js_list, $controller)
    {
        if (!$controller) {
            return;
        }

        $view = $controller->getView();

        foreach ($js_list AS $js_id => $js) {
            if ($js->js->path) {
                if (!isset($view->jsFiles[$js->position][$js->js->path])) {
                    $view->registerJsFile($js->js->path, ['depends' => $this->depends_option, 'position' => $js->position]);
                }
            } else {
                $key = 'JS_'.$js_id;

                if (!isset($view->js[$js->position][$key])) {
                    $view->registerJs($js->js->content, $js->position, $key);
                }
            }
        }
    }

    protected function registerCss($css_list, $controller)
    {
        if (!$controller) {
            return;
        }

        $view = $controller->getView();

        foreach ($css_list AS $css_id => $css) {
            if ($css->css->path) {
                if (!isset($view->cssFiles[$css->css->path])) {
                    $view->registerCssFile($css->css->path, ['depends' => $this->depends_option]);
                }
            } else {
                $key = 'CSS_'.$css_id;

                if (!isset($view->css[$key])) {
                    $view->registerCss($css->css->content, [], $key);
                }
            }
        }
    }

    protected function getUsersForNotify()
    {
        return User::find()->with([
                  'role' => function ($query) {
                      $query->andWhere(['status' => Role::STATUS_ACTIVE])->andWhere(['is_admin' => 1]);
                  },
               ])->where(['status' => User::STATUS_ACTIVE])->andWhere(['like', 'notify', $this->modelName])->all();
    }

    public function getPreview($path, $width = 250, $height = 250)
    {
        if (!$width || !$height) {
            $size = Image::getImagine()->open($path)->getSize();

            if (!$width && !$height) {
                $width = $size->getWidth();
                $height = $size->getHeight();
            }

            if (!$width) {
                $width = ceil($size->getWidth() / ($size->getHeight() / $height));
            }

            if (!$height) {
                $height = ceil($size->getHeight() / ($size->getWidth() / $width));
            }
        }

        $preview_path = $this->uploadFolder.'/'.$width.'_'.$height.'_'.StringHelper::Basename($path);

        if (!file_exists($preview_path)) {
            Image::thumbnail($path, $width, $height)->save($preview_path, ['quality' => 75]);
        }

        return str_replace(Yii::getAlias('@app/web'), '', $preview_path);
    }
}
