<?php
use app\modules\icms\widgets\grid\GridView;
?>
<div class="data">

    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => 'app\models\CatalogDelivery',
        'filterScenario' => 'filter',
        'tableName'=>'Доставка',
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['orders/delivery_edit', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'price',
                'label' => 'Стоимость',
                'format' => 'number'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'have_address',
                'label' => 'Нужен адрес?',
                'format' => 'checkbox'
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
                    'items' => app\models\CatalogDelivery::getStatuses()
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