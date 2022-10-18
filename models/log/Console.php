<?php

namespace app\models\log;

use yii\behaviors\TimestampBehavior;

/**
 * @property integer $id
 * @property string $user
 * @property string $controller
 * @property string $action
 * @property string $command
 * @property string $start
 * @property string $end
 * @property integer $exit_code
 *
 * @property integer $created_at
 * @property integer $updated_at
 */
class Console extends \app\components\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%log_console}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

}
