<?php

namespace app\models\ActiveRecord;

use Yii;
use yii\web\IdentityInterface;
use yii\helpers\Html;
use yii\helpers\Url;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use app\components\helpers\ModelsHelper;
use app\models\ActiveRecord\AbstractModel;

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
 * @property string $notify
 *
 * @property Role $role
 */
class User extends AbstractModel implements IdentityInterface
{
    public static $entityName = 'User';

    public static $entitiesName = 'Users';

    public $remember_me, $_user, $repassword, $agree, $email_or_phone, $old_password, $new_password;

    public $reCaptcha;

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';
    const SCENARIO_PASSWORD = 'password';
    const SCENARIO_SETTINGS = 'settings';
    const SCENARIO_SEARCH = 'search';
    const SCENARIO_REG = 'reg';
    const SCENARIO_RESTORE = 'restore';
    const SCENARIO_ACCOUNT = 'account';
    const SCENARIO_ACCOUNT_PASSWORD = 'account_password';

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
        $scenarios[self::SCENARIO_REG] = ['email', 'realname', 'phone', 'password', 'repassword', 'agree', 'reCaptcha'];
        $scenarios[self::SCENARIO_RESTORE] = ['email_or_phone', 'reCaptcha'];
        $scenarios[self::SCENARIO_ACCOUNT] = ['email', 'realname', 'phone'];
        $scenarios[self::SCENARIO_ACCOUNT_PASSWORD] = ['new_password', 'old_password'];

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
            ['password', 'formValidatePassword', 'on' => [self::SCENARIO_LOGIN]],
            [['old_password', 'new_password'], 'required', 'on' => [self::SCENARIO_ACCOUNT_PASSWORD]],
            ['old_password', 'formValidateOnlyPassword', 'on' => [self::SCENARIO_ACCOUNT_PASSWORD]],
            ['password', 'string', 'min'=> 6, 'on' => [self::SCENARIO_ADD, self::SCENARIO_PASSWORD, self::SCENARIO_REG, self::SCENARIO_ACCOUNT_PASSWORD]],
            ['new_password', 'string', 'min'=> 6, 'on' => [self::SCENARIO_ACCOUNT_PASSWORD]],
            ['email', 'email', 'on' => self::SCENARIO_SEARCH],
            ['email', 'formValidateEmail', 'on' => [self::SCENARIO_ADD, self::SCENARIO_EDIT, self::SCENARIO_REG, self::SCENARIO_ACCOUNT]],
            ['role_id', 'required', 'on' => [self::SCENARIO_ADD, self::SCENARIO_EDIT]],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'on' => [self::SCENARIO_ADD, self::SCENARIO_PASSWORD, self::SCENARIO_REG]],
            ['repassword', 'required', 'on' => [self::SCENARIO_ADD, self::SCENARIO_PASSWORD, self::SCENARIO_REG]],
            [['realname', 'phone'], 'required', 'on' => [self::SCENARIO_REG, self::SCENARIO_ACCOUNT]],
            [['create_time', 'last_activity'], 'safe'],
            [['email', 'password', 'access_token', 'language', 'timeZone', 'realname', 'phone'], 'string', 'max' => 255],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
            ['agree', 'boolean', 'on' => self::SCENARIO_REG],
            ['agree', 'compare', 'compareValue' => 1, 'message' => 'Необходимо принять соглашение'],
            ['phone', 'filter', 'filter' => function ($value) {
                return '+'.str_replace(['+', '(', ')', '-', ' '], '', $value);
            }, 'on' => [self::SCENARIO_ADD, self::SCENARIO_EDIT, self::SCENARIO_REG, self::SCENARIO_ACCOUNT]],
            ['phone', 'match', 'pattern' => '/^\+7\d{10,10}$/i', 'on' => [self::SCENARIO_ADD, self::SCENARIO_EDIT, self::SCENARIO_REG, self::SCENARIO_ACCOUNT], 'enableClientValidation' => false],
            ['phone', 'formValidatePhone', 'on' => [self::SCENARIO_ADD, self::SCENARIO_EDIT, self::SCENARIO_REG, self::SCENARIO_ACCOUNT]],
            ['email_or_phone', 'string', 'on' => self::SCENARIO_RESTORE],
            ['email_or_phone', 'filter', 'filter' => function ($value) {
                return str_replace(['+', '(', ')', ' '], '', $value);
            }, 'on' => self::SCENARIO_RESTORE],
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
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'access_token' => Yii::t('app', 'Access Token'),
            'role_id' => Yii::t('app', 'Role'),
            'create_time' => Yii::t('app', 'Create time'),
            'last_activity' => Yii::t('app', 'Last activity'),
            'language' => Yii::t('app', 'Language'),
            'timeZone' => Yii::t('app', 'Time zone'),
            'realname' => Yii::t('app', 'Full name'),
            'phone' => Yii::t('app', 'Phone'),
            'remember_me' => Yii::t('app', 'Remember me'),
            'repassword'  => Yii::t('app', 'Retype password'),
            'notify'  => Yii::t('app', 'Notify'),
            'old_password' => Yii::t('app', 'Old password'),
            'new_password' => Yii::t('app', 'New password'),
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
        $this->setPassword($this->password);
        $this->create_time = $this->dbTime;
        $this->role_id = Yii::$app->site_options->user_reg_role;
    }

    public function eventAfterInsert()
    {
        if ($this->status == self::STATUS_INACTIVE) {
            $link = Url::to(['/activate/'.$this->access_token], true);


            Mail::createAndSave([
                'to_email'  => $this->email,
                'subject'   => 'Регистрация на сайте '.ucfirst($_SERVER['SERVER_NAME']),
                'body_text' => $link,
                'body_html' => $link,
            ], 'reg');
        }
    }

    public function setActivity()
    {
        $this->last_activity = $this->dbTime;
        $this->save();
    }

    public function refreshAuthkey()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
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
        return static::findOne(['access_token' => $token, 'status' => is_null($type) ? self::STATUS_ACTIVE : $type]);
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
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function formValidateOnlyPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->validatePassword($this->old_password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect password'));
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
     * Validate unique phone in DB
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function formValidatePhone($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $query = self::find()->where(['phone' => $this->phone]);

            if ($this->id) {
                $query = $query->andWhere(['!=', 'id', $this->id]);
            }

            if ($query->count()) {
                $this->addError($attribute, Yii::t('app', 'A user with such an phone is already registered'));
            }
        }
    }

    public function activateUser()
    {
        $this->refreshAuthkey();
        $this->status = self::STATUS_ACTIVE;
        $this->save();
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

    public function loginUser($long_login = false)
    {
        Yii::$app->user->login($this, $long_login ? 3600 * 24 * 30 : 0);
    }

    public function getRules()
    {
        $rules = [];
        $models = ModelsHelper::get();

        if ($this->role->is_admin) {
            foreach ($this->role->rules AS $rule) {
                $rules[$rule['model']] = [
                    'is_view'    => $rule['is_view'],
                    'is_add'     => $rule['is_add'],
                    'is_edit'    => $rule['is_edit'],
                    'is_delete'  => $rule['is_delete'],
                ];

                unset($models[$rule['model']]);
            }
        }

        foreach ($models AS $model_name => $model_data) {
            $rules[$model_name] = [
                'is_view'    => 0,
                'is_add'     => 0,
                'is_edit'    => 0,
                'is_delete'  => 0,
            ];
        }

        return $rules;
    }
}
