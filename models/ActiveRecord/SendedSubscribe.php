<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "sended_subscribe".
 *
 * @property int $id
 * @property int $subscribe_id
 * @property int $subscriber_id
 * @property string $send_errors
 *
 * @property Subscribe $subscribe
 * @property Subscriber $subscriber
 */
class SendedSubscribe extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subscribe_id', 'subscriber_id'], 'required'],
            [['subscribe_id', 'subscriber_id'], 'integer'],
            [['send_errors'], 'string'],
            [['subscribe_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subscribe::className(), 'targetAttribute' => ['subscribe_id' => 'id']],
            [['subscriber_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subscriber::className(), 'targetAttribute' => ['subscriber_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subscribe_id' => Yii::t('app', 'Subscribe ID'),
            'subscriber_id' => Yii::t('app', 'Subscriber ID'),
            'send_errors' => Yii::t('app', 'Send Errors'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscribe()
    {
        return $this->hasOne(Subscribe::className(), ['id' => 'subscribe_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriber()
    {
        return $this->hasOne(Subscriber::className(), ['id' => 'subscriber_id']);
    }
}
