<?php

namespace app\models\ActiveRecord;

use Yii;
use yii\helpers\Html;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "shop_order".
 *
 * @property int $id
 * @property int $status
 * @property string $add_time
 * @property string $delivery_date
 * @property string $issue_date
 * @property int $user_id
 * @property string $fio
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property int $payment_type
 * @property int $delivery_type
 * @property string $delivery_price
 * @property string $product_discount
 * @property string $delivery_discount
 * @property string $order_comment
 *
 * @property User $user
 * @property ShopOrderPosition[] $shopOrderPositions
 * @property ShopPayment[] $shopPayments
 */
class ShopOrder extends AbstractModel
{
    public static $entityName = 'Shop order';

    public static $entitiesName = 'Shop orders';

    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';

    const STATUS_WAIT_PAYMENT = 2;
    const STATUS_READY = 3;
    const STATUS_COMPLETED = 4;

    public $statuses = [
        -1 => 'Удалён',
        0  => 'Отменён',
        1  => 'Обрабатывается',
        2  => 'Ожидает оплаты',
        3  => 'Готов к выдаче',
        4  => 'Завёршён',
    ];

    public $payment_types = [
        'Безналичная оплата',
        'Наличные',
    ];

    public $delivery_types = [
        'Самовывоз',
        'Доставка курьером',
    ];

    public static $notify = 'Уведомление о новых заказах';

    public $agreement;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADD] = ['email', 'fio', 'phone', 'address', 'payment_type', 'delivery_type', 'order_comment', 'agreement'];
        $scenarios[self::SCENARIO_EDIT] = ['status', 'email', 'fio', 'phone', 'address', 'payment_type', 'delivery_type', 'order_comment'];
        $scenarios[self::SCENARIO_SEARCH] = ['email', 'fio', 'phone', 'address'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'user_id', 'payment_type', 'delivery_type'], 'integer'],
            [['add_time', 'delivery_date', 'issue_date'], 'safe'],
            ['email', 'email'],
            [['fio', 'phone', 'email', 'address'], 'required', 'on' => [self::SCENARIO_ADD, self::SCENARIO_EDIT]],
            [['address', 'order_comment'], 'string'],
            [['delivery_price', 'product_discount', 'delivery_discount'], 'number'],
            [['fio', 'phone', 'email'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['agreement'], 'required', 'message' => 'Необходимо принять соглашение', 'on' => [self::SCENARIO_ADD]],
            ['agreement', 'integer'],
            ['agreement', 'compare', 'compareValue' => 1, 'message' => 'Необходимо принять соглашение', 'on' => [self::SCENARIO_ADD]],
            ['phone', 'filter', 'filter' => function ($value) {
                return '+'.str_replace(['+', '(', ')', '-', ' '], '', $value);
            }, 'on' => [self::SCENARIO_ADD]],
            ['phone', 'match', 'pattern' => '/^\+7\d{10,10}$/i', 'on' => [self::SCENARIO_ADD], 'enableClientValidation' => false],
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
            'add_time' => Yii::t('app', 'Add time'),
            'delivery_date' => Yii::t('app', 'Delivery date'),
            'issue_date' => Yii::t('app', 'Issue date'),
            'user_id' => Yii::t('app', 'User'),
            'fio' => Yii::t('app', 'Fio'),
            'phone' => Yii::t('app', 'Your phone'),
            'email' => Yii::t('app', 'Your email'),
            'address' => Yii::t('app', 'Address'),
            'payment_type' => Yii::t('app', 'Payment type'),
            'delivery_type' => Yii::t('app', 'Delivery type'),
            'delivery_price' => Yii::t('app', 'Delivery price'),
            'product_discount' => Yii::t('app', 'Product discount'),
            'delivery_discount' => Yii::t('app', 'Delivery discount'),
            'order_comment' => Yii::t('app', 'Order comment'),
            'agreement' => Yii::t('app', 'Agreement')
        ];
    }

    public function eventBeforeInsert()
    {
        $this->add_time = $this->dbTime;
        $this->status = self::STATUS_ACTIVE;
    }

    public function eventAfterInsert()
    {

    }

    public function sendEmails()
    {
        if ($this->status == self::STATUS_ACTIVE) {
            $products_list = '<table style="border: 1px solid #d7d7d7; border-collapse: collapse; width: 100%">';
            $amount = 0;

            foreach ($this->shopOrderPosition AS $position) {
                $img = ProductPhoto::find()->where(['product_id' => $position->product_id])
                                          ->limit(1)
                                          ->orderBy(['photo_order' => SORT_ASC])
                                          ->one();

                if ($img) {
                    $photo = Html::img(Yii::$app->site_options->scheme.'://'.$_SERVER['SERVER_NAME'].str_replace(Yii::getAlias('@webroot'), '', $position->product->getPreview($img->photoPath, 142, 142)));
                } else {
                    $photo = '';
                }

                $products_list .= '<tr><td style="border: 1px solid #d7d7d7">'.Html::encode($position->product->product_name).'<br />'.$photo.'</td>'.
                                  '<td style="border: 1px solid #d7d7d7">x '.$position->quantity.'</td>'.
                                  '<td style="border: 1px solid #d7d7d7">'.Yii::$app->formatter->asDecimal($position->price, 2).' руб.</td></tr>';

                $amount += $position->price;
            }


            $products_list .= '<tr><td style="border: 1px solid #d7d7d7; font-weight: bold; text-align: right" colspan="3">Итого: '.Yii::$app->formatter->asDecimal($amount, 2).' руб.</td></tr></table>';

            Mail::createAndSave([
                'to_email'  => $this->email,
                'subject'   => 'Новый заказ на сайте '.ucfirst($_SERVER['SERVER_NAME']),
                'body_text' => $this->id,
                'body_html' => $this->id.$products_list,
            ], 'order_client');


            $notify_users = $this->getUsersForNotify();

            foreach ($notify_users AS $notify_user) {
                Mail::createAndSave([
                    'to_email'  => $notify_user->email,
                    'subject'   => 'Новый заказ на сайте '.ucfirst($_SERVER['SERVER_NAME']),
                    'body_text' => $this->id,
                    'body_html' => $this->id.$products_list,
                ], 'order_admin');
            }
        }
    }

    public function sendPaymentNotify() {
        $notify_users = $this->getUsersForNotify();

        foreach ($notify_users AS $notify_user) {
            Mail::createAndSave([
                'to_email'  => $notify_user->email,
                'subject'   => 'Поступила оплата по заказу №'.$this->id.' на сайте '.ucfirst($_SERVER['SERVER_NAME']),
                'body_text' => $this->id,
                'body_html' => $this->id,
            ], 'order_admin_payment');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopOrderPosition()
    {
        return $this->hasMany(ShopOrderPosition::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopPayments()
    {
        return $this->hasMany(ShopPayment::className(), ['order_id' => 'id']);
    }
}
