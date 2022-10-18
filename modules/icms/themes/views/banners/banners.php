<?php
use app\modules\icms\widgets\grid\GridView;
use app\models\Banner;
?>
<div class="data">

    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => Banner::class,
        'filterScenario' => 'filter',
        'tableName'=>'Баннеры',
        'relations' => ['banner_group_id' => 'id'],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'format' => 'link',
                'label' => 'Название',
                'options' => [
                    'link' => ['banners/banner_edit', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'status',
                'format' => 'select',
                'label' => 'Статус',
                'options' => [
                    'items' => Banner::getStatuses()
                ],
                'filter' => Banner::getStatuses()
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'sort',
                'format' => 'input',
                'label' => 'Сортировка',
                'contentOptions' => ['class' => 'width-120'],
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
