<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord;

/**
 * This is the model class for table "template_css".
 *
 * @property int $id
 * @property int $template_id
 * @property int $css_id
 * @property string $position
 * @property int $s_order
 *
 * @property Css $css
 * @property Template $template
 */
class TemplateCss extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template_id', 'css_id', 's_order'], 'integer'],
            [['position'], 'string', 'max' => 255],
            [['css_id'], 'exist', 'skipOnError' => true, 'targetClass' => Css::className(), 'targetAttribute' => ['css_id' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => Template::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'template_id' => Yii::t('app', 'Template ID'),
            'css_id' => Yii::t('app', 'Css ID'),
            'position' => Yii::t('app', 'Position'),
            's_order' => Yii::t('app', 'S Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCss()
    {
        return $this->hasOne(Css::className(), ['id' => 'css_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }
}
