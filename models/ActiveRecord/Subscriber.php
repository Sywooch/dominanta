<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "subscriber".
 *
 * @property int $id
 * @property int $status
 * @property string $email
 * @property string $hash
 */
class Subscriber extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            ['email', 'email'],
            ['email', 'required'],
            [['email', 'hash'], 'string', 'max' => 255],
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
            'email' => Yii::t('app', 'Email'),
            'hash' => Yii::t('app', 'Hash'),
        ];
    }

    public function eventBeforeInsert()
    {
        $this->hash = Yii::$app->security->generateRandomString();
    }
}
