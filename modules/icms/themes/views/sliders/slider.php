<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\Tabs;
?>
<div class="data">
    <?php
    $form = ActiveFormIcms::begin();
    ?>
    <?php
        $tabs = Tabs::begin([
            'tabNames' => ['Общая информация']
        ]);
        ?>

        <?php $tabs->beginTab() ?>
            <div class='col-70'>
                <?= $form->field($model, 'name')->textInput()->label('Название слайдера') ?>
                <div class="clear"></div>
                <div class='col-70'>
                    <div class="action_buttons">
                        <a class="back">Назад</a>
                        <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
                    </div>
                </div>
            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs::end() ?>
    <?php ActiveFormIcms::end(); ?>
</div>