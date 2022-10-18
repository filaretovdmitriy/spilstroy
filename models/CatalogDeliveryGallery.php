<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class CatalogDeliveryGallery extends \app\components\db\ActiveRecordFiles
{

    public $imageFields = [
        'image' => ['catalog_delivery', 'delivery_image']
    ];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название', 'on' => ['default']],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            ['catalog_delivery_id', 'exist', 'targetClass' => CatalogDelivery::class, 'targetAttribute' => 'id'],
            [
                '!image', 'file',
                'extensions' => ['png', 'jpg', 'gif'], 'wrongExtension' => 'Доступные форматы: {extensions}',
                'maxSize' => 2097152, 'tooBig' => 'Не больше 2Мб',
                'skipOnEmpty' => false, 'message' => 'Выберите файл (файл не загружен)'
            ],
            [['name', 'sort', 'catalog_delivery_id'], 'safe'],
        ];
    }

    public static function tableName()
    {
        return '{{%catalog_delivery_gallery}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'sort' => 'Сортировка',
        ];
    }

}
