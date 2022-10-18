<?php

namespace app\models;

class CatalogSkuPropsValue extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%catalog_sku_props_values}}';
    }

    public function rules()
    {
        return [
            [['catalog_sku_id', 'props_id', 'value'], 'safe'],
        ];
    }

    public function getProps()
    {
        return $this->hasOne(Prop::class, ['id' => 'props_id']);
    }

    public function getPropsValue()
    {
        return $this->hasOne(PropsValue::class, ['id' => 'value']);
    }

    public function getCatalogSku()
    {
        return $this->hasOne(CatalogSku::class, ['id' => 'catalog_sku_id']);
    }

}
