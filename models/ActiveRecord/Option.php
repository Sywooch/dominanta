<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "option".
 *
 * @property int $id
 * @property string $option
 * @property string $option_name
 * @property string $option_value
 */
class Option extends AbstractModel
{
    public static $entityName = 'Option';

    public static $entitiesName = 'Options';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option', 'option_name'], 'string', 'max' => 255],
            [['option_value'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'option' => Yii::t('app', 'Option'),
            'option_name' => Yii::t('app', 'Option Name'),
            'option_value' => Yii::t('app', 'Option Value'),
        ];
    }

    public static function getByKey($key)
    {
        $option = self::findOne(['option' => $key]);
        return $option ? $option->option_value : false;
    }
}
