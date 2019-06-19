<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "variable".
 *
 * @property int $id
 * @property string $name
 * @property string $value
 */
class Variable extends AbstractModel
{
    public static $entityName = 'Variable';

    public static $entitiesName = 'Variables';

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_FORM] = ['name', 'value'];
        $scenarios[self::SCENARIO_SEARCH] = ['name', 'value'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required', 'on' => self::SCENARIO_FORM],
            [['value'], 'string'],
            [['name'], 'string', 'max' => 255],
            ['name', 'unique', 'targetClass' => self::classname(), 'targetAttribute' => 'name'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Variable name'),
            'value' => Yii::t('app', 'Variable value'),
        ];
    }
}
