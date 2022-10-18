<?php
use app\modules\icms\widgets\grid\GridView;
?>

<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?= GridView::widget([
        'modelName' => 'app\models\Module',
        'tableName'=>'Список модулей',
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'format' => 'link',
                'label' => 'Название',
                'options' => [
                    'link' => ['developer/module_edit', 'id']
                ]
            ],
            [
                'attribute' => 'url',
                'format' => 'raw',
                'label' => 'Ссылка',
                'value' => function($model) {
                    return empty($model->url)?'---':yii\helpers\Html::a($model->url, '/' . $model->url);
                }
            ],
            [
                'attribute' => 'page.name_menu',
                'label' => 'Страница',
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'route',
                'label' => 'Роут',
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