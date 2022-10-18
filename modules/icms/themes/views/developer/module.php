<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\Tabs;
use yii\helpers\Url;
?>
<div class="data">
    <?php $form = ActiveFormIcms::begin() ?>
    <?php $tabs = Tabs::begin([
            'tabNames' => ['Общая информация']
    ]) ?>
        <?php $tabs->beginTab() ?>
            <div class='col-70'>
                <?= $form->field($model, 'name')->textInput()->label('Название') ?>
                <?= $form->field($model, 'route')->textInput()->label('Роут') ?>
            </div>
            <div class="col-25 float_r">
                <?php if (empty($model->tree_id) === false) { ?>
                <fieldset>
                    <label>Страница: <strong><?= $model->page->name_menu ?></strong></label>
                    <a target="_blank" href="<?= Url::to(['structure/edit', 'id' => $model->tree_id]) ?>" class="button">Редактировать страницу</a>
                </fieldset>
                <?php } ?>
                <?php if (empty($model->url) === false) { ?>
                    <a target="_blank" href="/<?= $model->url ?>" class="button">Перейти на страницу</a>
                <?php } ?>
            </div>
        <?php $tabs->endTab() ?>
    <?php $tabs::end() ?>
    <div class="clear"></div>
    <div class='col-100'>
        <div class="action_buttons">
            <a class="back">Назад</a>
            <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveFormIcms::end() ?>
</div>