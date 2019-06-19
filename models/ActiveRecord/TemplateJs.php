<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord;

/**
 * This is the model class for table "template_js".
 *
 * @property int $id
 * @property int $template_id
 * @property int $js_id
 * @property string $position
 * @property int $s_order
 *
 * @property Js $js
 * @property Template $template
 */
class TemplateJs extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template_id', 'js_id', 's_order'], 'integer'],
            [['position'], 'string', 'max' => 255],
            [['js_id'], 'exist', 'skipOnError' => true, 'targetClass' => Js::className(), 'targetAttribute' => ['js_id' => 'id']],
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
            'js_id' => Yii::t('app', 'Js ID'),
            'position' => Yii::t('app', 'Position'),
            's_order' => Yii::t('app', 'S Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJs()
    {
        return $this->hasOne(Js::className(), ['id' => 'js_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }
}
