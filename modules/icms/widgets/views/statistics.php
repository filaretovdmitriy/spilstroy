<?php

use yii\helpers\Html;
?>

<div class="widget activity">
    <div class="whead">
        <div class="wtitle w-25"><?= $title ?></div>
        <?php if ($intervalSelector) { ?>
            <div class="wlinks w-70">
                <?php
                foreach ($intervals as $dateRange => $dateRangeName) {
                    echo Html::a(
                            $dateRangeName, [false, $requestParameter => $dateRange], [
                        'class' => $selectInterval === $dateRange ? 'active' : ''
                    ]);
                }
                ?>
            </div>
        <?php } ?>
    </div>
    <div class="wbody">
        <div id="<?= $id ?>" style="min-height: 320px;"></div>
    </div>
</div>