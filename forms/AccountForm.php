<?php

namespace app\forms;

use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Account form
 */
class AccountForm extends Model
{

    public $login;
    public $email;
    public $name;
    public $password;
    public $repassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['login', 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }],
            ['login', 'required'],
            ['login', 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }],
            ['login', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким Логином уже существует',],
            ['login', 'string', 'min' => 2, 'max' => 255],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'except' => ['id' => \Yii::$app->user->id], 'message' => 'Пользователь с такой почтой уже существует'],
            ['password', 'string', 'min' => 6],
            ['repassword', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function saveAccount()
    {
        if ($this->validate()) {
            $user = User::findOne(\Yii::$app->user->id);
            $user->scenario = "updateAccount";
            $user->login = $this->login;
            $user->name = $this->name;
            $user->email = $this->email;
            if (($this->password != "") && ($this->password == $this->repassword)) {
                $user->scenario = "updateAccountPassword";
                $user->setPassword($this->password);
            }
            if ($user->save()) {
                return $user;
            }
        }
        return null;
    }

}
