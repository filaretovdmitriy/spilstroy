<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class UserLog extends ActiveRecord
{

    public function rules()
    {
        return [
            [['user_id', 'name', 'ip'], 'safe']
        ];
    }

    public static function tableName()
    {
        return '{{%user_log}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    static function addLog($forDeveloper = false)
    {
        $log = new self();
        $user = \Yii::$app->user->identity;
        $log->user_id = $user->id;
        if (empty($user->name)) {
            $log->name = $user->login;
        } else {
            $log->name = $user->name;
        }
        $log->ip = \Yii::$app->request->getUserIp();
        $log->developer_only = $forDeveloper;
        $log->save();
    }

}
