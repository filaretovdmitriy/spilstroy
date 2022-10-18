<?php
use app\modules\icms\widgets\AdminMenuDropDown;
use app\modules\icms\widgets\AdminMenuAdvanced;
use app\modules\icms\widgets\BreadCrumbs;
use app\components\Pjax;
?>

<?php $this->beginContent('@icms/layouts/main.php') ?>

    <?php Pjax::begin([
        'id' => 'pjax-right',
        'linkSelector' => 'a[data-is-pjax]',
        'options' => ['class' => 'pjax-wraper']
    ]) ?>
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
    <?php Pjax::end() ?>

<?php $this->endContent() ?>
