<?php

use yii\helpers\Html;

$this->title = ' - '. $name;
?>
<div class="data">
    <div class="padd">

    <h1><?= Html::encode($name) ?></h1>
    <p><?= nl2br(Html::encode($message)) ?><p>
    <?= $exceptionText ?>
    </div>
</div>
