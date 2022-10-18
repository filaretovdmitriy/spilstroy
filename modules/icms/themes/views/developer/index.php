<?php
use app\modules\icms\widgets\grid\GridViewTree;
?>

<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridViewTree::widget([
        'modelName' => 'app\models\AdminMenu',
        'filterScenario' => 'filter',
        'tableName'=>'Меню ICMS',
        'showChildren' => false,
        'treeField' => 'childrens',
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
                'attribute' => 'title',
                'format' =>  ['link', 'prefix'],
                'label' => 'Заголовок',
                'options' => [
                    'link' => ['developer/menu_edit', 'id'],
                    'text' => '&nbsp;&nbsp;&nbsp;&nbsp;',
                    'countField' => 'level',
                    'skipFirst' => true,
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'route',
                'label' => 'Роут',
            ],
            [
                'attribute' => 'role',
                'format' => 'raw',
                'label' => 'Доступ',
                'value' => function($data) {
                    $role = Yii::$app->authManager->getRole($data->role);
                    if (!is_null($role)) {
                        return $role->description;
                    } else {
                        return 'Все';
                    }
                }
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'isActive',
                'label' => 'Включен',
                'format' => 'checkbox'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'in_button',
                'label' => 'В кнопке',
                'format' => 'checkbox'
            ],
            [
                'attribute' => 'parentName',
                'format' => 'html',
                'label' => 'Имя родителя (GET)'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'sort',
                'label' => 'Сортировка',
                'contentOptions' => ['class' => 'width-120'],
                'format' => 'input'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                'delete' => true,
                'save' => true
            ],
            ],
        ]) ?>
</div>