<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "mail_attachment".
 *
 * @property int $id
 * @property int $mail_id
 * @property string $path
 * @property int $embed
 *
 * @property Mail $mail
 */
class MailAttachment extends AbstractModel
{
    public static $entityName = 'Mail attachment';

    public static $entitiesName = 'Mail attachments';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mail_id', 'embed'], 'integer'],
            [['path'], 'string', 'max' => 255],
            [['mail_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mail::className(), 'targetAttribute' => ['mail_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mail_id' => Yii::t('app', 'Mail ID'),
            'path' => Yii::t('app', 'Path'),
            'embed' => Yii::t('app', 'Embed'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMail()
    {
        return $this->hasOne(Mail::className(), ['id' => 'mail_id']);
    }
}
