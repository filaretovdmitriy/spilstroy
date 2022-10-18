<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\CheckBoxSlide;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\AliasInput;
use app\modules\icms\widgets\ckeditor\CKEditor;
use app\modules\icms\widgets\FileImageInput;
use app\modules\icms\widgets\multi_upload\MultiUpload;

?>
<div class="data">
    <?php
    $form = ActiveFormIcms::begin();
    ?>
        <?php
        $tabs = Tabs::begin([
            'tabNames' => ['Общая информация', 'SEO']
        ]);
        ?>

        <?php $tabs->beginTab() ?>
            <div class='col-70'>
                <?= $form->field($model, 'name_menu')->textInput()->label('Название страницы') ?>
                <?php if ($model->url != '/') {
                    echo $form->field($model, 'pid')->widget(DropDownList::class, [
                        'items' => app\models\Tree::getNamedTreeAsArray(),
                        'options' => ['options' => $model::getDisabledBranch($model->id)]
                    ])->label('Родительская страница');
                    echo $form->field($model, 'name')->widget(AliasInput::class, [
                        'from' => Html::getInputId($model, 'name_menu'),
                        'autoEnabledField' => 'auto_url',
                        'autoEnabled' => false
                    ])->label('URL');
                } else {
                    echo $form->field($model, 'pid')->hiddenInput()->label('');
                    echo $form->field($model, 'name')->hiddenInput()->label('');
                } ?>
                <?= $form->field($model, 'content')->widget(CKEditor::class)->label('Контент') ?>
            </div>
            <div class='col-25 float_r'>

                <?php if (Yii::$app->user->can('developer')) {
                    echo $form->field($model, 'is_safe')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label('Защита от удаления');
                } ?>
                <?= $form->field($model, 'in_menu')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Не отображать /Отображать'])->label('Отображать в меню') ?>
                <?= $form->field($model, 'in_menu_bottom')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Не отображать /Отображать'])->label('Отображать в меню снизу') ?>
                <?= $form->field($model, 'in_map')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label('Отображать на карте сайта') ?>
                <?= $form->field($model, 'sort')->textInput()->label('Сортировка') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses()])->label('Статус') ?>
                <?php
                if (Yii::$app->user->can('developer') && $model->url !== '/') {
                    echo Html::label('Модуль');
                    echo DropDownList::widget(['name' => 'moduleTreeId', 'selection' => $moduleId,'items' => app\models\Module::getNamesAsArray()]);
                }
                ?>
                <?= $form->field($model, 'image')->widget(FileImageInput::class)->label('Изображение') ?>
                <?= MultiUpload::widget([
                    'modelName' => app\models\TreeGallery::class,
                    'relationValue' => $model->id,
                    'relationField' => 'tree_id',
                    'field' => 'image',
                    'label' => 'Изображения',
                    'type' => MultiUpload::TYPE_IMAGES,
                    'fields' => [
                        'name' => 'Изображение',
                        'sort' => 0,
                    ],
                ]) ?>
            </div>
        <?php $tabs->endTab() ?>

        <?php $tabs->beginTab() ?>
            <div class='col-70'>
                <?= $form->field($model, 'h1_seo')->textInput()->label('H1') ?>
                <?= $form->field($model, 'title_seo')->textInput()->label('Title') ?>
                <?= $form->field($model, 'description_seo')->textarea()->label('Description') ?>
                <?= $form->field($model, 'keywords_seo')->textarea()->label('Keywords') ?>
            </div>
            <div class='col-25 float_r'>
                <?= $form->field($model, 'nofollow')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /Да'])->label('Делать ссылку в меню с nofollow') ?>
            </div>
        <?php $tabs->endTab() ?>
    <?php Tabs::end(); ?>
    <div class="clear"></div>
    <div class='col-70'>
        <div class="action_buttons">
            <a class="back">Назад</a>
            <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveFormIcms::end(); ?>
</div>

