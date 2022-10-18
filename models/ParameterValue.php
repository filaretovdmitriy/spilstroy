<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use app\components\db\ActiveRecordFiles;

class ParameterValue extends ActiveRecordFiles
{

    public function rules()
    {
        return [
            ['value', 'required', 'message' => 'Поле должно быть заполнено'],
            ['sort', 'default', 'value' => 0],
            ['parameter_id', 'exist', 'targetClass' => Parameter::class, 'targetAttribute' => 'id'],
            [['parameter_id', 'value', 'sort'], 'safe'],
        ];
    }

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['value'],
        ];
    }

    public static function tableName()
    {
        return '{{%parameters_values}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function afterDelete()
    {
        parent::afterDelete();

        \Yii::$app->cache->delete(Parameter::CACHE_NAME);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        \Yii::$app->cache->delete(Parameter::CACHE_NAME);
    }

}
