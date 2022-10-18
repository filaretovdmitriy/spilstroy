<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class CatalogOrderItem extends \app\components\db\ActiveRecord
{

    public function rules()
    {
        return [
            ['catalog_order_id', 'number'],
            ['catalog_id', 'number'],
            ['catalog_sku_id', 'number'],
            ['price', 'number'],
            ['quant', 'number']
        ];
    }

    public static function tableName()
    {
        return '{{%catalog_order_items}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getCatalog()
    {
        return $this->hasOne(Catalog::class, ['id' => 'catalog_id']);
    }

    public function getCatalogSku()
    {
        return $this->hasOne(CatalogSku::class, ['id' => 'catalog_sku_id']);
    }

    /**
     * Получает заказ пользователя
     * @param integer $catalogOrderId - идентификатор заказа (если не задан берется последний нефонормленный заказ пользователя)
     * @return CatalogOrder заказ пользователя
     * @throws \yii\web\HttpException в случае, если заказ с переданным идентификатором не найден
     */
    static private function getOrder($catalogOrderId = null)
    {
        if (is_null($catalogOrderId)) {
            $order = CatalogOrder::getUserOrder(true);
        } else {
            $order = CatalogOrder::findOne($catalogOrderId);
            if (is_null($order)) {
                throw new \yii\web\HttpException(500, 'Order ID:' . $catalogOrderId . ' not found');
            }
        }
        return $order;
    }

    /**
     * Добавление товара в заказ
     * @param integer $id - идентификатор товара или sku
     * @param boolean $isSku - SKU или нет
     * @param integer $quant - количество
     * @param integer $catalogOrderId - идентификатор заказа (если нет, то товар добавляется в послений не оформленный заказ пользователя)
     * @return /app/models/CatalogOrder обновленный заказ
     */
    static function add($id, $isSku = false, $quant = 1, $catalogOrderId = null)
    {
        $order = self::getOrder($catalogOrderId);
        $order->add($id, $isSku, $quant);
        return $order;
    }

    /**
     * Обновляет количество товара в заказе с пересчетом итоговой стоимости заказа
     * @param integer $id - идентификатор товара <b>в заказе</b>
     * @param integer $quant - количество
     * @param integer $catalogOrderId - идентификатор заказа (если нет, то товар добавляется в послений не оформленный заказ пользователя)
     * @return /app/models/CatalogOrder обновленный заказ
     */
    static function edit($id, $quant, $catalogOrderId = null)
    {
        $order = self::getOrder($catalogOrderId);
        $order->edit($id, $quant);
        return $order;
    }

    /**
     * Удаление элемента из заказа с пересчетом итоговой стоимости и количества
     * @param integer $id - идентификатор товара в заказе
     * @return /app/models/CatalogOrder обновленный заказ
     */
    static function deleteItem($id)
    {
        $orderItem = self::findOne($id);
        $order = self::getOrder($orderItem->catalog_order_id);
        $order->deleteItem($id);
        return $order;
    }

    /**
     * Получает товар в заказе
     * @param integer $id - идентификатор товара или sku 
     * @param integer $orderId - идентификатор заказа
     * @param boolean $isSku - SKU или нет
     * @return CatalogOrderItems товар в заказе или null, если такой товар не найден
     */
    static public function getItemByOrder($id, $orderId, $isSku = false)
    {
        $findItem = self::find()->andWhere(['catalog_order_id' => $orderId]);
        if ($isSku) {
            $findItem->andWhere(['catalog_sku_id' => $id]);
        } else {
            $findItem->andWhere(['catalog_id' => $id]);
        }
        return $findItem->one();
    }

    /**
     * Сериализует все заказы товара и сохраняет сериализованный вид в базе
     * @param integer $orderId - идентификатор заказа
     */
    static function serializeOrderGoods($orderId)
    {
        $goods = self::findAll(['catalog_order_id' => $orderId]);

        foreach ($goods as $good) {
            $good->serialize();
        }
    }

    /**
     * Сериализует информацию о товаре
     * @return boolen результат сохранения сериализации
     */
    public function serialize()
    {
        $good = $this->getArrayInfo();
        $good->offsetSet('image', null);
        $good->offsetSet('url', null);
        $this->info = serialize($good);
        return $this->save();
    }

    /**
     * Получает информацию о товаре
     * @return \ArrayObject информация о товаре
     */
    public function getArrayInfo()
    {
        $catalog = $this->catalog;
        if ((is_null($catalog) || $catalog->status == Catalog::STATUS_DISABLE || $catalog->categorie->status == CatalogCategorie::STATUS_DISABLE || ($this->catalog_sku_id && $this->getCatalogSku()->exists() === false)) && !empty($this->info)
        ) {
            $good = unserialize($this->info);
            $good->offsetSet('serialize', true);
            return $good;
        } elseif ((is_null($catalog) || $catalog->status == Catalog::STATUS_DISABLE || ($this->catalog_sku_id && $this->getCatalogSku()->exists() === false)) && empty($this->info)) {
            return null;
        }
        $good = new \ArrayObject();
        $good->setFlags(\ArrayObject::ARRAY_AS_PROPS);
        $good->offsetSet('id', $catalog->id);
        $good->offsetSet('serialize', false);
        $good->offsetSet('name', $catalog->name);
        $good->offsetSet('sku', []);
        if ($this->catalog_sku_id) {
            $catalogSku = $this->catalogSku;
            $good->offsetSet('article', $catalogSku->article);
            $skus = [];
            foreach ($catalogSku->values as $skuProp) {
                $sku = [];
                $prop = $skuProp->props;
                $sku['name'] = $prop->name;
                if ($prop->prop_type_list_id === 0) {
                    $sku['value'] = $skuProp->value;
                } else {
                    $sku['value'] = $skuProp->propsValue->name;
                }

                $skus[$skuProp->props_id] = $sku;
            }
            $good->sku = $skus;
        } else {
            $good->offsetSet('article', $catalog->article);
        }
        $good->offsetSet('url', \yii\helpers\Url::to([
                    'site/catalog_element',
                    'catalog_categorie_alias' => $catalog->categorie->alias,
                    'catalog_id' => $catalog->id,
                    'catalog_alias' => $catalog->alias,
        ]));
        $good->offsetSet('price', $this->price);
        $good->offsetSet('quant', $this->quant);
        $good->offsetSet('summ', $this->price * $this->quant);
        $good->offsetSet('image', $catalog->getPath('image'));

        return $good;
    }

}
