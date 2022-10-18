<?php

namespace app\models;

use app\components\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Slider extends ActiveRecord
{

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите имя слайдера', 'on' => ['default']],
            ['name', 'unique', 'message' => 'Слайдер с именем уже существет', 'on' => ['default']],
        ];
    }

    public function getSlides()
    {
        return $this->hasMany(Slide::class, ['slider_id' => 'id']);
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
        return '{{%slider}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeDelete()
    {
        foreach ($this->slides as $slide) {
            $slide->delete();
        }
        return parent::beforeDelete();
    }

}
