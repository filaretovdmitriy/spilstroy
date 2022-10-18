<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class MapMark extends \app\components\db\ActiveRecordFiles
{

    public $imageFields = [
        'image' => ['map_marks', 'marks']
    ];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название метки', 'on' => ['default']],
            ['status', 'in', 'range' => [1, 2], 'message' => 'Выберите правильное значение',],
            ['status', 'default', 'value' => 1],
            ['map_id', 'exist', 'targetClass' => Map::class, 'targetAttribute' => 'id'],
            [
                '!image', 'file',
                'extensions' => ['png', 'jpg', 'gif'], 'wrongExtension' => 'Доступные форматы: {extensions}',
            ],
            [['content', 'coordinate_x', 'coordinate_y', 'color', 'image_x', 'image_y', 'image_width', 'image_height'], 'safe']
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

    public function getMap()
    {
        return $this->hasOne(Map::class, ['id' => 'map_id']);
    }

    public static function tableName()
    {
        return '{{%map_marks}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->map->updateCounters(['mark_count' => 1]);
        }
        if (empty($this->image)) {
            $this->image_x = $this->image_y = $this->image_width = $this->image_height = 0;
        } elseif (((isset($this->oldAttributes['image']) && $this->oldAttributes['image'] !== $this->image) || $this->image_width == 0 || $this->image_height == 0) && file_exists($this->getFullPath('image'))) {
            $imageInfo = getimagesize($this->getFullPath('image'));
            $this->image_width = $imageInfo[0];
            $this->image_height = $imageInfo[1];
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (isset($changedAttributes['map_id']) && $changedAttributes['map_id'] != $this->map_id) {
            $this->map->updateCounters(['mark_count' => 1]);
            Map::updateAllCounters(['mark_count' => -1], 'id = :id', ['id' => $changedAttributes['map_id']]);
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete()
    {
        $this->map->updateCounters(['mark_count' => -1]);
        return parent::beforeDelete();
    }

}
