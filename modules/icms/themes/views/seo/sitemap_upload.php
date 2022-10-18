<?php

use yii\helpers\Html;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\FileInput;
?>
<div class="data">
    <form method="POST" enctype="multipart/form-data">
        <?php
        $tabs = Tabs::begin([
            'tabNames' => ['Общая информация']
        ]);
        ?>

        <?php $tabs->beginTab(); ?>
        <div class='col-70'>
            <fieldset>
                <?= Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken()) ?>

                <?= Html::label('Файл sitemap.xml', 'new_sitemap'); ?>
                <?= FileInput::widget(['name' => 'new_sitemap']) ?>
                <?php if (Yii::$app->getSession()->getFlash('SITEMAP_ERROR', false) !== false) { ?>
                    <div class="notice">Ошибка в формате xml. Файл не прошел валидацию</div>
                <?php } ?>
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