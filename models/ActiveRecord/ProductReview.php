<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

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
 *
 * @property User $approver0
 * @property Product $product
 * @property User $user
 */
class ProductReview extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'product_id', 'user_id', 'approver'], 'integer'],
            [['add_time', 'approved'], 'safe'],
            [['review_text'], 'string'],
            [['reviewer'], 'string', 'max' => 255],
            [['approver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['approver' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'review_text' => Yii::t('app', 'Review Text'),
            'approver' => Yii::t('app', 'Approver'),
            'approved' => Yii::t('app', 'Approved'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover0()
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
}
