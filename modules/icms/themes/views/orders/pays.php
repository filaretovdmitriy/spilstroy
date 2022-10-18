<?php
use app\modules\icms\widgets\grid\GridView;
use app\models\CatalogPay;

?>
<div class="data">

    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => CatalogPay::class,
        'filterScenario' => 'filter',
        'tableName'=>'Оплаты',
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['orders/pay_edit', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'is_default',
                'label' => 'По умолчанию',
                'format' => 'radio'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'status',
                'label' => 'Статус',
                'format' => 'select',
                'options' => [
                    'items' => CatalogPay::getStatuses()
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'sort',
                'label' => 'Сортировка',
                'format' => 'input'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                'delete' => Yii::$app->user->can('developer'),
                'save' => true,
            ],
        ],
    ]);
            ?>

</div>