<?php
use app\modules\icms\widgets\grid\GridView;
use app\models\Feedback;
?>
<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?= GridView::widget([
        'modelName' => Feedback::class,
        'filterScenario' => 'filter',
        'tableName'=>'Отзывы',
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'ФИО',
                'format' => 'link',
                'options' => [
                    'link' => ['feedbacks/edit', 'id'],
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'content',
                'label' => 'Текст',
                'format' => ['cut', 'nl2br'],
                'options' => [
                    'length' => 50
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'catalog_id',
                'label' => 'К товару',
                'format' => 'array',
                'options' => [
                    'items' => app\models\Catalog::getNamesAsArray()
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'created_date',
                'label' => 'Дата создания (для отображения)',
                'format' => 'date'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'created_at',
                'label' => 'Дата создания',
                'format' => 'date'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'status',
                'label' => 'Статус',
                'contentOptions' => ['class' => 'width-200'],
                'format' => 'select',
                'options' => [
                    'items' => Feedback::getStatuses()
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                'delete' => true,
                'save' => true,
                'view' => ['feedbacks/edit', 'id'],
            ],
        ],
    ]) ?>
</div>
