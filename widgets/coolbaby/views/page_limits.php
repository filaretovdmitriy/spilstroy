<?php

use yii\helpers\ArrayHelper;
?>
<div class="btn-group btn-select perpage-select">
    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="value"><?= $pages->pageSize ?></span>
        <span class="caret min"></span>
    </a>
    <ul class="dropdown-menu">
        <?php
        foreach ($limits as $limit) {
            if ($limit == $pages->pageSize) {
                continue;
            }
            ?>
            <li><a href="<?= Yii::$app->urlManager->createUrl(ArrayHelper::merge($parameters, [$pages->pageSizeParam => $limit])) ?>"><?= $limit ?></a></li>
        <?php } ?>
    </ul>
</div>