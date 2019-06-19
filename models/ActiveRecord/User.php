<?php

namespace app\models\ActiveRecord;

use Yii;
use app\models\ActiveRecord\AbstractModel;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $status
 * @property string $email
 * @property string $password
 * @property string $access_token
 * @property int $role_id
 * @property string $create_time
 * @property string $last_activity
 * @property string $language
 * @property string $timeZone
 * @property string $realname
 * @property string $notify
 *
 * @property Role $role
 */
class User extends AbstractModel implements IdentityInterface
{
    public static $entityName = 'User';

    public static $entitiesName = 'Users';

    public $remember_me, $_user, $repassword;

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';
    const SCENARIO_PASSWORD = 'password';
    const SCENARIO_SETTINGS = 'settings';
    const SCENARIO_SEARCH = 'search';

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = ['email', 'password', 'remember_me'];
        $scenarios[self::SCENARIO_ADD] = ['email', 'realname', 'phone', 'role_id', 'password', 'repassword'];
        $scenarios[self::SCENARIO_PASSWORD] = ['password', 'repassword'];
        $scenarios[self::SCENARIO_EDIT] = ['email', 'realname', 'phone', 'role_id'];
        $scenarios[self::SCENARIO_SETTINGS] = ['timeZone', 'language', 'notify'];
        $scenarios[self::SCENARIO_SEARCH] = ['email', 'realname', 'role_id', 'create_time', 'last_activity', 'language', 'timeZone', 'phone'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'trim'],
            [['status', 'role_id'], 'integer'],
            [['email', 'password'], 'required', 'except' => self::SCENARIO_SEARCH],
            ['remember_me', 'boolean', 'on' => self::SCENARIO_LOGIN],
            ['password', 'formValidatePassword', 'on' => self::SCENARIO_LOGIN],
            ['email', 'email', 'on' => self::SCENARIO_SEARCH],
            ['email', 'formValidateEmail', 'on' => [self::SCENARIO_ADD, self::SCENARIO_EDIT]],
            ['role_id', 'required', 'on' => [self::SCENARIO_ADD, self::SCENARIO_EDIT]],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'on' => [self::SCENARIO_ADD, self::SCENARIO_EDIT]],
            [['create_time', 'last_activity'], 'safe'],
            [['email', 'password', 'access_token', 'language', 'timeZone', 'realname', 'phone'], 'string', 'max' => 255],
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
            'status' => Yii::t('app', 'Status'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'access_token' => Yii::t('app', 'Access Token'),
            'role_id' => Yii::t('app', 'Role'),
            'create_time' => Yii::t('app', 'Create time'),
            'last_activity' => Yii::t('app', 'Last activity'),
            'language' => Yii::t('app', 'Language'),
            'timeZone' => Yii::t('app', 'Time zone'),
            'realname' => Yii::t('app', 'User name'),
            'phone' => Yii::t('app', 'Phone'),
            'remember_me' => Yii::t('app', 'Remember me'),
            'repassword'  => Yii::t('app', 'Retype password'),
            'notify'  => Yii::t('app', 'Notify'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    public function eventBeforeInsert()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
        $this->create_time = $this->dbTime;
    }

    public function setActivity()
    {
        $this->last_activity = $this->dbTime;
        $this->save();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by free params
     *
     * @param array $params
     * @return static|null
     */
    public static function findUserByParam(array $params)
    {
        return static::findOne($params);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->access_token;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Create password
     *
     * @param string $password
     * @param bool $hashed
     * @return void
     */
    public function setPassword($password, $hashed = false)
    {
        $this->password = $hashed ? $password : Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function formValidatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_user = self::findByUsername($this->email);

            if (!$this->_user || !$this->_user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect email or password'));
            }
        }
    }

    /**
     * Validate unique email in DB
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function formValidateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $query = self::find()->where(['email' => $this->email]);

            if ($this->id) {
                $query = $query->andWhere(['!=', 'id', $this->id]);
            }

            if ($query->count()) {
                $this->addError($attribute, Yii::t('app', 'A user with such an email is already registered'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->_user, $this->remember_me ? 3600 * 24 * 30 : 0);
        }

        return false;
    }
}
