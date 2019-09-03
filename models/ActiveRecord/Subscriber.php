<?php

namespace app\models\ActiveRecord;

use Yii;
use yii\helpers\Url;
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
    public static $entityName = 'Subscriber';

    public static $entitiesName = 'Subscribers';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            ['email', 'email'],
            ['email', 'unique', 'message' => 'Ваш адрес уже был добавлен в рассылки'],
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

    public function eventAfterInsert()
    {
        $link = Url::to(['subscribe', 'token' => $this->hash], true);

        Mail::createAndSave([
            'to_email'  => $this->email,
            'subject'   => 'Подтверждение для рассылки новостей сайта '.ucfirst($_SERVER['SERVER_NAME']),
            'body_text' => $link,
            'body_html' => $link,
        ], 'subscribe');
    }
}
