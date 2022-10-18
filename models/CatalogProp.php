<?php

namespace app\models;

use app\components\db\ActiveRecord;

class CatalogProp extends ActiveRecord
{

    public function rules()
    {
        return [
            [['catalog_id', 'props_id', 'value'], 'safe', 'on' => 'default'],
        ];
    }

    public static function tableName()
    {
        return '{{%catalog_props}}';
    }

    public function getPropsModel()
    {
        return $this->hasOne(Prop::class, ['id' => 'props_id']);
    }

    static function getAllCategoriePropsId($categorieId)
    {
        $categorie = CatalogCategorie::findOne($categorieId);
        $catalogCategorieProps = \yii\helpers\ArrayHelper::map($categorie->catalogCategorieProps, 'props_id', 'props_id');
        $parents = \app\components\IcmsHelper::getAllParents(CatalogCategorie::findOne($categorieId), 'pid');
        foreach ($parents as $parentId => $parentName) {
            $parent = CatalogCategorie::findOne($parentId);
            foreach ($parent->catalogCategorieProps as $parentProp) {
                if (!in_array($parentProp->props_id, $catalogCategorieProps)) {
                    $catalogCategorieProps[$parentProp->props_id] = $parentProp->props_id;
                }
            }
        }

        return array_keys($catalogCategorieProps);
    }

}
