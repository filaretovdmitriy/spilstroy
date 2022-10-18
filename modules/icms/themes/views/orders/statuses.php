<?php
use app\modules\icms\widgets\grid\GridView;
use app\models\CatalogOrderStatus;
use app\models\CatalogOrder;
?>

<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => CatalogOrderStatus::class,
        'filterScenario' => 'filter',
        'tableName'=>'Статусы заказа',
        'conditions'=>['!=', 'id', CatalogOrder::STATUS_NEW],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['orders/status_edit', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'can_cancel',
                'label' => 'Можно отменить?',
                'format' => 'checkbox'
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