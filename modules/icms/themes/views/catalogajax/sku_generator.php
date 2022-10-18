<?php
use yii\helpers\Html;
use app\modules\icms\widgets\CheckBoxList;
?>

<div class="sku-generator-modal data content">
    <h2>Генерация торговых предложений товара</h2>
    <div id="sku-parameters">
        
        <fieldset class="line-box">
            <legend>Шаблон артикула</legend>
            <input type="text" name="article-template" id="article-template">
            <button type="button" class="article-element" data-prop="good_id">ID товара <?= $model->id ?></button>
            <?php foreach ($skuList as $prop => $sku) { ?>
                <button type="button" class="article-element" data-prop="<?= $prop ?>"><?= $allPropsArray[$prop]['name'] ?></button>
            <?php } ?>
        </fieldset>
        <?php foreach ($skuList as $prop => $sku) {
            if ($allPropsArray[$prop]['prop_type_id'] !== 4) {
                continue;
            }
            ?>
            <fieldset class="line-box">
                <legend><?= $allPropsArray[$prop]['name'] ?></legend>
                <?= CheckBoxList::widget([
                    'items' => $allPropsArray[$prop]['values'],
                    'name' => 'parameters[' . $prop . ']',
                    'select' => array_keys($allPropsArray[$prop]['values'])
                ]) ?>
            </fieldset>
        <?php } ?>
        <?= Html::hiddenInput('catalog_id', $model->id) ?>
    </div>
    <div class="col-100">
        <div class="action_buttons">
            <?= Html::submitButton('Генерировать', ['class' => 'save', 'name' => 'generate', 'id' => 'generate-sku']) ?>
        </div>
    </div>
</div>