<?php
use app\modules\icms\widgets\grid\GridView;
?>
<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => 'app\models\PropsGroup',
        'tableName' => 'Группы свойств',
        'filterScenario' => 'filter',
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['catalog/prop_group_edit', 'id'],
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'alias',
                'label' => 'Алиас',
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'created_at',
                'label' => 'Дата создания',
                'format' => 'date'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'updated_at',
                'label' => 'Дата изменения',
                'format' => 'date'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                'delete' => true,
                'view' => ['catalog/prop_group_edit', 'id'],
            ],
        ],
    ]);
    ?>
</div>
