<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "callback".
 *
 * @property int $id
 * @property int $status
 * @property string $add_time
 * @property string $fio
 * @property string $phone
 */
class Callback extends AbstractModel
{
    public static $entityName = 'Callback';

    public static $entitiesName = 'Callbacks';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['add_time'], 'safe'],
            [['fio', 'phone'], 'required'],
            [['fio', 'phone'], 'string', 'max' => 255],
            ['phone', 'filter', 'filter' => function ($value) {
                return '+'.str_replace(['+', '(', ')', '-', ' '], '', $value);
            }],
            ['phone', 'match', 'pattern' => '/^\+7\d{10,10}$/i', 'enableClientValidation' => false],
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
            'add_time' => Yii::t('app', 'Add Time'),
            'fio' => Yii::t('app', 'Fio'),
            'phone' => Yii::t('app', 'Phone'),
        ];
    }

    public function eventBeforeInsert()
    {
        $this->add_time = $this->dbTime;
    }
}
