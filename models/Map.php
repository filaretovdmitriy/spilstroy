<?php

namespace app\models;

use app\components\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Map extends ActiveRecord
{

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите имя карты', 'on' => ['default']],
            ['name', 'unique', 'message' => 'Карта с таким именем уже существет', 'on' => ['default']],
            ['status', 'in', 'range' => [1, 2], 'message' => 'Выберите правильное значение',],
            ['status', 'default', 'value' => 1],
            [['content', 'mark_count', 'center_x', 'center_y', 'zoom', 'mark_default_color'], 'safe', 'on' => ['default']],
        ];
    }

    public function getMarks()
    {
        return $this->hasMany(MapMark::class, ['map_id' => 'id']);
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
        return '{{%maps}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    static function renderView($key)
    {
        $map = self::findOne($key);
        if (is_null($map)) {
            return 'Карта не найдена';
        }

        return \app\widgets\YandexMap::widget([
                    'map' => $map,
                    'marks' => $map->marks
        ]);
    }

    public function beforeDelete()
    {
        foreach ($this->marks as $mark) {
            $mark->delete();
        }
        return parent::beforeDelete();
    }

}
