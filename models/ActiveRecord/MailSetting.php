<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "mail_setting".
 *
 * @property int $id
 * @property int $status
 * @property string $service_name
 * @property string $smtp_host
 * @property int $smtp_port
 * @property string $smtp_user
 * @property string $smtp_password
 * @property string $smtp_secure
 * @property string $from_email
 * @property string $from_name
 * @property string $reply_to
 *
 * @property Mail[] $mails
 */
class MailSetting extends AbstractModel
{
    public static $entityName = 'Mail setting';

    public static $entitiesName = 'Mail settings';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'smtp_port'], 'integer'],
            [['service_name', 'smtp_host', 'smtp_user', 'smtp_password', 'from_email', 'from_name', 'reply_to'], 'string', 'max' => 255],
            [['service_name', 'smtp_host', 'smtp_port'], 'required', 'on' => self::SCENARIO_FORM],
            [['smtp_secure'], 'string', 'max' => 5],
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
            'service_name' => Yii::t('app', 'Service name'),
            'smtp_host' => Yii::t('app', 'SMTP host'),
            'smtp_port' => Yii::t('app', 'SMTP port'),
            'smtp_user' => Yii::t('app', 'SMTP user'),
            'smtp_password' => Yii::t('app', 'SMTP password'),
            'smtp_secure' => Yii::t('app', 'SMTP secure'),
            'from_email' => Yii::t('app', 'From email'),
            'from_name' => Yii::t('app', 'From name'),
            'reply_to' => Yii::t('app', 'Reply to'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMails()
    {
        return $this->hasMany(Mail::className(), ['mail_setting_id' => 'id']);
    }

    public function eventBeforeInsert()
    {
        if (!self::find()->count()) {
            $this->status = self::STATUS_ACTIVE;
        }
    }
}
