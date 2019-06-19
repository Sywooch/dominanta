<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property int $status
 * @property int $pid
 * @property string $role_name
 * @property int $is_admin
 *
 * @property Role $p
 * @property Role[] $roles
 */
class Role extends AbstractModel
{
    public static $entityName = 'Role';

    public static $entitiesName = 'Roles';

    /** @var $subroles */
    public $subroles;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'pid', 'is_admin'], 'integer'],
            [['role_name'], 'required'],
            [['role_name'], 'string', 'max' => 255],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['pid' => 'id']],
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
            'pid' => Yii::t('app', 'Parent role'),
            'role_name' => Yii::t('app', 'Role name'),
            'is_admin' => Yii::t('app', 'Administrator rules'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'pid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['pid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRules()
    {
        return $this->hasMany(Rule::className(), ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['role_id' => 'id']);
    }

    public function eventBeforeInsert()
    {
        $this->status = self::STATUS_ACTIVE;
    }
}
