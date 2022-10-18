<?php
use app\modules\icms\widgets\grid\GridView;
use yii\helpers\Html;
?>
<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => 'app\models\User',
        'tableName'=>'Пользователи',
        'filterScenario' => 'filter',
        'conditions' => Yii::$app->user->can('developer')?[]:['NOT IN', 'id',Yii::$app->authManager->getUserIdsByRole('developer')],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'login',
                'label' => 'Логин',
                'format' => 'link',
                'options' => [
                    'link' => ['users/edit', 'id'],
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Имя'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'email',
                'label' => 'E-mail'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'label' => 'Роль',
                'attribute' => 'role',
                'format' => 'raw',
                'function' => function($data) {
                    $rolesName = [];
                    foreach ($data->roles as $role) {
                        if (empty($role->description) === false) {
                            $rolesName[] = $role->description;
                        }
                    }
                    return implode(', ', $rolesName);
                }
                
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
                'format' => 'raw',
                'label' => 'Пароль',
                'value' => function ($data) {
                    return Html::a('Редактировать пароль', ['users/edit_password', 'id' => $data->id]);
                },
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridActionColumn',
                'delete' => function($model) {
                    return Yii::$app->getUser()->identity->id != $model->id;
                },
            ],
        ],
    ]) ?>
</div>
