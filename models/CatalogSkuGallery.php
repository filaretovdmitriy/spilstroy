<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class CatalogSkuGallery extends \app\components\db\ActiveRecordFiles
{

    public $imageFields = [
        'image' => ['catalog_sku_gallery', 'catalog_sku_gallery']
    ];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название', 'on' => ['default']],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            ['is_main', 'default', 'value' => 0],
            ['catalog_sku_id', 'exist', 'targetClass' => CatalogSku::class, 'targetAttribute' => 'id'],
            [
                '!image', 'file',
                'extensions' => ['png', 'jpg', 'gif'], 'wrongExtension' => 'Доступные форматы: {extensions}',
                'maxSize' => 2097152, 'tooBig' => 'Не больше 2Мб',
                'skipOnEmpty' => false, 'message' => 'Выберите файл (файл не загружен)'
            ],
            [['name', 'sort', 'catalog_sku_id', 'is_main'], 'safe'],
        ];
    }

    public static function tableName()
    {
        return '{{%catalog_sku_gallery}}';
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
            'is_main' => 'Главное',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->is_main == 1) {
            self::updateAll(['is_main' => 0], [
                'AND',
                ['!=', 'id', $this->id],
                ['=', 'catalog_sku_id', $this->catalog_sku_id],
            ]);
        }
    }

}
