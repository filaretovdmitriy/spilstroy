<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class TreeGallery extends \app\components\db\ActiveRecordFiles
{

    public $imageFields = [
        'image' => ['tree_gallery', 'tree_gallery']
    ];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название', 'on' => ['default']],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            ['tree_id', 'exist', 'targetClass' => Tree::class, 'targetAttribute' => 'id'],
            [
                '!image', 'file',
                'extensions' => ['png', 'jpg', 'gif'], 'wrongExtension' => 'Доступные форматы: {extensions}',
                'maxSize' => 2097152, 'tooBig' => 'Не больше 2Мб',
                'skipOnEmpty' => false, 'message' => 'Выберите файл (файл не загружен)'
            ],
            [['name', 'sort', 'tree_id'], 'safe'],
        ];
    }

    public static function tableName()
    {
        return '{{%tree_gallery}}';
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
