<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "mail".
 *
 * @property int $id
 * @property int $status
 * @property int $mail_setting_id
 * @property string $create_time
 * @property string $send_time
 * @property string $to_email
 * @property string $subject
 * @property string $body_text
 * @property string $body_html
 * @property string $send_errors
 *
 * @property MailSetting $mailSetting
 * @property MailAttachment[] $mailAttachments
 */
class Mail extends AbstractModel
{
    public static $entityName = 'Mail';

    public static $entitiesName = 'Mails';

    const STATUS_SENDED = 3;
    const STATUS_ERROR  = 2;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'mail_setting_id'], 'integer'],
            [['create_time', 'send_time'], 'safe'],
            [['body_text', 'body_html', 'send_errors'], 'string'],
            [['to_email', 'subject'], 'string', 'max' => 255],
            [['mail_setting_id'], 'exist', 'skipOnError' => true, 'targetClass' => MailSetting::className(), 'targetAttribute' => ['mail_setting_id' => 'id']],
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
            'mail_setting_id' => Yii::t('app', 'Mail Setting ID'),
            'create_time' => Yii::t('app', 'Create time'),
            'send_time' => Yii::t('app', 'Send time'),
            'to_email' => Yii::t('app', 'To email'),
            'subject' => Yii::t('app', 'Subject'),
            'body_text' => Yii::t('app', 'Body text'),
            'body_html' => Yii::t('app', 'Body html'),
            'send_errors' => Yii::t('app', 'Send errors'),
        ];
    }

    public function statusIcons()
    {
        return [
            self::STATUS_DELETED  => 'trash',
            self::STATUS_INACTIVE => 'clock-o',
            self::STATUS_ACTIVE   => 'paper-plane',
            self::STATUS_SENDED   => 'check',
            self::STATUS_ERROR    => 'ban',
        ];
    }

    public function statusTexts()
    {
        return [
            self::STATUS_DELETED  => Yii::t('app', 'Deleted'),
            self::STATUS_INACTIVE => Yii::t('app', 'Waiting'),
            self::STATUS_ACTIVE   => Yii::t('app', 'Sending'),
            self::STATUS_SENDED   => Yii::t('app', 'Sended'),
            self::STATUS_ERROR    => Yii::t('app', 'Error'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailSetting()
    {
        return $this->hasOne(MailSetting::className(), ['id' => 'mail_setting_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAttachments()
    {
        return $this->hasMany(MailAttachment::className(), ['mail_id' => 'id']);
    }


    public function eventBeforeInsert()
    {
        $mail_setting = MailSetting::findOne(['status' => MailSetting::STATUS_ACTIVE]);
        $this->create_time = $this->dbTime;
        $this->mail_setting_id = $mail_setting ? $mail_setting->id : NULL;
    }
}
