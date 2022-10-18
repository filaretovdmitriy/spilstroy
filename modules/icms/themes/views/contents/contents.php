<?php
use app\modules\icms\widgets\grid\GridView;
?>
<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => 'app\models\Content',
        'filterScenario' => 'filter',
        'tableName'=>'Элементы',
        'relations' => ['content_categorie_id' => 'id'],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['contents/content_edit', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'g_date',
                'label' => 'Дата',
                'format' => 'date'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'status',
                'format' => 'select',
                'label' => 'Статус',
                'contentOptions' => ['class' => 'width-200'],
                'options' => [
                    'items' => app\models\Content::getStatuses()
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'sort',
                'format' => 'input',
                'contentOptions' => ['class' => 'width-120'],
                'label' => 'Сортировка',
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
    ]); ?>
</div>
