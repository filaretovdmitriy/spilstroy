<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\drop_down_list\DropDownList;
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
                <?= $form->field($model, 'login')->textInput()->label("Login") ?>
                <?= $form->field($model, 'name')->textInput()->label('Имя пользователя') ?>
                <?= $form->field($model, 'email')->textInput()->label('Е-mail') ?>
                <?= $form->field($model, 'password')->input('password')->label('Пароль') ?>
                <?= $form->field($model, 'password_repeat')->input('password')->label('Повторите пароль') ?>

                <div class="clear"></div>
                <div class='col-70'>
                    <div class="action_buttons">
                        <a class="back">Назад</a>
                        <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
                    </div>
                </div>
            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'role')->widget(DropDownList::class, ['items' => $roles, 'placeholder' => 'Выберите группу'])->label('Группа') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs::end() ?>
    <?php ActiveFormIcms::end(); ?>
</div>