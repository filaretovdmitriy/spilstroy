<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use app\components\db\ActiveRecordFiles;

class PropsValue extends ActiveRecordFiles
{

    public $imageFields = [
        'image' => ['props_value', 'props_value']
    ];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название параметра', 'on' => ['default']],
            ['sort', 'default', 'value' => 100],
            [['name', 'props_id', 'sort', 'image'], 'safe']
        ];
    }

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['name'],
        ];
    }

    public static function tableName()
    {
        return '{{%props_values}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

}
