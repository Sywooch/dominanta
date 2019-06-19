<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord;

/**
 * This is the model class for table "js".
 *
 * @property int $id
 * @property string $js_name
 * @property string $path
 * @property string $content
 *
 * @property PageJs[] $pageJs
 * @property TemplateJs[] $templateJs
 */
class Js extends AbstractModel
{
    public static $entityName = 'JS';

    public static $entitiesName = 'JS';

    public $upload;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['js_name'], 'required', 'on' => self::SCENARIO_FORM],
            [['js_name'], 'unique', 'on' => self::SCENARIO_FORM],
            [['content'], 'string'],
            [['js_name', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = ['js_name'];

        return $scenarios;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'js_name' => Yii::t('app', 'JS Name'),
            'path' => Yii::t('app', 'Path'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageJs()
    {
        return $this->hasMany(PageJs::className(), ['js_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplateJs()
    {
        return $this->hasMany(TemplateJs::className(), ['js_id' => 'id']);
    }

    public function eventBeforeDelete()
    {
        if ($this->path && strpos($this->path, 'http') !== 0 && file_exists(Yii::getAlias('@webroot').$this->path)) {
            unlink(Yii::getAlias('@webroot').$this->path);
        }
    }
}
