<?php

namespace app\models\ActiveRecord;

use Yii;
use yii\helpers\Html;
use app\models\ActiveRecord\AbstractModel;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;

/**
 * This is the model class for table "product_review".
 *
 * @property int $id
 * @property int $status
 * @property int $product_id
 * @property string $add_time
 * @property int $user_id
 * @property string $reviewer
 * @property string $review_text
 * @property int $approver
 * @property string $approved
 * @property int $rate
 *
 * @property User $approver
 * @property Product $product
 * @property User $user
 */
class ProductReview extends AbstractModel
{
    public static $entityName = 'Review';

    public static $entitiesName = 'Reviews';

    public $reCaptcha;

    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';

    public static $notify = 'Уведомление о новых отзывах';

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADD] = ['product_id', 'reviewer', 'review_text', 'rate', 'reCaptcha'];
        $scenarios[self::SCENARIO_EDIT] = ['reviewer', 'review_text', 'rate'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'product_id', 'user_id', 'approver', 'rate'], 'integer'],
            [['product_id', 'reviewer', 'review_text', 'rate'], 'required', 'on' => self::SCENARIO_ADD],
            [['add_time', 'approved'], 'safe'],
            [['review_text'], 'string'],
            [['reviewer'], 'string', 'max' => 255],
            [['approver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['approver' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'product_id' => Yii::t('app', 'Product ID'),
            'add_time' => Yii::t('app', 'Add Time'),
            'user_id' => Yii::t('app', 'User ID'),
            'reviewer' => Yii::t('app', 'Reviewer'),
            'review_text' => Yii::t('app', 'Review text'),
            'approver' => Yii::t('app', 'Approver'),
            'approved' => Yii::t('app', 'Approved'),
            'rate' => Yii::t('app', 'Rate'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApproverUser()
    {
        return $this->hasOne(User::className(), ['id' => 'approver']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
                'subject'   => 'Новый отзыв на сайте '.ucfirst($_SERVER['SERVER_NAME']),
                'body_text' => 'Добавлен новый отзыв к товару: '.$this->product->product_name,
                'body_html' => 'Добавлен новый отзыв к товару: '.Html::encode($this->product->product_name),
            ]);
        }
    }
}
