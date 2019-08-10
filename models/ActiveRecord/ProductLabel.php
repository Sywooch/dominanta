<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "product_label".
 *
 * @property int $id
 * @property int $status
 * @property string $label
 * @property string $font_color
 * @property string $bg_color
 * @property string $link
 * @property string $widget
 *
 * @property ProductLabels[] $productLabels
 */
class ProductLabel extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['label'], 'required'],
            [['label', 'font_color', 'bg_color', 'link', 'widget'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'label' => Yii::t('app', 'Label'),
            'font_color' => Yii::t('app', 'Font Color'),
            'bg_color' => Yii::t('app', 'Bg Color'),
            'link' => Yii::t('app', 'Link'),
            'widget' => Yii::t('app', 'Widget'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductLabels()
    {
        return $this->hasMany(ProductLabels::className(), ['label_id' => 'id']);
    }
}
