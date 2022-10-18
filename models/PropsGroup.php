<?php

namespace app\models;

use app\components\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class PropsGroup extends ActiveRecord
{

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название группы', 'on' => ['default']],
            ['alias', 'unique', 'message' => 'Такой алиас уже есть в базе', 'on' => ['default']],
            [['name', 'alias'], 'safe']
        ];
    }

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['name', 'alias'],
        ];
    }

    public static function tableName()
    {
        return '{{%props_groups}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getProps()
    {
        return $this->hasMany(Prop::class, ['props_groups_id' => 'id']);
    }

    static function getByAlias($aliasName)
    {
        return self::find()->andWhere(['alias' => $aliasName])->one();
    }

    public function beforeDelete()
    {
        Prop::updateAll(['props_groups_id' => 0], 'props_groups_id = ' . $this->id);

        return parent::beforeDelete();
    }

}
