<?php

namespace app\components\properties;

use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use app\components\properties\Groups;
use app\models\CatalogSku;
use app\models\CatalogSkuPropsValue;

class Sku extends DynamicModel
{

    public $groups;

    public function attributeLabels()
    {
        return ArrayHelper::map(\app\models\Prop::find()->andWhere(['is_sku' => 1])->all(), 'alias', 'name');
    }

    public function __construct(array $attributes = [], $config = [])
    {
        parent::__construct($attributes, $config);

        $this->groups = new Groups($this);
    }

    public function save($catalogId, $skuId = null, $price = 0, $article = '', $status = 0)
    {
        //TODO: Тут надо как то проверять не существует ли такое уже sku в базе
        if (is_null($skuId)) {
            $sku = new CatalogSku();
        } else {
            $sku = CatalogSku::findOne($skuId);
            CatalogSkuPropsValue::deleteAll('catalog_sku_id = :skuId', ['skuId' => $skuId]);
        }
        $sku->catalog_id = $catalogId;
        $sku->article = $article;
        $sku->price = $price;
        $sku->status = $status;
        $sku->save();

        $propsInfo = \app\components\IcmsHelper::map(\app\models\Prop::find()->all(), 'alias', ['id', 'is_most', 'prop_type_id']);
        foreach ($this->attributes as $alias => $values) {
            if (empty($values) && $propsInfo[$alias]['prop_type_id'] != 7) {
                continue;
            }
            if ($propsInfo[$alias]['prop_type_id'] == 7 && $propsInfo[$alias]['is_most'] != 1 && empty($values)) {
                continue;
            }
            if (!is_array($values)) {
                $values = [0 => $values];
            }
            foreach ($values as $value) {
                $skuValue = new CatalogSkuPropsValue();
                $skuValue->catalog_sku_id = $sku->id;
                $skuValue->props_id = $propsInfo[$alias]['id'];
                $skuValue->value = $value;
                $skuValue->save();
            }
        }

        return true;
    }

}
