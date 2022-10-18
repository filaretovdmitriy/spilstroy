<?php
use app\modules\icms\widgets\grid\GridView;
use app\models\Slide;
?>
<div class="data">

    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => Slide::class,
        'filterScenario' => 'filter',
        'tableName'=>'Слайды',
        'relations' => ['slider_id'],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'format' => 'link',
                'label' => 'Название',
                'options' => [
                    'link' => ['sliders/slide_edit', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'image',
                'format' => 'img',
                'label' => 'Изображение',
                'options' => ['resize' => ['type' => 3]]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'status',
                'format' => 'select',
                'label' => 'Статус',
                'contentOptions' => ['class' => 'width-200'],
                'options' => [
                    'items' => Slide::getStatuses()
                ],
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'sort',
                'format' => 'input',
                'label' => 'Сортировка',
                'contentOptions' => ['class' => 'width-120'],
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