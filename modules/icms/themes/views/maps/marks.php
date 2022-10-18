<?php
use app\modules\icms\widgets\grid\GridView;
?>
<div class="data">

    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => 'app\models\MapMark',
        'filterScenario' => 'filter',
        'tableName'=>'Метки',
        'relations' => ['map_id'],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['maps/mark_edit', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'image',
                'format' => 'img',
                'label' => 'Изображение',
                'options' => [
                    'resize' => ['type' => 3]
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'status',
                'format' => 'select',
                'label' => 'Статус',
                'contentOptions' => ['class' => 'width-200'],
                'options' => [
                    'items' => \app\models\MapMark::getStatuses()
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'created_at',
                'label' => 'Дата создания',
                'visible' => Yii::$app->user->can('developer'),
                'format' => 'date'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'updated_at',
                'label' => 'Дата изменения',
                'visible' => Yii::$app->user->can('developer'),
                'format' => 'date'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                'delete' => true,
                'save' => true
            ],
        ],
    ]);
            ?>

</div>