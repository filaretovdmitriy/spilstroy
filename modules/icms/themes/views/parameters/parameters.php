<?php

use app\modules\icms\widgets\grid\GridView;
use yii\helpers\Html;
use app\components\IcmsHelper;
?>
<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?= GridView::widget([
        'modelName' => 'app\models\Parameter',
        'filterScenario' => 'filter',
        'tableName'=>'Параметры',
        'columns' => [
            'id',
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'name',
                'format' => 'link',
                'label' => 'Название',
                'options' => [
                    'link' => ['parameters/edit', 'id'],
                ]
            ],
            [
                'format' => 'raw',
                'label' => 'Значение',
                'value' => function ($data) {
                    switch ($data->type) {
                        case $data::TYPE_TEXT:
                            return Html::activeTextInput($data, 'value', ['class' => 'text']);
                        case $data::TYPE_HTML:
                            return 'Форматированный текст<br>Символов: ' . mb_strlen($data->value, 'utf-8');
                        case $data::TYPE_IMAGE:
                            return Html::img(IcmsHelper::getResizePath($data->getPath('value'), 50, 50, 3));
                        case $data::TYPE_FILE:
                            return Html::a('Загруженный файл', $data->getPath('value', $data::TYPE_FILE_FILE));
                        case $data::TYPE_BOOLEAN:
                            return $data->value == 1 ? 'Да' : 'Нет';
                        case $data::TYPE_MULTI:
                            $values = $data::getValue($data->id);
                            if (empty($values) === false) {
                                return implode(', ', $values);
                            } else {
                                return null;
                            }
                    }
                },
            ],
            [
                'class' => 'app\modules\icms\widgets\grid\GridFormatColumn',
                'attribute' => 'type',
                'label' => 'Тип',
                'format' => 'array',
                'options' => [
                    'items' => app\models\Parameter::getTypes()
                ]
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
                'delete' => Yii::$app->user->can('developer'),
                'save' => true
            ],
        ],
    ]) ?>
</div>
