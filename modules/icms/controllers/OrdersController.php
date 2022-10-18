<?php

namespace app\modules\icms\controllers;

use app\models\CatalogOrder;
use app\models\CatalogOrderStatus;
use Yii;
use yii\filters\AccessControl;
use app\models\CatalogDelivery;
use app\models\CatalogPay;
use app\modules\icms\widgets\GreenLine;

class OrdersController extends \app\components\controller\Backend
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['title' => 'Заказы'],
        ];
        return $this->render('index');
    }

    public function actionOrder($id)
    {
        $this->layout = "innerPjax";
        $order = CatalogOrder::findOne($id);

        if (is_null($order) === true) {
            throw new \yii\web\NotFoundHttpException();
        }

        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['orders/index'], 'title' => 'Заказы'],
            ['title' => 'Заказ №' . $order->id],
        ];
        $newStatus = \Yii::$app->request->post('orderStatus', false);
        if ($newStatus !== false && CatalogOrderStatus::find()->andWhere(['id' => $newStatus])->exists() === true) {
            $order->catalog_order_status_id = $newStatus;
            $order->save();
            GreenLine::show();
            Yii::$app->session->setFlash('save', 'Cохранено');
            return $this->refresh();
        }
        $orderGoods = $order->getGoods();
        return $this->render('order', [
                    'order' => $order,
                    'orderGoods' => $orderGoods
        ]);
    }

    public function actionDeliverys()
    {
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['orders/index'], 'title' => 'Заказы'],
            ['title' => 'Способы доставки'],
        ];

        return $this->render('deliverys');
    }

    public function actionDelivery_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['orders/index'], 'title' => 'Заказы'],
            ['url' => ['orders/deliverys'], 'title' => 'Способы доставки'],
            ['title' => 'Добаление нового способа доставки'],
        ];
        $model = new CatalogDelivery();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->redirect(['orders/delivery_edit', 'id' => $model->id]);
        }

        return $this->render('delivery', ['model' => $model]);
    }

    public function actionDelivery_edit($id)
    {
        $delivery = CatalogDelivery::findOne($id);
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['orders/index'], 'title' => 'Заказы'],
            ['url' => ['orders/deliverys'], 'title' => 'Способы доставки'],
            ['title' => 'Редактировать'],
        ];
        if (is_null($delivery) === true) {
            return $this->redirect(['orders/delivery_add']);
        }
        if ($delivery->load(Yii::$app->request->post()) && $delivery->save()) {
            GreenLine::show();
            return $this->refresh();
        }
        return $this->render('delivery', ['model' => $delivery]);
    }

    public function actionPays()
    {
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['orders/index'], 'title' => 'Заказы'],
            ['title' => 'Способы оплаты'],
        ];

        return $this->render('pays');
    }

    public function actionPay_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['orders/index'], 'title' => 'Заказы'],
            ['url' => ['orders/pays'], 'title' => 'Способы оплаты'],
            ['title' => 'Добаление нового способа оплаты'],
        ];
        $model = new CatalogPay();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->redirect(['orders/pay_edit', 'id' => $model->id]);
        }

        return $this->render('pay', ['model' => $model]);
    }

    public function actionPay_edit($id)
    {
        $pay = CatalogPay::findOne($id);
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['orders/index'], 'title' => 'Заказы'],
            ['url' => ['orders/pays'], 'title' => 'Способы оплаты'],
            ['title' => 'Редактировать'],
        ];
        if (is_null($pay) === true) {
            return $this->redirect(['orders/pay_add']);
        }
        if ($pay->load(Yii::$app->request->post()) && $pay->save()) {
            GreenLine::show();
            return $this->refresh();
        }
        return $this->render('pay', ['model' => $pay]);
    }

    public function actionStatuses()
    {
        $this->layout = 'innerPjax';
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['orders/index'], 'title' => 'Заказы'],
            ['title' => 'Статусы заказа'],
        ];

        return $this->render('statuses');
    }

    public function actionStatus_add()
    {
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['orders/index'], 'title' => 'Заказы'],
            ['url' => ['orders/statuses'], 'title' => 'Статусы заказа'],
            ['title' => 'Добаление нового статуса заказа'],
        ];
        $model = new CatalogOrderStatus();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            GreenLine::show();
            return $this->redirect(['orders/status_edit', 'id' => $model->id]);
        }

        return $this->render('status', ['model' => $model]);
    }

    public function actionStatus_edit($id)
    {
        $status = CatalogOrderStatus::findOne($id);
        Yii::$app->view->params['breadCrumbs']['crumbs'] = [
            ['url' => ['orders/index'], 'title' => 'Заказы'],
            ['url' => ['orders/statuses'], 'title' => 'Статусы заказа'],
            ['title' => 'Редактировать'],
        ];
        if (is_null($status) === true) {
            return $this->redirect(['orders/status_add']);
        }
        if ($status->load(Yii::$app->request->post()) && $status->save()) {
            GreenLine::show();
            return $this->refresh();
        }
        return $this->render('status', ['model' => $status]);
    }

}
