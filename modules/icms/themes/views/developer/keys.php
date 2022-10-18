<?php
use app\modules\icms\widgets\grid\GridViewTree;
?>

<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?= GridViewTree::widget([
        'modelName' => 'app\models\Key',
        'filterScenario' => 'filter',
        'tableName' => 'Список ключей',
        'treeField' => 'keys',
        'columns' => [
            'id',

            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'level',
                'format' => 'repeat',
                'label' => '',
                'options' => [
                    'text' => '***'
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'format' => ['link', 'prefix'],
                'label' => 'Ключ (название)',
                'options' => [
                    'link' => ['developer/key_edit', 'id'],
                    'text' => '&nbsp;&nbsp;&nbsp;&nbsp;',
                    'countField' => 'level'
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'value',
                'label' => 'Значение',
                'format' => 'input'
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
                'save' => true
            ],
            ],
        ]) ?>
</div>