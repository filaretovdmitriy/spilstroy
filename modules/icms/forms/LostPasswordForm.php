<?php

namespace app\modules\icms\forms;

use yii\base\Model;
use app\models\User;
use app\components\IcmsHelper;

class LostPasswordForm extends Model
{

    public $email;

    public function rules()
    {
        return [
            ['email', 'email', 'message' => 'Введите правильный email'],
        ];
    }

    public function changePassword()
    {

        $user = User::find()->andWhere(['email' => $this->email])->one();
        if (!empty($user)) {
            $new_password = IcmsHelper::generatePassword(10);
            $user->setPassword($new_password);
            $user->load(['LostPasswordForm' => ['password' => $new_password]], 'LostPasswordForm');
            $user->save(false);

            \app\components\Mailer::lostPassword($user->login, $user->email, $new_password);
        } else {
            $this->addError('email', 'Такой почты нет в базе!');
        }
    }

}
