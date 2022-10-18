<?php

use yii\helpers\Html;
use app\modules\icms\widgets\RadioList;

Yii::$app->view->registerJs(<<<JS
    $('#redirect-list').on('click', '.grid-action', function() {
        if(confirm('Удалить этот редирект?')) {
            $(this).parents('tr').remove();
            if ($('#redirect-list tr').not('#empty-message-row, #redirect-template').length === 0) {
                $('#empty-message-row').show();
            }
        }
    });
    var REDIRECTS_COUNTER = 0;
    $(document).on('click', '#add-redirect', function() {
        REDIRECTS_COUNTER++;
        template = $('#redirect-template').clone();
        template.attr('id', 'new-redirect-' + REDIRECTS_COUNTER);
        template.find('[name="template-from"]').attr('name', 'redirectsNew[from][' + REDIRECTS_COUNTER + ']');
        template.find('[name="template-to"]').attr('name', 'redirectsNew[to][' + REDIRECTS_COUNTER + ']');
        template.find('[name="template-code"]').each(function(i) {
            elem = $(this);
            if (i == 0) {
                elem.attr('id', 'redirectsNew_code_301_' + REDIRECTS_COUNTER);
            } else {
                elem.attr('id', 'redirectsNew_code_302_' + REDIRECTS_COUNTER);
            }
        });
        template.find('[name="template-code"]').attr('name', 'redirectsNew[code][' + REDIRECTS_COUNTER + ']');
        template.find('label[for="templatecode-301"]').attr('for', 'redirectsNew_code_301_' + REDIRECTS_COUNTER);
        template.find('label[for="templatecode-302"]').attr('for', 'redirectsNew_code_302_' + REDIRECTS_COUNTER);
        
        $('#redirect-list').append(template);
        
        $('#empty-message-row').hide();
        template.show();
    });
JS
);
?>
<?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
<form method="POST" enctype="multipart/form-data">
    <?= Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken()) ?>
    <div class="col-70">
        <table class="table width-100 table-striped" style="border-right: 1px solid #D7DDE2">
            <thead>
                <tr>
                    <th>Откуда</th>
                    <th style="width: 20px;"></th>
                    <th>Куда</th>
                    <th style="width: 240px;">Код</th>
                    <th style="width: 20px;"></th>
                </tr>
            </thead>
            <tbody id="redirect-list">
                <?php
                $counter = 0;
                foreach ($redirects as $from => $info) {
                    $counter++;
                    ?>
                    <tr id="redirect-<?= $counter ?>">
                        <td><?= Html::textInput("redirects[from][{$counter}]", $from) ?></td>
                        <td class="center">=></td>
                        <td><?= Html::textInput("redirects[to][{$counter}]", $info[0]) ?></td>
                        <td>
                            <?= RadioList::widget(['name' => "redirects[code][{$counter}]", 'select' => $info[1], 'items' => [
                                    301 => '301 (Перемещено навсегда)',
                                    302 => '302 (Перемещено временно)',
                                ]])
                            ?>
                        </td>
                        <td>
                            <a class="grid-action" href="javascript:void(0)" title="Удалить">
                                <i class="active-icon active-icon-delete"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>

                <tr id="empty-message-row" style="<?= !empty($redirects) ? 'display: none' : '' ?>">
                    <td colspan="5" class="center">Не назначено ни одного редиректа</td>
                </tr>

                <tr id="redirect-template" style="display: none">
                    <td><?= Html::textInput("template-from", '') ?></td>
                    <td class="center">=></td>
                    <td><?= Html::textInput("template-to", '') ?></td>
                    <td>
                        <?=
                        RadioList::widget(['name' => "template-code", 'select' => 301, 'items' => [
                                301 => '301 (Перемещено навсегда)',
                                302 => '302 (Перемещено временно)'
                    ]])
                        ?>
                    </td>
                    <td>
                        <a class="grid-action" href="javascript:void(0)" title="Удалить">
                            <i class="active-icon active-icon-delete"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-29 float_r">
        <div class="padding-right-20">
            <fieldset class="line-box">
                <legend>Из файла</legend>
                <?= \app\modules\icms\widgets\FileInput::widget(['name' => 'file']) ?>
                <br>
                <?= app\modules\icms\widgets\CheckBox::widget(['name' => 'overload', 'choiceLabel' => 'Переписать существующие']) ?>
            </fieldset>

            <div class="action_buttons">
                <a class="back">Назад</a>
                <?= Html::button('Добавить', ['class' => 'save', 'id' => 'add-redirect']) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'save', 'name' => 'save-button']) ?>
            </div>
        </div>
    </div>
</form>