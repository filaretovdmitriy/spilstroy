<?php

use app\modules\icms\widgets\drop_down_list\DropDownList;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use app\modules\icms\assets\IcmsAsset;
?>
<fieldset>
    <div>
        <label>Добавить товар</label>
        <?=
        DropDownList::widget([
            'items' => ['Выберите товар'],
            'name' => 'related',
            'options' => ['data-good-id' => $model->id],
            'parameters' => [
                'ajax' => [
                    'url' => yii\helpers\Url::to(['catalogajax/search_goods']),
                    'dataType' => 'json',
                    'delay' => 350,
                    'data' => new JsExpression(<<<JS
                function (params) {
                  console.log(params);
                  return {q: params.term, current: {$model->id}};
                }
JS
                    ),
                    'processResults' => new JsExpression(<<<JS
                function (data, params) {
                    return {results: data.items};
                }
JS
                    ),
                    'cache' => false
                ],
                'minimumInputLength' => 2,
                'escapeMarkup' => new JsExpression(<<<JS
            function (markup) { return markup; }
JS
                ),
                'templateResult' => new JsExpression(<<<JS
            function (item) {
                if (item.loading || item.children) return item.text;

                if (!item.image) {
                    return "<div class='good-option-select2'><span>" + item.text + "</span></div>";
                }
                return "<div class='good-option-select2'><img src='" + item.image + "'>" + item.text + "</div>";
            }
JS
                ),
            ],
            'clientEvents' => [
                'select' => <<<JS
            goodId = $(this).data('good-id');
            relatedId = $(this).val();
            $('#preloader').show();
            $.post('/icms/catalogajax/add_related_good', {goodId: goodId, relatedId: relatedId}, function(data) {
                if (data.success) {
                    $.pjax.reload('#relaitedGoods');
                    
                }
            }, 'json');
            $(this).val(0).trigger("change");
JS
            ]
        ]);
        ?>
    </div>
</fieldset>
<div>
    <h2>Сопуствующие</h2>
    <?php Pjax::begin(['id' => 'relaitedGoods', 'options' => ['class' => 'pjax-wraper']]) ?>
    <?php if (!empty($relatedGoods)) { ?>
        <?php foreach ($relatedGoods as $good) { ?>
            <div class="line-box related-good">
                <a href="<?= yii\helpers\Url::to(['catalog/catalog_edit', 'id' => $good->id]) ?>" data-pjax="0">
                    <?= $good->name ?><br>
                    <?php if (!empty($good->image)) { ?>
                        <img src="<?= \app\components\IcmsHelper::getResizePath($good->getPath('image')) ?>">
                    <?php } else { ?>
                        <img src="<?= IcmsAsset::path('img/icon_default_image.png') ?>">
                    <?php } ?>
                </a>
                <button class="button" type="button" data-good-id="<?= $model->id ?>" data-id="<?= $good->id ?>">Удалить</button>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="line-box" style="text-align: center;">Не найдено сопутствующих товаров</div>
    <?php } ?>
    <?php Pjax::end() ?>
    <?php if (!empty($relatingGoods)) { ?>
        <h2>В сопутствующих у</h2>
        <?php foreach ($relatingGoods as $good) { ?>
            <div class="line-box related-good">
                <a href="<?= yii\helpers\Url::to(['catalog/catalog_edit', 'id' => $good->id]) ?>">
                    <?= $good->name ?><br>
                    <?php if (!empty($good->image)) { ?>
                        <img src="<?= \app\components\IcmsHelper::getResizePath($good->getPath('image')) ?>">
                    <?php } else { ?>
                        <img src="<?= IcmsAsset::path('img/icon_default_image.png') ?>">
                    <?php } ?>
                </a>
            </div>
        <?php } ?>
    <?php } ?>
</div>

