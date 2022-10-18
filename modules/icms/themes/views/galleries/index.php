<?php
use app\modules\icms\widgets\grid\GridView;
?>
<div class="data">

    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => 'app\models\Gallerycategorie',
        'filterScenario' => 'filter',
        'tableName'=>'Галереи',
        'relations' => ['pid' => 'id'],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['galleries/index', 'id'],
                    'is-pjax' => true,
                ]
            ],

            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'status',
                'label' => 'Статус',
                'format' => 'select',
                'contentOptions' => ['class' => 'width-200'],
                'options' => [
                    'items' => app\models\GalleryCategorie::getStatuses()
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'sort',
                'label' => 'Сортировка',
                'contentOptions' => ['class' => 'width-120'],
                'format' => 'input'
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
                'save' => true,
                'view' => ['galleries/categorie_edit', 'id']
            ],
        ],
    ]);
    ?>
<div class="clear"></div>
    <?=
    GridView::widget([
        'modelName' => 'app\models\Gallery',
        'filterScenario' => 'filter',
        'tableName'=>'Изображения',
        'relations' => ['gallery_categorie_id' => 'id'],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['galleries/gallery_edit', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'image',
                'label' => 'Изображение',
                'format' => 'img',
                'options'=>[
                    'resize'=>['type' => 3]
                ]

            ],

            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'status',
                'label' => 'Статус',
                'format' => 'select',
                'contentOptions' => ['class' => 'width-200'],
                'options' => [
                    'items' => app\models\Gallery::getStatuses()
                ],
                'filter' => app\models\Gallery::getStatuses()
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
                'save' => true,
            ],
        ],
    ]);
    ?>

</div>
