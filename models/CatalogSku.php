<?php

namespace app\models;

use app\models\Prop;
use yii\helpers\ArrayHelper;

class CatalogSku extends \app\components\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%catalog_sku}}';
    }

    public function rules()
    {
        return [
            [['catalog_id', 'article', 'price', 'status'], 'safe'],
        ];
    }

    public function getCatalog()
    {
        return $this->hasOne(Catalog::class, ['id' => 'catalog_id']);
    }

    public function getValues()
    {
        return $this->hasMany(CatalogSkuPropsValue::class, ['catalog_sku_id' => 'id']);
    }

    public function getProps()
    {
        return $this->hasMany(Prop::class, ['id' => 'props_id'])->via('values');
    }

    public function getImage()
    {
        return $this->getImages()->andWhere(['is_main' => 1])->one();
    }

    public function getImages()
    {
        return $this->hasMany(CatalogSkuGallery::class, ['catalog_sku_id' => 'id']);
    }

    public function getStatusName()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status];
    }

    public function getValuesAsArray()
    {
        $values = $this->values;
        $valuesIds = ArrayHelper::map($values, 'id', 'props_id');
        $propsTypes = ArrayHelper::map(Prop::find()->andWhere(['id' => $valuesIds])->all(), 'id', 'prop_type_id');
        $valuesStrings = ArrayHelper::map(PropsValue::find()->all(), 'id', 'name');
        $valuesArray = [];
        foreach ($values as $value) {
            if (!in_array($propsTypes[$value->props_id], [1, 2, 3, 7])) {
                if (!array_key_exists($value->props_id, $valuesArray)) {
                    $valuesArray[$value->props_id] = [$value->id => $valuesStrings[$value->value]];
                } else {
                    $valuesArray[$value->props_id] += [$value->id => $valuesStrings[$value->value]];
                }
            } else {
                $valuesArray[$value->props_id] = [$value->id => $value->value];
            }
        }
        return $valuesArray;
    }

    public function beforeDelete()
    {

        $this->unlinkAll('values', true);

        foreach ($this->images as $image) {
            $image->delete();
        }

        return parent::beforeDelete();
    }

}
