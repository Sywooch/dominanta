<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property string $add_time
 * @property string $f_name
 * @property string $phone
 * @property string $email
 * @property string $message
 */
class Feedback extends AbstractModel
{
    public static $entityName = 'Feedback';

    public static $entitiesName = 'Feedback';

    public static $notify = 'Уведомление формы обратной связи';

    public $reCaptcha;

    public $agreement;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['add_time'], 'safe'],
            [['message'], 'string'],
            [['f_name', 'phone', 'email'], 'string', 'max' => 255],
            [['f_name', 'phone', 'email', 'message'], 'required'],
            [['reCaptcha'], ReCaptchaValidator2::className(),
              'uncheckedMessage' => 'Подтвердите, что вы не робот.',
              'message' => 'Ошибка проверки подлинности пользователя. Обновите страницу и попробуйте ещё раз.',
            ],
            [['agreement'], 'required', 'message' => 'Необходимо принять соглашение'],
            ['agreement', 'integer'],
            ['agreement', 'compare', 'compareValue' => 1, 'message' => 'Необходимо принять соглашение'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'add_time' => Yii::t('app', 'Add time'),
            'f_name' => Yii::t('app', 'Your name'),
            'phone' => Yii::t('app', 'Your phone'),
            'email' => Yii::t('app', 'Your email'),
            'message' => Yii::t('app', 'Message'),
            'agreement' => Yii::t('app', 'Agreement'),
        ];
    }

    public function eventBeforeInsert()
    {
        $this->add_time = $this->dbTime;
    }

    public function eventAfterInsert()
    {
        $notify_users = $this->getUsersForNotify();

        foreach ($notify_users AS $notify_user) {
            Mail::createAndSave([
                'to_email'  => $notify_user->email,
                'subject'   => 'Уведомление о новом сообщении на сайте '.ucfirst($_SERVER['SERVER_NAME']),
                'body_text' => 'Новое сообщение на сайте '.$_SERVER['SERVER_NAME'].'.'.PHP_EOL.PHP_EOL
                                .'Имя: '.$this->f_name.PHP_EOL
                                .'Телефон: '.$this->phone.PHP_EOL
                                .'Email: '.$this->email.PHP_EOL
                                .'Сообщение: '.$this->message,
                'body_html' => 'Новое сообщение на сайте '.$_SERVER['SERVER_NAME'].'.<br /><br />'
                                .'Имя: '.$this->f_name.'<br />'
                                .'Телефон: '.$this->phone.'<br />'
                                .'Email: '.$this->email.'<br />'
                                .'Сообщение: '.$this->message,
            ]);
        }
    }
}
