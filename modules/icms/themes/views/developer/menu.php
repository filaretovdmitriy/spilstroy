<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\models\User;
use app\modules\icms\widgets\CheckBoxSlide;
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
                <?= $form->field($model, 'title')->textInput()->label('Название') ?>
                <?= $form->field($model, 'route')->textInput()->label('Роут') ?>
                <?= $form->field($model, 'controller')->textInput()->label('Контроллер') ?>
                <?= $form->field($model, 'parentName')->textInput()->label('Имя родителя (GET)') ?>

                <div class="clear"></div>
                <div class='col-70'>
                    <div class="action_buttons">
                        <a class="back">Назад</a>
                        <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
                    </div>
                </div>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'pid')->widget(DropDownList::class, [
                    'items' => [0 => 'Нет'] + $model::getTreeArray(),
                    'options' => ['options' => $model::getDisabledBranch($model->id)]
                ])->label('Родитель') ?>
                <?= $form->field($model, 'role')->widget(DropDownList::class, ['items' => User::getRolesAsArray(), 'placeholder' => 'Выберите группу'])->label('Группа') ?>
                <?= $form->field($model, 'icon_class')->widget(DropDownList::class, ['items' => $model::getIconClasses(), 'placeholder' => 'Выберите иконку'])->label('Иконка') ?>
                <?= $form->field($model, 'isActive')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label("Включено?") ?>
                <?= $form->field($model, 'in_button')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label("Отображать в кнопке") ?>
                <?= $form->field($model, 'sort')->textInput()->label('Сортировка') ?>

            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs::end() ?>
    </div>
    <?php ActiveFormIcms::end(); ?>
</div>