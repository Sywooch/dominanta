<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "subscribe".
 *
 * @property int $id
 * @property int $status
 * @property string $mail_subject
 * @property string $mail_text
 *
 * @property SendedSubscribe[] $sendedSubscribes
 */
class Subscribe extends AbstractModel
{
    public static $entityName = 'Subscribe';

    public static $entitiesName = 'Subscribes';

    const STATUS_SENDING = 2;
    const STATUS_SENDED  = 3;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['mail_subject', 'mail_text'], 'required', 'on' => self::SCENARIO_FORM],
            [['mail_text'], 'string'],
            [['mail_subject'], 'string', 'max' => 255],
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
            'mail_subject' => Yii::t('app', 'Mail subject'),
            'mail_text' => Yii::t('app', 'Mail text'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSendedSubscribes()
    {
        return $this->hasMany(SendedSubscribe::className(), ['subscribe_id' => 'id']);
    }

    public function statusIcons()
    {
        return [
            self::STATUS_DELETED  => 'trash',
            self::STATUS_INACTIVE => 'edit',
            self::STATUS_ACTIVE   => 'clock-o',
            self::STATUS_SENDING  => 'paper-plane',
            self::STATUS_SENDED    => 'check',
        ];
    }

    public function statusTexts()
    {
        return [
            self::STATUS_DELETED  => Yii::t('app', 'Deleted'),
            self::STATUS_INACTIVE => Yii::t('app', 'Draft'),
            self::STATUS_ACTIVE   => Yii::t('app', 'Active'),
            self::STATUS_SENDING   => Yii::t('app', 'Sending'),
            self::STATUS_SENDED    => Yii::t('app', 'Sended'),
        ];
    }

    public function getStatusText()
    {
        return $this->statusTexts()[$this->status];
    }

    function getStatusIcon()
    {
        return $this->statusIcons()[$this->status];
    }
}
