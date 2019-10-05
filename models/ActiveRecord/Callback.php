<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;

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

    public $reCaptcha;

    public static $notify = 'Уведомление о заказе обратного звонка';

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
            [['reCaptcha'], ReCaptchaValidator2::className(),
              'uncheckedMessage' => 'Ошибка проверки подлинности пользователя. Обновите страницу и попробуйте ещё раз.',
            ],
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

    public function eventAfterInsert()
    {
        $notify_users = $this->getUsersForNotify();

        foreach ($notify_users AS $notify_user) {
            Mail::createAndSave([
                'to_email'  => $notify_user->email,
                'subject'   => 'Уведомление о заказе обратного звонка на сайте '.ucfirst($_SERVER['SERVER_NAME']),
                'body_text' => 'Заказ обратного звонка на сайте '.$_SERVER['SERVER_NAME'].'.'.PHP_EOL.PHP_EOL
                                .'ФИО: '.$this->fio.PHP_EOL
                                .'Телефон: '.$this->phone.PHP_EOL,
                'body_html' => 'Заказ обратного звонка на сайте '.$_SERVER['SERVER_NAME'].'.<br /><br />'
                                .'ФИО: '.$this->fio.'<br />'
                                .'Телефон: '.$this->phone.'<br />',
            ]);
        }
    }
}
