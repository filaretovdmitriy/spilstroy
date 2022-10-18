<?php

use yii\helpers\Html;
use app\modules\icms\widgets\CheckBoxList;
?>

<div class="sku-generator-modal data content">
    <h2>Генерация торговых предложений для товаров категории</h2>
    <div id="sku-categorie-parameters">
        <div class="notice">Все торговые предложения у товаров из текущих категорий и подкатегорий <strong>будут удалены</strong></div>
        <fieldset class="line-box">
            <legend>Шаблон артикула</legend>
            <input type="text" name="article-template" id="article-template">
            <button type="button" class="article-element" data-prop="good_id">ID товара</button>
            <?php foreach ($props as $alias => $prop) { ?>
                <button type="button" class="article-element" data-prop="<?= $alias ?>"><?= $prop['name'] ?></button>
            <?php } ?>
        </fieldset>
        <?php foreach ($props as $alias => $prop) { ?>
            <fieldset class="line-box">
                <legend><?= $prop['name'] ?></legend>
                <?=
                CheckBoxList::widget([
                    'items' => $prop['values'],
                    'name' => 'parameters[' . $alias . ']',
                    'select' => array_keys($prop['values'])
                ])
                ?>
            </fieldset>
        <?php } ?>
        <?= Html::hiddenInput('categorie_id', $model->id) ?>
    </div>
    <div class="col-100">
        <div class="action_buttons">
            <?= Html::submitButton('Генерировать', ['class' => 'save', 'name' => 'generate', 'id' => 'generate-categorie-sku']) ?>
        </div>
    </div>
</div>