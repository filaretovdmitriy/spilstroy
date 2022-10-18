<?php
use app\modules\icms\widgets\grid\GridView;
?>
<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?= GridView::widget([
        'modelName' => 'app\models\Prop',
        'tableName' => 'Свойства',
        'filterScenario' => 'filter',
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['catalog/prop_edit', 'id'],
                ]
            ],
            [
                'attribute' => 'group.name',
                'format' => 'html',
                'label' => 'Группа'
            ],
            [
                'attribute' => 'type.name',
                'format' => 'html',
                'label' => 'Тип',
            ],
            [
                'attribute' => 'is_most',
                'format' => 'html',
                'label' => 'Обязательно',
                'value' => function ($data) {
                    return $data->is_most?'Да':'Нет';
                },
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
                'delete' => Yii::$app->user->can('developer'),
                'view' => ['catalog/prop_edit', 'id'],
            ],
        ],
    ]);
    ?>

</div>
