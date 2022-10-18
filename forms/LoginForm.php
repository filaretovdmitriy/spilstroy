<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\User;

class LoginForm extends Model
{

    public $login;
    public $password;
    public $rememberMe = true;
    private $_user = false;

    public function rules()
    {
        return [
            ['login', 'required', 'message' => 'Заполните поле Логин'],
            ['password', 'required', 'message' => 'Заполните поле Пароль'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Доступ закрыт');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByLogin($this->login);
        }
        return $this->_user;
    }

}
