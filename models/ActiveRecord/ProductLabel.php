<?php

namespace app\models\ActiveRecord;

use Yii;
use yii\helpers\Html;
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

    public function getContent()
    {
        if (!$this->status) {
            return '';
        }

        $styles = [];

        if ($this->bg_color) {
            $styles[] = 'background:'.$this->bg_color;
        }

        if ($this->font_color) {
            $styles[] = 'color:'.$this->font_color;
        }

        return $this->link || $this->widget ?
               Html::a(Html::encode($this->label), ($this->link ? $this->link : '/shop/search?tag='.$this->widget), ['style' => implode(';', $styles), 'class' => 'product_label'])
               :
               '<span class="product_label"'.($styles ? ' style="'.implode(';', $styles).'"' : '').'>'.
               Html::encode($this->label).
               '</span>';

    }
}
