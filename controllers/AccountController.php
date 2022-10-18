<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\CatalogOrder;
use app\forms\LoginForm;
use app\forms\SignupForm;
use app\forms\LostPasswordForm;

class AccountController extends \app\components\controller\Frontend
{

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionRegistration()
    {

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {

            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionAccount()
    {
        if (!\Yii::$app->user->isGuest) {

            $user = User::findIdentity(\Yii::$app->user->id);
            $userInfo = User::findOne(\Yii::$app->user->id);
            $user->scenario = "updateAccount";
            $UP = Yii::$app->request->post('User');
            if (($UP['password'] == "") && ($UP['password_repeat'] == "")) {
                $user->scenario = "updateAccountNP";
            }
            if ($user->load(Yii::$app->request->post()) && $user->validate()) {
                $user->save();
            }

            return $this->render('account', ['model' => $user, 'userInfo' => $userInfo]);
        } else {
            return $this->goHome();
        }
    }

    public function actionAccount_history()
    {
        if (!\Yii::$app->user->isGuest) {

            $orders = CatalogOrder::find()->andWhere('user_id=:user_id', ['user_id' => Yii::$app->user->id])
                            ->andWhere('catalog_order_status_id<>:catalog_order_status_id', ['catalog_order_status_id' => 3])
                            ->orderBy('g_date desc')->all();

            return $this->render('account_history', ['orders' => $orders]);
        } else {
            return $this->goHome();
        }
    }

    public function actionAccount_history_order()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $order_id = \Yii::$app->request->get('order_id');

        $order = CatalogOrder::findOne($order_id);
        if ($order->user_id != Yii::$app->user->id) {
            return $this->goHome();
        }

        $orderGoods = $order->getGoods();
        return $this->render('account_history_order', [
                    'order' => $order,
                    'orderGoods' => $orderGoods
        ]);
    }

    public function actionLost_password()
    {
        $model = new LostPasswordForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->changePassword();
            \Yii::$app->getSession()->setFlash('complite', 'Новый пароль выслан Вам на email');
        }
        return $this->render('lost_password', [
                    'model' => $model,
        ]);
    }

    public function actionWishlist()
    {
        if (Yii::$app->user->isGuest) {
            throw new \yii\web\HttpException(403, 'Только для зарегистрированных пользователей');
        }
        $goods = Yii::$app->user->identity->getWishList();
        return $this->render('wishlist', [
                    'goods' => $goods
        ]);
    }

}
