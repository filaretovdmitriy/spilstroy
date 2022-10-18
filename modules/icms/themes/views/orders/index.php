<?php

use app\modules\icms\widgets\grid\GridView;
use app\models\CatalogOrder;
?>
<div class="data">

    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => CatalogOrder::class,
        'filterScenario' => 'filter',
        'tableName'=>'Заказы',
        'conditions'=>['!=', 'catalog_order_status_id', CatalogOrder::STATUS_NEW],
        'columns' => [
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'id',
                'label' => 'Заказ',
                'format' => 'link',
                'options' => [
                    'link' => ['orders/order', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'user_name',
                'label' => 'Заказчик',
                'format' => 'link',
                'options' => [
                    'link' => ['orders/order', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'total_count',
                'label' => 'Кол-во, шт',
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'total_price',
                'label' => 'Сумма, р',
                'format' => 'number'
            ],


            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'created_at',
                'label' => 'Дата создания',
                'format' => 'date'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'catalog_order_status_id',
                'label' => 'Статус',
                'contentOptions' => ['class' => 'width-200'],
                'format' => 'select',
                'options' => [
                    'items' => app\models\CatalogOrder::getStatuses()
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                'delete' => true,
                'save' => true,
                'view' => ['orders/order', 'id'],
            ],
        ],
    ]);
            ?>

</div>