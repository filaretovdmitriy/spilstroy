<?php
/* @var $this app\components\View */

use yii\helpers\Url;
?>

<div class="row">
    <!--Left Sidebar-->
    <div class="col-md-3 md-margin-bottom-40">
        <ul class="list-group sidebar-nav-v1 margin-bottom-40" id="sidebar-nav-1">
            <li class="list-group-item ">
                <a href="<?= Url::to(['account/account']) ?>"><i class="fa fa-bar-chart-o"></i> Аккаунт</a>
            </li>
            <li class="list-group-item active">
                <a href="<?= Url::to(['account/account_history']) ?>"><i class="fa fa-history"></i> История заказов</a>
            </li>
        </ul>


    </div>
    <!--End Left Sidebar-->

    <!-- Profile Content -->
    <div class="col-md-9">
        <div class="profile-body margin-bottom-20 log-reg-v3">
            <h2><?= $this->h1 ?></h2>
            <?php if (!empty($orders)) { ?>
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>
                            Заказ
                        </th>
                        <th>
                            Дата создания
                        </th>
                        <th>
                            Кол-во, шт.
                        </th>
                        <th>
                            Сумма, р.
                        </th>
                        <th>
                            Статус
                        </th>
                        <th>

                        </th>
                        <th>

                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($orders as $order){ ?>
                        <tr id="order-<?= $order->id ?>">
                            <td>
                                №<?=$order->id?>
                            </td>
                            <td>
                                <?=date('d.m.Y H:i',strtotime($order->g_date))?>
                            </td>
                            <td>
                                <?=$order->total_count?>
                            </td>
                            <td>
                                <?= number_format($order->total_price, 0, '.', ' ') ?>
                            </td>
                            <td class="order-status">
                                <?=$order->status->name?>
                            </td>
                            <td>
                                <?php if ($order->status->can_cancel) { ?>
                                    <a href="javascript:;" class="text-danger cancel-order" data-id="<?= $order->id ?>">Отменить заказ</a>
                                <?php } ?>
                            </td>
                            <td>
                               <a href="<?= Url::to(['account/account_history_order', 'order_id' => $order->id]) ?>">Подробнее</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    </table>
            <?php } else { ?>
                <div class="alert alert-info fade in">
                    <strong>Ваш список заказов пуст.</strong> Вы еще не отправляли ни одного заказа
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- End Profile Content -->
</div>

