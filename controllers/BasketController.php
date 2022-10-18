<?php

namespace app\controllers;

use app\models\CatalogSku;
use app\models\CatalogSkuPropsValue;
use yii\helpers\ArrayHelper;
use app\models\CatalogOrder;
use app\models\CatalogOrderItem;
use app\models\CatalogDelivery;
use app\models\CatalogPay;

class BasketController extends \app\components\controller\Frontend
{


    /*
     * Поиск id sku по свойствам и коду товара
     */
    public function actionGet_sku_id()
    {
        $result = ['success' => true];
        $elemId = \Yii::$app->request->post('elemId');
        if (is_null($elemId)) {
            throw new \yii\web\HttpException(500, 'Good ID not request');
        }
        $props = \Yii::$app->request->post('props', []);
        $catalogSku = CatalogSku::find();
        foreach ($props as $key => $value) {
            $elems = ArrayHelper::map(
                            CatalogSkuPropsValue::find()->andWhere('props_id=:props_id and value=:value', ['props_id' => $key, 'value' => $value]
                            )->all(), 'id', 'catalog_sku_id');
            $catalogSku->andFilterWhere(['in', 'id', $elems]);
        }
        $catalogSku->andFilterWhere(['=', 'catalog_id', $elemId]);
        $catalogSku->andFilterWhere(['=', 'status', 1]);
        $catalogSku = $catalogSku->one();
        if (!empty($catalogSku)) {
            $result['elemIdSku'] = $catalogSku->id;
        } else {
            $result['elemIdSku'] = 0;
        }
        echo json_encode($result);
    }

    /**
     * Добавление товара в корзину
     */
    public function actionAdd_to_cart()
    {
        $elemId = \Yii::$app->request->post('elemId');
        if (is_null($elemId)) {
            throw new \yii\web\HttpException(500, 'Good ID not request');
        }
        $elemSkuId = \Yii::$app->request->post('elemSkuId', false);
        $elemQuant = \Yii::$app->request->post('elemQuant', 1);

        $order = CatalogOrder::getUserOrder();

        if (\Yii::$app->user->id !== $order->user_id && \Yii::$app->session->id !== $order->session_id) {
            throw new \yii\web\HttpException(403, 'You can\'t edit this order');
        }

        if ($elemSkuId === false) {
            $orderItem = $order->add($elemId, false, $elemQuant);
        } else {
            $orderItem = $order->add($elemSkuId, true, $elemQuant);
        }

        return json_encode([
            'success' => true,
            'total_count' => $order->total_count,
            'total_price' => $order->total_price,
            'good_id' => $orderItem->id,
            'good_price' => $orderItem->price,
            'good_count' => $orderItem->quant,
            'good_summ' => $orderItem->price * $orderItem->quant,
            'delivery_id' => $order->catalog_pay_id,
            'delivery_id' => $order->catalog_delivery_id,
            'delivery_price' => $order->delivery_price,
        ]);
    }

    public function actionEdit_good_count()
    {
        $orderGoodId = \Yii::$app->request->post('orderGoodId');
        $newCount = \Yii::$app->request->post('newCount');

        $order = CatalogOrder::getUserOrder();

        if (is_null($order)) {
            throw new \yii\web\HttpException(500, 'Order not found');
        }

        if (\Yii::$app->user->id !== $order->user_id && \Yii::$app->session->id !== $order->session_id) {
            throw new \yii\web\HttpException(403, 'You can\'t edit this order');
        }

        $order->edit($orderGoodId, $newCount);
        $orderItem = CatalogOrderItem::findOne($orderGoodId);

        return json_encode([
            'success' => true,
            'total_count' => $order->total_count,
            'total_price' => $order->total_price,
            'good_id' => $orderItem->id,
            'good_price' => $orderItem->price,
            'good_count' => $orderItem->quant,
            'good_summ' => $orderItem->price * $orderItem->quant,
            'pay_id' => $order->catalog_pay_id,
            'delivery_id' => $order->catalog_delivery_id,
            'delivery_price' => $order->delivery_price,
        ]);
    }

    public function actionDelete_good()
    {
        $orderGoodId = \Yii::$app->request->post('orderGoodId');
        $order = CatalogOrder::getUserOrder();

        if (is_null($order)) {
            throw new \yii\web\HttpException(500, 'Order not found');
        }

        $order->deleteItem($orderGoodId);

        $result = [
            'success' => true,
            'total_count' => $order->total_count,
            'total_price' => $order->total_price,
            'pay_id' => $order->catalog_pay_id,
            'delivery_id' => $order->catalog_delivery_id,
            'delivery_price' => $order->delivery_price,
        ];

        if ($order->total_count <= 0) {
            $order->delete();
            $result['total_price'] = 0;
            $result['delivery_price'] = 0;
        }

        return json_encode($result);
    }

    public function actionChange_delivery()
    {
        $deliveryId = \Yii::$app->request->post('deliveryId');
        $order = CatalogOrder::getUserOrder();
        $delivery = CatalogDelivery::findOne($deliveryId);

        if (is_null($order) || is_null($delivery)) {
            throw new \yii\web\HttpException(500, 'Order or delivery not found');
        }

        if (\Yii::$app->user->id !== $order->user_id && \Yii::$app->session->id !== $order->session_id) {
            throw new \yii\web\HttpException(403, 'You can\'t edit this order');
        }

        $order->changeDelivery($deliveryId);

        return json_encode([
            'success' => true,
            'total_count' => $order->total_count,
            'total_price' => $order->total_price,
            'pay_id' => $order->catalog_pay_id,
            'delivery_id' => $order->catalog_delivery_id,
            'delivery_price' => $order->delivery_price,
        ]);
    }

    public function actionChange_pay()
    {
        $payId = \Yii::$app->request->post('payId');
        $order = CatalogOrder::getUserOrder();
        $pay = CatalogPay::findOne($payId);

        if (is_null($order) || is_null($pay)) {
            throw new \yii\web\HttpException(500, 'Order or delivery not found');
        }

        if (\Yii::$app->user->id !== $order->user_id && \Yii::$app->session->id !== $order->session_id) {
            throw new \yii\web\HttpException(403, 'You can\'t edit this order');
        }

        $order->changePay($payId);

        return json_encode([
            'success' => true,
            'total_count' => $order->total_count,
            'total_price' => $order->total_price,
            'pay_id' => $order->catalog_pay_id,
            'delivery_id' => $order->catalog_delivery_id,
            'delivery_price' => $order->delivery_price,
        ]);
    }

    public function actionBasket()
    {

        $order = CatalogOrder::getUserOrder(true);
        $order->setScenario('send');

        if ($order->load(\Yii::$app->request->post()) && $order->send()) {
            \app\components\Mailer::orderSend($order);

            if (\Yii::$app->user->isGuest === false && \Yii::$app->request->post('save_user_info', false)) {
                $user = \app\models\User::findOne(\Yii::$app->user->id);
                $user->name = $order->user_name;
                $user->phone = $order->user_phone;

                if ($order->delivery->have_address === 1) {
                    $user->city = $order->user_city;
                    $user->street = $order->user_street;
                    $user->home = $order->user_home;
                }
                $user->save(false);
            }

            \Yii::$app->session->setFlash('ORDER_SEND');
            return $this->redirect([$this->route]);
        }

        $deliverys = CatalogDelivery::find()->andWhere(['status' => CatalogDelivery::STATUS_ACTIVE])->orderBy(['sort' => SORT_ASC])->all();
        $pays = CatalogPay::find()->andWhere(['status' => CatalogPay::STATUS_ACTIVE])->orderBy(['sort' => SORT_ASC])->all();

        $goods = $order->getGoods();

        return $this->render('basket', [
                    'order' => $order,
                    'goods' => $goods,
                    'deliverys' => $deliverys,
                    'pays' => $pays,
                    'page' => $this->view->tree
        ]);
    }

}
