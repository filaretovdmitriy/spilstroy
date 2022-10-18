<?php
use app\modules\icms\widgets\grid\GridViewTree;
?>
<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridViewTree::widget([
        'modelName' => 'app\models\Tree',
        'tableName'=>'Структура',
        'ignoreKey' => 1,
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
                'attribute' => 'name_menu',
                'format' => ['link', 'prefix'],
                'label' => 'Название для меню',
                'options' => [
                    'link' => ['structure/edit', 'id'],
                    'text' => '&nbsp;&nbsp;&nbsp;&nbsp;',
                    'countField' => 'level',
                    'skipFirst' => true,
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'url',
                'format' => 'link',
                'label' => 'Адрес',
                'options' => [
                    'link' => '',
                    'addField' => 'url',
                    'data-pjax' => 0
                ]
            ],

            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'status',
                'label' => 'Статус',
                'format' => 'select',
                'contentOptions' => ['class' => 'width-200'],
                'options' => [
                    'items' => app\models\Tree::getStatuses()
                ],
                'filter' => app\models\Tree::getStatuses()
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'sort',
                'label' => 'Сортировка',
                'format' => 'input',
                'contentOptions' => ['class' => 'width-120'],

            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                'delete' => true,
                'deleteSafe' => 'is_safe',
                'save' => true,
                'view' => ['structure/edit', 'id']
            ]
        ]
    ]); ?>
</div>