<?php

use yii\helpers\ArrayHelper;
?>
<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <?= $pages->pageSize ?> <span class="caret"></span>
</button>
<ul class="dropdown-menu" role="menu">
    <?php
    foreach ($limits as $limit) {
        if ($limit == $pages->pageSize) {
            continue;
        }
        ?>
        <li><a href="<?= Yii::$app->urlManager->createUrl(ArrayHelper::merge($parameters, [$pages->pageSizeParam => $limit])) ?>"><?= $limit ?></a></li>
    <?php } ?>
</ul>