<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use app\models\GalleryCategorie;

class Gallery extends \app\components\db\ActiveRecordFiles
{

    public $imageFields = [
        'image' => ['gallery', 'gallery']
    ];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название', 'on' => ['default']],
            ['status', 'in', 'range' => [1, 2], 'message' => 'Выберите правильное значение',],
            ['status', 'default', 'value' => 1],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            ['gallery_categorie_id', 'exist', 'targetClass' => GalleryCategorie::class, 'targetAttribute' => 'id'],
            [
                '!image', 'file',
                'extensions' => ['png', 'jpg', 'gif'], 'wrongExtension' => 'Доступные форматы: {extensions}',
            ],
            [['name', 'status', 'gallery_categorie_id', 'sort'], 'safe'],
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
        return '{{%gallery}}';
    }

    public function getCategorie()
    {
        return $this->hasOne(GalleryCategorie::class, ['id' => 'gallery_categorie_id']);
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

}
