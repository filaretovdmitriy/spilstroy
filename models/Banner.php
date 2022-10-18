<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class Banner extends \app\components\db\ActiveRecordFiles
{

    const TYPE_IMAGE = 1;
    const TYPE_FLASH = 2;

    public $imageFields = ['file' => ['banners', 'banner']];
    public $fileFields = ['file' => ['banners', 'banner']];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название баннера', 'on' => ['default']],
            ['status', 'in', 'range' => [1, 2], 'message' => 'Выберите правильное значение', 'except' => 'filter'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE, 'except' => 'filter'],
            ['sort', 'default', 'value' => 0],
            ['type', 'in', 'range' => [1, 2], 'message' => 'Выберите правильное значение', 'except' => 'filter'],
            ['type', 'default', 'value' => self::TYPE_IMAGE, 'except' => 'filter'],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            ['banner_group_id', 'exist', 'targetClass' => BannerGroup::class, 'targetAttribute' => 'id'],
            [['link', 'width', 'height'], 'safe']
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

    static function getTypes()
    {
        return [
            self::TYPE_IMAGE => 'Изображение',
            self::TYPE_FLASH => 'Флеш'
        ];
    }

    public function getGroup()
    {
        return $this->hasOne(BannerGroup::class, ['id' => 'banner_group_id']);
    }

    public static function tableName()
    {
        return '{{%banner}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

}
