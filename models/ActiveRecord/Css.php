<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "css".
 *
 * @property int $id
 * @property string $css_name
 * @property string $path
 * @property string $content
 *
 * @property PageCss[] $pageCsses
 * @property TemplateCss[] $templateCsses
 */
class Css extends AbstractModel
{
    public static $entityName = 'CSS';

    public static $entitiesName = 'CSS';

    public $upload;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['css_name'], 'required', 'on' => self::SCENARIO_FORM],
            [['css_name'], 'unique', 'on' => self::SCENARIO_FORM],
            [['content'], 'string'],
            [['css_name', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = ['css_name'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'css_name' => Yii::t('app', 'CSS Name'),
            'path' => Yii::t('app', 'Path'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageCss()
    {
        return $this->hasMany(PageCss::className(), ['css_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplateCss()
    {
        return $this->hasMany(TemplateCss::className(), ['css_id' => 'id']);
    }

    public function eventBeforeDelete()
    {
        if ($this->path && strpos($this->path, 'http') !== 0 && file_exists(Yii::getAlias('@webroot').$this->path)) {
            unlink(Yii::getAlias('@webroot').$this->path);
        }
    }
}
