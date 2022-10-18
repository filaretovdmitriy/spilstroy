<?php
use app\modules\icms\widgets\AdminMenuDropDown;
use app\modules\icms\widgets\AdminMenuAdvanced;
use app\modules\icms\widgets\BreadCrumbs;
?>

<?php $this->beginContent('@icms/layouts/main.php') ?>

    <div class='header'>
        <?= BreadCrumbs::widget() ?>
        <?= app\modules\icms\widgets\Preloader::widget() ?>
        <div class='btns'>
            <div class='advanced'>
                <?= AdminMenuAdvanced::widget() ?>
            </div>
            <?= AdminMenuDropDown::widget() ?>
        </div>
    </div>
    <?= $content ?>

<?php $this->endContent() ?>
