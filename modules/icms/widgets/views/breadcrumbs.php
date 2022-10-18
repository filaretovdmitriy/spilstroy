<?php

use yii\helpers\Url;
?>
<div class='breadcrumbs'>
    <?php for ($i = 0; $i < count($crumbs) - 1; $i++) { ?>
        <a href='<?= Url::to($crumbs[$i]['url']) ?>' data-is-pjax><?= $crumbs[$i]['title'] ?></a>
    <?php } ?>
    <span><?= $crumbs[$i]['title'] ?></span>
</div>