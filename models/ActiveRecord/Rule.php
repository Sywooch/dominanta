<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "rule".
 *
 * @property int $id
 * @property int $role_id
 * @property string $model
 * @property int $is_view
 * @property int $is_add
 * @property int $is_edit
 * @property int $is_delete
 *
 * @property Role $role
 */
class Rule extends AbstractModel
{
    public static $entityName = 'Rule';

    public static $entitiesName = 'Rules';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'is_view', 'is_add', 'is_edit', 'is_delete'], 'integer'],
            [['model'], 'required'],
            [['model'], 'string', 'max' => 255],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'role_id' => Yii::t('app', 'Role ID'),
            'model' => Yii::t('app', 'Model'),
            'is_view' => Yii::t('app', 'Is View'),
            'is_add' => Yii::t('app', 'Is Add'),
            'is_edit' => Yii::t('app', 'Is Edit'),
            'is_delete' => Yii::t('app', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }
}
