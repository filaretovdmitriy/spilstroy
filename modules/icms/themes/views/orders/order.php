<?php
use app\modules\icms\widgets\drop_down_list\DropDownList;
?>
<?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'save']) ?>
<div class="data ">
    <?= \yii\helpers\Html::beginForm('/icms/orders/' . $order->id,'post');?>
    <h2 class="padd">Заказ № <?= $order->id ?></h2>
    <table class="width-100 table-striped table-vertical">
        <tbody>
        <tr>
            <td>Дата cоздания заказа</td>
            <td><?= date('d.m.Y H:i:s',strtotime($order->g_date)) ?></td>
        </tr>
        <tr>
            <td>Покупатель</td>
            <td><?= $order->user_name ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= $order->user_email ?></td>
        </tr>
        <tr>
            <td>Телефон</td>
            <td><?= $order->user_phone ?></td>
        </tr>
        <?php if ($order->delivery->have_address) { ?>
        <tr>
            <td>Адрес</td>
            <td>
                г. <?= $order->user_city ?>,
                <?= $order->user_street ?><?= !empty($order->user_home)?', ' . $order->user_home:'' ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td>Доставка</td>
            <td><?= $order->delivery->name ?> <?= !empty($order->delivery->price)?"({$order->delivery->price} руб.)":'' ?></td>
        </tr>
        <tr>
            <td>Оплата</td>
            <td><?= $order->pay->name ?></td>
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
            <td><?= DropDownList::widget(['width'=>'300px','name'=>'orderStatus','items'=>$order::getStatuses(),'selection'=>$order->catalog_order_status_id]) ?></td>
        </tr>
        </tbody>
    </table>
    <h2 class="padd">Состав заказа</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Изображение</th>
                <th>Наименование</th>
                <th>SKU</th>
                <th>Цена, р</th>
                <th>Количество, шт.</th>
                <th>Сумма, р</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderGoods as $good) { ?>
            <tr>
                <td><?= $good->id ?></td>
                <td>
                    <?php if (empty($good->image) === false) { ?>
                    <img src="<?= $good->getResizePath('image') ?>">
                    <?php } ?>
                </td>
                <td>
                    <a href="<?= yii\helpers\Url::to(['catalog/good_edit', 'id' => $good->id]) ?>"><?= $good->name ?></a>
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
    <div class="action_buttons">
    <?= \yii\helpers\Html::submitButton('Сохранить',['class'=>'save']);?>
    </div>
    <?= \yii\helpers\Html::endForm();?>
</div>