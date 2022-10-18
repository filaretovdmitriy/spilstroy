<?php

use app\components\IcmsHelper;
use app\models\Parameter;

?>

<div class="features-wrapper">
    <?php foreach ($banners as $banner) { ?>
        <div class="feature-item">
            <img src="<?= $banner->getPath('file') ?>" alt="<?= $banner->name ?>">
            <?=$banner->name?>
        </div>
    <?}?>
</div>