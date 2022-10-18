<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\AliasInput;
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
                <?= $form->field($model, 'pid')->widget(DropDownList::class, ['items' => app\models\Tree::getNamedTreeAsArray()])->label('Родительская страница') ?>
                <?= $form->field($model, 'name_menu')->textInput()->label("Название страницы") ?>
                <?= $form->field($model, 'name')->widget(AliasInput::class, [
                        'from' => Html::getInputId($model, 'name_menu'),
                        'autoEnabledField' => 'auto_url',
                        'autoEnabled' => true
                    ])->label('URL'); ?>

                <div class="clear"></div>
                <div class='col-70'>
                    <div class="action_buttons">
                        <a class="back">Назад</a>
                        <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
                    </div>
                </div>
            </div>
        <?php $tabs->endTab() ?>
        <?php Tabs::end(); ?>
    <?php ActiveFormIcms::end(); ?>
</div>