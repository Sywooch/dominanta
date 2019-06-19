<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord;

/**
 * This is the model class for table "page_js".
 *
 * @property int $id
 * @property int $page_id
 * @property int $js_id
 * @property string $position
 * @property int $s_order
 *
 * @property Js $js
 * @property Page $page
 */
class PageJs extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'js_id', 's_order'], 'integer'],
            [['position'], 'string', 'max' => 255],
            [['js_id'], 'exist', 'skipOnError' => true, 'targetClass' => Js::className(), 'targetAttribute' => ['js_id' => 'id']],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'page_id' => Yii::t('app', 'Page ID'),
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
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }
}
