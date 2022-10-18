<?php

use yii\helpers\Html;
use app\modules\icms\widgets\Tabs;
?>
<div class="data">
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <form method="POST">
        <?php
        $tabs = Tabs::begin([
                    'tabNames' => ['Общая информация']
        ]);
        ?>

        <?php $tabs->beginTab(); ?>
        <div class='col-70'>
            <fieldset>
                <?= Html::label('Содержимое файла robots.txt', 'robotsText'); ?>
                <?= Html::textarea('robotsText', $fileContent, ['class' => 'width-100']) ?>
                <?= Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken()) ?>
            </fieldset>
        </div>
        <?php $tabs->endTab(); ?>
        
        <?php $tabs::end(); ?>
        <div class="clear"></div>
        <div class='col-70'>
            <div class="action_buttons">
                <a class="back">Назад</a>
                <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
            </div>
        </div>
    </form>
</div>