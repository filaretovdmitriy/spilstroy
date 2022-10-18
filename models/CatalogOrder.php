<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class CatalogOrder extends \app\components\db\ActiveRecord
{

    const STATUS_NEW = 3;
    const STATUS_SEND = 1;
    const STATUS_USER_CANCEL = 2;

    public function rules()
    {
        return [
            ['catalog_order_status_id', 'number'],
            ['user_name', 'required', 'message' => 'Поле "Обращение" должно быть заполнено', 'on' => 'send'],
            ['user_name', 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }, 'on' => 'send'],
            ['user_email', 'required', 'message' => 'Введите email адрес', 'on' => 'send'],
            ['user_email', 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }, 'on' => 'send'],
            ['user_email', 'email', 'message' => 'Введите корректный email адрес', 'on' => 'send'],
            ['user_phone', 'required', 'message' => 'Введите телефон', 'on' => 'send'],
            ['user_phone', 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }, 'on' => 'send'],

            [['user_city', 'user_street', 'user_home', 'comment', 'user_phone', 'user_email', 'user_name'], 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }, 'on' => 'send'],
            [['user_city', 'user_street', 'user_home', 'comment', 'user_phone', 'user_email', 'user_name', 'export', 'export_date'], 'safe', 'on' => 'default'],
        ];
    }

    public static function tableName()
    {
        return '{{%catalog_order}}';
    }

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['id'],
            'send' => ['catalog_pay_id', 'catalog_delivery_id', 'user_name', 'user_email', 'user_city', 'user_street', 'user_home', 'comment', 'user_phone', 'user_email', 'user_name'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getOrderItems()
    {
        return $this->hasMany(CatalogOrderItem::class, ['catalog_order_id' => 'id']);
    }

    public function getStatus()
    {
        return $this->hasOne(CatalogOrderStatus::class, ['id' => 'catalog_order_status_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getDelivery()
    {
        return $this->hasOne(CatalogDelivery::class, ['id' => 'catalog_delivery_id']);
    }

    public function getPay()
    {
        return $this->hasOne(CatalogPay::class, ['id' => 'catalog_pay_id']);
    }

    static function getStatuses()
    {
        $statuses = CatalogOrderStatus::getStatuses();
        unset($statuses[self::STATUS_NEW]);
        return $statuses;
    }

    public function getActiveOrders()
    {
        return $this->find()->andWhere(['<>', 'catalog_order_status_id', 3])->all();
    }

    /**
     * Создание нового заказа
     * @return CatalogOrder новый заказ
     */
    static function createOrder()
    {
        $order = new self();
        if (!\Yii::$app->user->isGuest) {
            $order->user_id = \Yii::$app->user->id;
        }
        $userSession = \Yii::$app->session->id;
        if (empty($userSession)) { # Костыль из-за бага PHP
            session_start();
            $userSession = session_id();
        }
        $order->session_id = $userSession;
        $order->catalog_order_status_id = self::STATUS_NEW;
        $order->catalog_delivery_id = CatalogDelivery::getDefaultId();
        $order->catalog_pay_id = CatalogPay::getDefaultId();

        $order->save();

        return $order;
    }

    /**
     * Получает последний заказ не оформленный заказ пользователя
     * @param boolean $createNew - создавать ли новый заказ, если последнего не существует
     * @return \app\models\CatalogOrder null - если подходящий заказ не найден, CatalogOrder - если найден
     */
    static function getUserOrder($createNew = true)
    {
        $findOrder = self::find()->where(['catalog_order_status_id' => self::STATUS_NEW])->orderBy('created_at DESC');

        if (\Yii::$app->user->isGuest) {
            $findOrder->andWhere(['session_id' => \Yii::$app->session->id]);
        } else {
            $findOrder->andWhere('session_id = :sid OR user_id = :uid', [
                'sid' => \Yii::$app->session->id,
                'uid' => \Yii::$app->user->id
            ]);
        }

        $order = $findOrder->one();

        if (is_null($order) && $createNew) {
            return self::createOrder();
        } elseif (!is_null($order) && !\Yii::$app->user->isGuest && $order->user_id == 0) {
            $order->user_id = \Yii::$app->user->id;
            $order->save();
        }

        return $order;
    }

    /**
     * Пересчитывает сумму и количество в заказе
     * @param integer $id - идентификатор заказа
     * @return CatalogOrder
     */
    static function reCalc($id = null)
    {
        if (is_null($id)) {
            $order = self::getUserOrder(true);
        } else {
            $order = self::findOne($id);
        }

        $orderGoods = $order->orderItems;

        $totalPrice = 0;
        $totalQuant = 0;
        foreach ($orderGoods as $orderGood) {
            if (!$orderGood->catalog_sku_id) {
                $catalog = $orderGood->catalog;
                $price = $catalog->price;
            } else {
                $catalogSku = $orderGood->catalogSku;
                $price = $catalogSku->price;
            }
            if ($price != $orderGood->price) {
                $orderGood->price = $price;
                $orderGood->save();
            }

            $totalQuant += $orderGood->quant;
            $totalPrice += $orderGood->price;
        }

        $order->total_price = $totalPrice;
        $order->total_count = $totalQuant;
        $order->save();
        return $order;
    }

    /**
     * Получает все товары заказа
     * @return \ArrayObject товары заказа
     */
    public function getGoods()
    {
        $goods = [];

        $orderGoods = $this->orderItems;
        foreach ($orderGoods as $orderGood) {
            $goodInfo = $orderGood->getArrayInfo();
            if (!is_null($goodInfo)) {
                $goods[$orderGood->id] = $goodInfo;
            } else {
                $this->deleteItem($orderGood->id);
            }
        }

        return $goods;
    }

    private function updateItemCounters($price, $oldPrice, $quant, $oldQuant)
    {
        $goodSumm = $quant * $price;
        $goodOldSumm = $oldPrice * $oldQuant;

        $this->total_price += ($goodSumm - $goodOldSumm);
        $this->total_count += ($quant - $oldQuant);

        $this->save();
    }

    /**
     * Добавление товара в заказ
     * @param integer $id - идентификатор товара или sku
     * @param boolean $isSku - SKU или нет
     * @param integer $quant - количество
     */
    public function add($id, $isSku = false, $quant = 1)
    {
        if ($isSku) {
            $orderItem = $this->addSkuItem($id, $quant, $this->id);
        } else {
            $orderItem = $this->addCatalogItem($id, $quant, $this->id);
        }
        return $orderItem;
    }

    /**
     * Добавляет товар в заказ
     * @param integer $id - идентификатор товара
     * @param integer $quant - количество товаров, добавляемое в заказ
     * @return /app/models/CatalogOrderItem добавленный товар
     * @throws \yii\web\HttpException
     */
    public function addCatalogItem($id, $quant)
    {
        $catalog = Catalog::findOne($id);
        if (is_null($catalog)) {
            throw new \yii\web\HttpException(500, 'Good ID:' . $id . ' not found');
        }

        $orderItem = CatalogOrderItem::getItemByOrder($id, $this->id);
        if (is_null($orderItem)) {
            $orderItem = new CatalogOrderItem();
            $orderItem->catalog_order_id = $this->id;
            $orderItem->catalog_id = $id;
        }
        $oldPrice = $orderItem->price;
        $oldQuant = $orderItem->quant;

        $orderItem->price = $catalog->price;
        $orderItem->quant += $quant;
        $orderItem->save();

        $this->updateItemCounters($orderItem->price, $oldPrice, $orderItem->quant, $oldQuant);

        return $orderItem;
    }

    /**
     * Добавляет SKU в заказ
     * @param integer $id - идентификатор SKU
     * @param integer $quant - количество товаров, добавляемое в заказ
     * @return /app/models/CatalogOrderItem добавленный товар
     * @throws \yii\web\HttpException
     */
    public function addSkuItem($id, $quant)
    {
        $catalogSku = CatalogSku::findOne($id);
        if (is_null($catalogSku)) {
            throw new \yii\web\HttpException(500, 'Good SKU ID:' . $id . ' not found');
        }

        $orderItem = CatalogOrderItem::getItemByOrder($id, $this->id, true);
        if (is_null($orderItem)) {
            $orderItem = new CatalogOrderItem();
            $orderItem->catalog_order_id = $this->id;
            $orderItem->catalog_sku_id = $id;
            $orderItem->catalog_id = $catalogSku->catalog_id;
        }
        $oldPrice = $orderItem->price;
        $oldQuant = $orderItem->quant;

        if ($catalogSku->price != 0) {
            $orderItem->price = $catalogSku->price;
        } else {
            $orderItem->price = $catalogSku->catalog->price;
        }
        $orderItem->quant += $quant;
        $orderItem->save();

        $this->updateItemCounters($orderItem->price, $oldPrice, $orderItem->quant, $oldQuant);

        return $orderItem;
    }

    /**
     * Обновляет количество товара в заказе с пересчетом итоговой стоимости заказа
     * @param integer $id - идентификатор товара <b>в заказе</b>
     * @param integer $quant - количество
     * @throws \yii\web\HttpException
     */
    public function edit($id, $quant)
    {
        $orderItem = CatalogOrderItem::findOne($id);
        if ($orderItem->catalog_order_id !== $this->id) {
            throw new \yii\web\HttpException(500, 'This product (ID:' . $id . ') belongs to a different order (ID:' . $orderItem->catalog_order_id . ')');
        }

        $oldPrice = $orderItem->price;
        $oldQuant = $orderItem->quant;
        if (!$orderItem->catalog_sku_id) {
            $catalog = $orderItem->catalog;
            $orderItem->price = $catalog->price;
        } else {
            $catalogSku = $orderItem->catalogSku;
            if ($catalogSku->price != 0) {
                $orderItem->price = $catalogSku->price;
            } else {
                $orderItem->price = $catalogSku->catalog->price;
            }
        }
        $orderItem->quant = $quant;
        $orderItem->save();

        $this->updateItemCounters($orderItem->price, $oldPrice, $orderItem->quant, $oldQuant);
    }

    /**
     * Удаление элемента из заказа с пересчетом итоговой стоимости и количества
     * @param integer $id - идентификатор товара в заказе
     * @throws \yii\web\HttpException
     */
    public function deleteItem($id)
    {
        $orderItem = CatalogOrderItem::findOne($id);

        if ($orderItem->catalog_order_id !== $this->id) {
            throw new \yii\web\HttpException(500, 'This product (ID:' . $id . ') belongs to a different order (ID:' . $orderItem->catalog_order_id . ')');
        }
        $oldPrice = $orderItem->price;
        $oldQuant = $orderItem->quant;
        $orderItem->delete();

        $this->updateItemCounters(0, $oldPrice, 0, $oldQuant);
    }

    /**
     * Изменение способа доставки у заказа и пересчет стоимости
     * @param integer $deliveryId - идентификатор способа доставки
     */
    public function changeDelivery($deliveryId)
    {
        $delivery = CatalogDelivery::findOne($deliveryId);

        $oldDeliveryPrice = $this->delivery_price;
        $this->catalog_delivery_id = $delivery->id;
        $this->delivery_price = $delivery->price;
        $this->total_price += ($delivery->price - $oldDeliveryPrice);
        $this->save();
    }

    /**
     * Изменине сопособа оплаты у заказа
     * @param integer $payId - идентификатор способа оплаты
     */
    public function changePay($payId)
    {
        $pay = CatalogPay::findOne($payId);
        $this->catalog_pay_id = $pay->id;
        $this->save();
    }

    /**
     * Отправка заказа
     */
    public function send()
    {
        $this->g_date = date('Y-m-d H:i:s');
        $this->catalog_order_status_id = self::STATUS_SEND;

        foreach ($this->orderItems as $orderItem) {
            $orderItem->serialize();
        }

        return $this->save();
    }

    public function beforeDelete()
    {
        $this->unlinkAll('orderItems');

        return parent::beforeDelete();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (isset($changedAttributes['catalog_order_status_id']) && $this->status !== $changedAttributes['catalog_order_status_id'] && $this->catalog_order_status_id != self::STATUS_NEW && $this->catalog_order_status_id != self::STATUS_SEND) {
            \app\components\Mailer::orderStatusChange($this->id, $this->user_email, $this->catalog_order_status_id);
        }
    }

}
