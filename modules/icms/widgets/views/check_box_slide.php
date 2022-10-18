<?php

use yii\helpers\Html;

if (!is_null($model)) {
    ?>
    <label class="switch <?= $model->{$attribute} == true ? ' active' : '' ?>" for="<?= Html::getInputId($model, $attribute) ?>">
        <div class="inner"></div>
        <?= Html::activeCheckbox($model, $attribute, ['hidden' => 'hidden', 'label' => '', 'class' => 'slide-check-box']) ?>
    </label>
<?php } else { ?>
    <label class="switch <?= $checked == true ? ' active' : '' ?>" for="<?= $name ?>">
        <div class="inner"></div>
        <?= Html::checkbox($name, $checked, ['id' => $name, 'hidden' => 'hidden', 'label' => '', 'class' => 'slide-check-box']) ?>
    </label>
<?php } ?>
<label class="text" for="<?= !is_null($model) ? Html::getInputId($model, $attribute) : $name ?>"><?= $choiceLabel ?></label>
<div class="clear"></div>