<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use app\models\Slider;

class Slide extends \app\components\db\ActiveRecordFiles
{

    public $imageFields = ['image' => ['slides', 'slide']];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название слайда', 'on' => ['default']],
            ['status', 'in', 'range' => [1, 2], 'message' => 'Выберите правильное значение', 'except' => 'filter'],
            ['status', 'default', 'value' => 1, 'except' => 'filter'],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            ['slider_id', 'exist', 'targetClass' => Slider::class, 'targetAttribute' => 'id'],
            [
                '!image', 'file',
                'extensions' => ['png', 'jpg', 'gif'], 'wrongExtension' => 'Доступные форматы: {extensions}',
            ],
            [['content', 'link'], 'safe']
        ];
    }

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['name', 'status'],
        ];
    }

    public function getSlider()
    {
        return $this->hasOne(Slider::class, ['id' => 'slider_id']);
    }

    public static function tableName()
    {
        return '{{%slide}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

}
