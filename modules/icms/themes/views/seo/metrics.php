<?php

use app\modules\icms\widgets\grid\GridView;
use yii\widgets\Pjax;
use app\models\Key;
?>
<div class="data">
    <?php Pjax::begin(['id' => 'user', 'options' => ['class' => 'pjax-wraper']]) ?>
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => Key::class,
        'filterScenario' => 'filter',
        'tableName'=>'Метрики',
        'conditions' => ['pid' => 12],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'format' => 'input',
                'label' => 'Название'
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'value',
                'label' => 'Код метрики',
                'format' => 'textarea',
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
    ]);
    ?>
    <?php Pjax::end() ?>
</div>
