<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "template".
 *
 * @property int $id
 * @property int $status
 * @property string $layout
 * @property string $template_name
 * @property string $template_content
 * @property string $settings
 *
 * @property Page[] $pages
 */
class Template extends AbstractModel
{
    public static $entityName = 'Template';

    public static $entitiesName = 'Templates';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['layout', 'template_name'], 'required', 'on' => self::SCENARIO_FORM],
            [['template_content', 'settings'], 'string'],
            [['layout', 'template_name'], 'string', 'max' => 255],
            ['template_content', 'match', 'pattern' => '/\\{\\{\\{content\\}\\}\\}/', 'message' => Yii::t('app', 'You need to add the value of {{{content}}} to the template content')]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = ['layout', 'template_name'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'layout' => Yii::t('app', 'Layout'),
            'template_name' => Yii::t('app', 'Template name'),
            'template_content' => Yii::t('app', 'Template content'),
            'settings' => Yii::t('app', 'Settings'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['template_id' => 'id']);
    }

    public static function getLayouts()
    {
        $directory = scandir(Yii::getAlias('@app').'/views/layouts');
        $layouts = [];

        foreach ($directory AS $layout) {
            if (strpos($layout, '.php') !== false) {
                $name = str_replace('.php', '', $layout);
                $layouts[$name] = $name;
            }
        }

        return $layouts;
    }

    public function eventBeforeInsert()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->template_content = $this->saveContentImages($this->template_content);
    }

    public function eventBeforeUpdate()
    {
        $this->template_content = $this->saveContentImages($this->template_content);
    }

    public function getTemplateCss()
    {
        return TemplateCss::find()->where(['template_id' => $this->id])->indexBy('css_id')->orderBy(['s_order' => SORT_ASC])->all();
    }

    public function getTemplateJs()
    {
        return TemplateJs::find()->where(['template_id' => $this->id])->indexBy('js_id')->orderBy(['s_order' => SORT_ASC])->all();
    }
}
