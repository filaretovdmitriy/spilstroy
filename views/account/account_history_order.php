<?php
/* @var $this app\components\View */

use yii\helpers\Url;
use yii\helpers\Html;
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
            <h2>Заказ №<?=$order->id?></h2>
            <table class="table table-hover table-bordered table-striped">
                <tbody>
                <tr>
                    <td>Создание заказа</td>
                    <td><?= date('d.m.Y H:i:s',strtotime($order->g_date)) ?></td>
                </tr>
                <tr>
                    <td>Количество товара, шт</td>
                    <td><?= $order->total_count ?></td>
                </tr>

                <tr>
                    <td>Сумма заказа, р</td>
                    <td><?= number_format($order->total_price, 2, '.', ' ') ?></td>
                </tr>
                <tr>
                    <td>Статус</td>
                    <td><?php  echo $order->status->name ?></td>
                </tr>
                </tbody>
            </table>
            <h2 class="padd">Состав заказа</h2>
            <table class="table table-hover table-bordered table-striped">
                <thead>
                <tr>
                    <th>
                        Id
                    </th>
                    <th>
                        Наименование
                    </th>
                    <th>
                        Свойства
                    </th>
                    <th>
                        Цена, р
                    </th>
                    <th>
                        Количество, шт.
                    </th>
                    <th>
                        Сумма, р
                    </th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderGoods as $good) { ?>
                    <tr>
                        <td><?= $good->id ?></td>
                        <td>
                            <?= Html::a($good->name, $good->url) ?>
                            <?php if (!empty($good->article)) { ?>
                                <br>
                                <span><?= $good->article ?></span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php foreach ($good->sku as $skuId => $skuProps) { ?>
                                <?= $skuProps['name'] ?>: <?= $skuProps['value'] ?><br>
                            <?php } ?>
                        </td>
                        <td><?= number_format($good->price, 2, '.', ' ') ?></td>
                        <td><?= $good->quant ?></td>
                        <td><?= number_format($good->summ, 2, '.', ' ') ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p><a href="<?= Url::to(['account/account_history']) ?>">Все заказы</a></p>
        </div>
    </div>
    <!-- End Profile Content -->
</div>

