<?php

namespace app\models;

use app\components\db\ActiveRecord;

class PropTypeList extends ActiveRecord
{

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название параметра', 'on' => ['default']],
            [['name'], 'safe']
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
        return '{{%prop_type_list}}';
    }

}
