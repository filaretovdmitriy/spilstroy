<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const DEFAULT_ROLE = 'user';

    public $role;
    public $password;
    public $password_repeat;

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['login', 'email', 'name'],
            'edit' => ['login', 'email', 'name', 'status', 'role'],
            'editPassword' => ['password', 'password_repeat'],
            'lostPassword' => ['password'],
            'registration' => ['name', 'login', 'email', 'password_hash', 'auth_key'],
            'updateAccount' => ['name', 'login', 'email', 'password', 'password_repeat'],
            'updateAccountNP' => ['name', 'login', 'email']
        ];
    }

    public function rules()
    {
        return [
            ['login', 'required', 'message' => 'Заполните поле Login', 'on' => ['default', 'edit']],
            ['login', 'unique', 'message' => 'Пользователь с таким логином уже зарегистрирован', 'on' => ['default', 'edit']],
            ['email', 'email', 'message' => 'Не является правильным E-Mail адресом', 'on' => ['default', 'edit']],
            ['email', 'required', 'message' => 'Заполните поле E-Mail', 'on' => ['default', 'edit']],
            ['email', 'unique', 'message' => 'Пользователь с таким email\'ом уже зарегистрирован', 'on' => ['default', 'edit']],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED], 'message' => 'Выберите правильное значение'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['role', 'default', 'value' => self::DEFAULT_ROLE],
            ['name', 'required', 'message' => 'Введите имя пользователя', 'on' => ['default', 'edit']],
            ['password', 'required', 'message' => 'Введите пароль'],
            ['password_repeat', 'required', 'message' => 'Повторите пароль'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
        ];
    }

    static function getStatuses()
    {
        return [self::STATUS_ACTIVE => 'Активный', self::STATUS_DELETED => 'Удален'];
    }

    static function getRolesAsArray()
    {
        $roles = ArrayHelper::map(Yii::$app->authManager->roles, 'name', 'description');
        if (\Yii::$app->user->can('developer') === false) {
            unset($roles['developer']);
        }
        return $roles;
    }

    public static function getFirstRoleName($id)
    {
        $roles = Yii::$app->getAuthManager()->getRolesByUser($id);   
        unset($roles['guest']);

        $role = array_pop($roles);

        if (is_null($role) === true) {
            return self::DEFAULT_ROLE;
        }

        return $role->name;
    }

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Поиск пользователя по логину
     * @param string $login - логин
     * @return /app/models/User|null - модель пользователя или null, если пользователя с таким логином не найдено
     */
    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Поиск пользователя по токену сброса пароля
     * @param string $token токен для сброса пароля
     * @return /app/models/User|null - модель пользователя или null, если пользователя с таким токеном не существует
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Проверка токена на валидность
     * @param string $token токен для сброса пароля
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Проверка пароля для текущего пользователя
     * @param string $password пароль
     * @return boolean
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Хеширование пароля и установка
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Генерация кода аутентификации для галочки "Запомнить"
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Генерация нового токена сброса пароля
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Удаление токена сброса пароля
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getRoles()
    {
        $auth = \Yii::$app->authManager;
        $roles = $auth->getRolesByUser($this->id);

        if (count($roles) > 0) {
            $rolesArr = [];
            foreach ($roles as $role) {
                $rolesArr[] = $role;
            }
            return $rolesArr;
        } else {
            return null;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $auth = Yii::$app->authManager;

        $oldRole = $this->getFirstRoleName($this->id);

        if (($this->role !== $oldRole || $insert === true) && empty($this->role) === false) {
            $auth->revokeAll($this->id);
            
            $role = $auth->getRole($this->role);
            $auth->assign($role, $this->id);
        }
        
    }

    public function getWish()
    {
        return $this->hasMany(Catalog::class, ['id' => 'catalog_id'])->
                        viaTable('{{%wishlist}}', ['user_id' => 'id']);
    }

    public $wishGoods = null;

    public function getWishList()
    {
        if (is_null($this->wishGoods)) {
            $this->wishGoods = $this->wish;
        }
        return $this->wishGoods;
    }

    public function getWishGoodsId()
    {
        return \app\components\IcmsHelper::map($this->getWishList(), 'id');
    }

    public function beforeSave($insert)
    {
        if (empty($this->password) === false) {
            $this->setPassword($this->password);
        }
        return parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
        if (\Yii::$app->user->can($this->role) === false) {
            return false;
        }
        \Yii::$app->authManager->revokeAll($this->id);
        return parent::beforeDelete();
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->role = self::getFirstRoleName($this->id);
    }

    public static function getNamesAsArray($fieldName = 'name')
    {
        $primaryKeys = self::primaryKey();
        $primaryKey = array_shift($primaryKeys);
        return ArrayHelper::map(self::find()->all(), $primaryKey, $fieldName);
    }

}
