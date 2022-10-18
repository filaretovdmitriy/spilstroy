<?php

namespace app\controllers;

use app\components\Mailer;

class AjaxController extends \app\components\controller\Ajax
{


    public function actionCatalog_feedback()
    {
        $result = ['success' => false];
        $feedbackTxt = strip_tags(\Yii::$app->request->post('feedback'));
        $elemId = \Yii::$app->request->post('elemId');
        if (!empty($feedbackTxt) && !empty($elemId)) {
            $feedback = new \app\models\Feedback();
            $feedback->catalog_id = $elemId;
            $feedback->content = $feedbackTxt;
            $feedback->created_date = date('Y-m-d H:i:s');
            $feedback->status = \app\models\Feedback::STATUS_HIDDEN;
            $feedback->save();
            $result['success'] = true;
        }
        echo $result;
    }

    public function actionEdit_washlist()
    {
        if (\Yii::$app->user->isGuest) {
            throw new \yii\web\HttpException(403, 'Login please');
        }
        $user = \Yii::$app->user->identity;
        $goodId = \Yii::$app->request->post('goodId', false);
        $good = \app\models\Catalog::findOne($goodId);
        if (is_null($good)) {
            throw new \yii\web\HttpException(500, 'Good not found');
        }
        $isDelete = \Yii::$app->request->post('delete', false);

        $result = ['success' => true];

        $userWishIds = $user->getWishGoodsId();
        if (in_array($goodId, $userWishIds)) {
            $user->unlink('wish', $good);
            $result['delete'] = true;
        } elseif (!$isDelete) {
            $user->link('wish', $good);
            $result['delete'] = false;
        }

        return $result;
    }

    public function actionCancel_order()
    {
        $result = ['success' => true];
        $orderId = \Yii::$app->request->post('orderId', false);
        $order = \app\models\CatalogOrder::findOne($orderId);

        if (is_null($order)) {
            throw new \yii\web\HttpException(500, "Order (ID:{$orderId}) not found");
        }
        if (\Yii::$app->user->isGuest || $order->user_id !== \Yii::$app->user->id || !$order->status->can_cancel) {
            throw new \yii\web\HttpException(403, "You can't edit this order");
        }

        $order->catalog_order_status_id = \app\models\CatalogOrder::STATUS_USER_CANCEL;
        $order->save();
        $status = \app\models\CatalogOrderStatus::findOne(\app\models\CatalogOrder::STATUS_USER_CANCEL);
        $result['status'] = $status->name;

        return $result;
    }

    public function actionCall_back_send()
    {
        $form = new \app\widgets\back_call_popup\BackCallForm();
        $result = ['success' => false];
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            $result['success'] = true;
            $message = <<<HTML
<p><b>Имя: </b>{$form->name}</p>
<p><b>Телефон: </b>{$form->phone}</p>
HTML;
            if (!Mailer::send(null, 'Запрос обратного звонка', $message)) {
                $result['success'] = false;
                $result['error'] = 2;
            }
        } else {
            $result['error'] = 1;
        }

        return $result;
    }

}
