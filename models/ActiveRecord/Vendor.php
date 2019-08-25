<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "vendor".
 *
 * @property int $id
 * @property string $title
 *
 * @property Product[] $products
 */
class Vendor extends AbstractModel
{
    public static $entityName = 'Vendor';

    public static $entitiesName = 'Vendors';

    public $photo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required', 'on' => self::SCENARIO_FORM],
            [['title', 'photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = ['title'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['vendor_id' => 'id']);
    }

    public function eventBeforeDelete()
    {
        Product::updateAll(['vendor_id' => NULL], ['vendor_id' => $this->id]);
    }
}
