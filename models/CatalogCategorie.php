<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class CatalogCategorie extends \app\components\db\ActiveRecordFiles
{

    public $imageFields = [
        'image' => ['catalog_categorie', 'catalog_categorie']
    ];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите имя категории', 'on' => ['default']],
            ['alias', 'required', 'message' => 'Введите алиас категории', 'on' => ['default']],
            ['alias', 'unique', 'message' => 'Категория с таким с алиасом уже существет', 'on' => ['default']],
            ['alias', 'match', 'pattern' => '/[a-zA-Z0-9_]+$/', 'message' => 'Только латинские буквы и цифры', 'on' => ['default']],
            [['content', 'title_seo', 'description_seo', 'keywords_seo', 'sort', 'status', 'pid'], 'safe', 'on' => 'default'],
            [
                '!image', 'file',
                'extensions' => ['png', 'jpg', 'gif'], 'wrongExtension' => 'Доступные форматы: {extensions}',
            ],
            [['auto_url'], 'safe'],
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

    public function getCatalogs()
    {
        return $this->hasMany(Catalog::class, ['catalog_categorie_id' => 'id']);
    }

    public function getSubcatalogs()
    {
        return $this->hasMany(CatalogCategorie::class, ['pid' => 'id'])->andWhere(['status'=>1]);
    }

    public function getCatalogCategorieProps()
    {
        return $this->hasMany(CatalogCategorieProp::class, ['catalog_categorie_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(self::class, ['pid' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
    }

    public function getChildrensCategorieId($ids = []) {
        foreach ($this->getCategories()->all() as $categorie) {
            $ids[] = $categorie->id;
            
            $ids = array_merge($ids, $categorie->getChildrensCategorieId());
        }

        return $ids;
    }


    public static function tableName()
    {
        return '{{%catalog_categorie}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert && array_key_exists('pid', $changedAttributes) && $changedAttributes['pid'] != $this->pid) {
            foreach ($this->catalogs as $catalog) {
                $props = $catalog->propsValues;
                foreach ($props as $prop) {
                    $prop->delete();
                }
            }
        }
        $this->clearCatalogCache();
    }

    public function beforeDelete()
    {
        foreach ($this->catalogs as $catalog) {
            $catalog->delete();
        }
        foreach ($this->categories as $categorie) {
            $categorie->delete();
        }
        foreach ($this->catalogCategorieProps as $catalogCategorieProp) {
            $catalogCategorieProp->delete();
        }
        $this->clearCatalogCache();
        return parent::beforeDelete();
    }

    public function clearCatalogCache()
    {
        if (\Yii::$app->cache->exists('catalogMenu')) {
            \Yii::$app->cache->delete('catalogMenu');
        }
    }

    static function getCatalogMainCategorie($catalog_categorie_id)
    {
        $catalog_categorie = self::find()->where(['id' => $catalog_categorie_id])->one();
        if ($catalog_categorie->pid == 0) {
            $item = $catalog_categorie->id;
        } else {
            $item = self::getCatalogMainCategorie($catalog_categorie->pid);
        }
        return $item;
    }

    static function getAliases($activeOnly = true)
    {
        $categoriesQuery = self::find();
        $categoriesQuery->select(['id', 'alias']);
        if ($activeOnly) {
            $categoriesQuery->andWhere(['status' => self::STATUS_ACTIVE]);
        }
        return \yii\helpers\ArrayHelper::map($categoriesQuery->all(), 'id', 'alias');
    }

    static function siteMap()
    {
        return [
            'title' => 'Категории каталога',
            'route' => 'site/catalog_categorie',
            'url' => [
                'catalog_categorie_alias' => 'alias'
            ],
            'condition' => ['status' => self::STATUS_ACTIVE],
            'priority' => 0.8
        ];
    }

}
