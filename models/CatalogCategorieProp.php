<?php

namespace app\models;

use app\components\db\ActiveRecord;

class CatalogCategorieProp extends ActiveRecord
{

    public function rules()
    {
        return [
            [['catalog_categorie_id', 'props_id'], 'safe', 'on' => 'default'],
        ];
    }

    public static function tableName()
    {
        return '{{%catalog_categotie_props}}';
    }

    public function getProps()
    {
        return $this->hasOne(Prop::class, ['id' => 'props_id']);
    }

    public function getPropsFilter()
    {
        return $this->hasOne(Prop::find()->andWhere('is_filter=1')->all(), ['id' => 'props_id']);
    }

}
