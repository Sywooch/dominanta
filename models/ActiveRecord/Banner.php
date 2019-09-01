<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "banner".
 *
 * @property int $id
 * @property int $status
 * @property string $banner_text
 */
class Banner extends AbstractModel
{
    public static $entityName = 'Banner';

    public static $entitiesName = 'Banners';

    public $photo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['status'], 'required', 'on' => self::SCENARIO_FORM],
            [['banner_text', 'photo'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = [];

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
            'banner_text' => Yii::t('app', 'Banner text'),
        ];
    }
}
