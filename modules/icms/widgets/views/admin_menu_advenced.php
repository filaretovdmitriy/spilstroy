<?php

use yii\helpers\Html;
?>
<div class='advanced'>
    <?php foreach ($adminMenu as $menuElem) { ?>
        <?= Html::a($menuElem->title, [$menuElem->route], ['data-is-pjax' => Yii::$app->controller->layout === '']) ?>
    <?php } ?>
</div>