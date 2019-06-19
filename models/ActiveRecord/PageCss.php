<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord;

/**
 * This is the model class for table "page_css".
 *
 * @property int $id
 * @property int $page_id
 * @property int $css_id
 * @property string $position
 * @property int $s_order
 *
 * @property Css $css
 * @property Page $page
 */
class PageCss extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'css_id', 's_order'], 'integer'],
            [['position'], 'string', 'max' => 255],
            [['css_id'], 'exist', 'skipOnError' => true, 'targetClass' => Css::className(), 'targetAttribute' => ['css_id' => 'id']],
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
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }
}
