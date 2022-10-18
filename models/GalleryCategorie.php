<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class GalleryCategorie extends \app\components\db\ActiveRecordFiles
{

    public $imageFields = [
        'image' => ['gallery_categorie', 'gallery_categorie']
    ];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите имя категории', 'on' => ['default']],
            ['name', 'unique', 'message' => 'Категория с таким с именем уже существет', 'on' => ['default']],
            ['sort', 'default', 'value' => 0],
            [['content', 'title_seo', 'description_seo', 'keywords_seo', 'sort', 'status', 'pid'], 'safe', 'on' => 'default'],
            ['!image', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'message' => 'Доступные типы файлов png, jpg, gif'],
        ];
    }

    public function getGallerys()
    {
        return $this->hasMany(Gallery::class, ['gallery_categorie_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(self::class, ['pid' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
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
        return '{{%gallery_categorie}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeDelete()
    {
        foreach ($this->categories as $categorie) {
            $categorie->delete();
        }

        foreach ($this->gallerys as $gallery) {
            $gallery->delete();
        }
        return parent::beforeDelete();
    }

    static function siteMap()
    {
        return [
            'title' => 'Категории галереи',
            'route' => 'site/gallery_element',
            'url' => [
                'gallery_categorie_id' => 'id'
            ],
            'condition' => ['status' => self::STATUS_ACTIVE],
        ];
    }

}
