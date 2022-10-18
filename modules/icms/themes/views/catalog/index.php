<?php
use app\modules\icms\widgets\grid\GridView;
?>
<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?=
    GridView::widget([
        'modelName' => 'app\models\CatalogCategorie',
        'filterScenario' => 'filter',
        'tableName' => 'Категории',
        'relations' => ['pid' => 'id'],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['catalog/index', 'id'],
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
                    'items' => app\models\CatalogCategorie::getStatuses()
                ],
                'filter' => app\models\CatalogCategorie::getStatuses()
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
                'view' => ['catalog/categorie_edit', 'id']
            ],
        ],
    ]);
    ?>
<div class="clear"></div>
    <?=
    GridView::widget([
        'modelName' => 'app\models\Catalog',
        'filterScenario' => 'filter',
        'tableName' => 'Товары',
        'relations' => ['catalog_categorie_id' => 'id'],
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'label' => 'Название',
                'format' => 'link',
                'options' => [
                    'link' => ['catalog/catalog_edit', 'id']
                ]
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'price',
                'label' => 'Цена, р',
                'format' => 'number'
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
                    'items' => app\models\Catalog::getStatuses()
                ],
                'filter' => app\models\Catalog::getStatuses()
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
    ]);
    ?>

</div>
