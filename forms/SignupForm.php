<?php

namespace app\forms;

use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $login;
    public $email;
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
            ['login', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким Логином уже существует'],
            ['login', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с такой почтой уже существует'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['repassword', 'required'],
            ['repassword', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->scenario = "registration";
            $user->login = $this->login;
            $user->name = $this->login;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                \app\components\Mailer::registration($user->id);
                return $user;
            }
        }

        return null;
    }

}
