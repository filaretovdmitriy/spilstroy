<?php

use app\modules\icms\widgets\grid\GridView;
?>
<div class="data">

    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => 'app\models\Map',
        'filterScenario' => 'filter',
        'tableName'=>'Карты',
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['maps/marks', 'map_id' => 'id'],
                    'is-pjax' => true,
                ]
            ],
            [
                'attribute' => 'mark_count',
                'format' => 'HTML',
                'label' => 'Количество меток'
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
                'view' => ['maps/map_edit', 'id']
            ],
        ],
    ]);
            ?>

</div>