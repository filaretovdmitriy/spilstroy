<?php
use yii\helpers\Html;
use app\modules\icms\widgets\ActiveFormIcms;
use app\models\ContentCategorie;
use app\modules\icms\widgets\FileImageInput;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\date_time_input\DateTimeInput;
use app\modules\icms\widgets\Tabs;
use app\modules\icms\widgets\AliasInput;
use app\modules\icms\widgets\ckeditor\CKEditor;
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
                <?= $form->field($model, 'content_categorie_id')->widget(DropDownList::class, ['items' => ContentCategorie::getNamesAsArray(), 'placeholder' => 'Выберите раздел'])->label('Раздел') ?>
                <?= $form->field($model, 'name')->textInput(['class' => 'width-100'])->label('Название') ?>
                <?= $form->field($model, 'alias')->widget(AliasInput::class, [
                        'from' => Html::getInputId($model, 'name'),
                        'autoEnabledField' => 'auto_alias',
                        'autoEnabled' => true
                    ])->label('URL') ?>
                <?= $form->field($model, 'anons')->textarea(['class' => 'width-100'])->label('Анонс') ?>
                <?= $form->field($model, 'content')->widget(CKEditor::class)->label('Контент') ?>


            </div>
            <div class="col-25 float_r">
                <?= $form->field($model, 'author')->textInput(['class' => 'width-100'])->label('Источник') ?>
                <?= $form->field($model, 'author_link')->textInput(['class' => 'width-100'])->label('Ссылка на источник') ?>
                <?= $form->field($model, 'image')->widget(FileImageInput::class)->label('Изображение') ?>
                <?= $form->field($model, 'sort')->textInput(['class' => 'width-100'])->label('Сортировка') ?>
                <?= $form->field($model, 'status')->widget(DropDownList::class, ['items' => $model::getStatuses(), 'placeholder' => 'Выберите статус'])->label('Статус') ?>
                <?= $form->field($model, 'g_date')->widget(DateTimeInput::class)->label('Дата публикации') ?>
            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs->beginTab() ?>
            <div class='col-70'>
                <?= $form->field($model, 'title_seo')->textInput(['class' => 'width-100'])->label("Title") ?>
                <?= $form->field($model, 'description_seo')->textarea(['class' => 'width-100'])->label('Description') ?>
                <?= $form->field($model, 'keywords_seo')->textarea(['class' => 'width-100'])->label('Keywords') ?>
            </div>
        <?php $tabs->endTab() ?>
        <?php $tabs::end() ?>
    <div class="clear"></div>
    <div class='col-70'>
        <div class="action_buttons">
            <a class="back">Назад</a>
            <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveFormIcms::end() ?>
</div>
