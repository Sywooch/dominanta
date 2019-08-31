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
            [['banner_text'], 'string'],
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
            'banner_text' => Yii::t('app', 'Banner text'),
        ];
    }
}
