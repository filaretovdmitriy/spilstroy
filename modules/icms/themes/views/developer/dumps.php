<?php
use yii\helpers\Html;
use app\modules\icms\widgets\CheckBoxSlide;
use app\components\IcmsHelper;
use yii\helpers\Url;
?>
<div class="data" style="padding: 0px 20px 20px 20px;">
    <div class="float_l col-24">
        <h2 class="padd">Создание</h2>

        <div class="line-box">
            <form action="" method="POST">
                <?= Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken()) ?>
                <b>Параметры</b>
                <fieldset>
                    <?= CheckBoxSlide::widget([
                        'name' => 'dump[for_source]',
                        'value' => 1,
                        'choiceLabel' => 'Исходный код (≈' . IcmsHelper::getSymbolByQuantity(IcmsHelper::getDirecrotySize(Yii::getAlias('@app')) - array_sum($cacheSizes) - IcmsHelper::getDirecrotySize(Yii::getAlias('@app') . '/web/upload') - IcmsHelper::getDirecrotySize(Yii::getAlias('@app') . '/backups')) . ')',
                        'checked' => true
                    ]) ?>
                </fieldset>
                <fieldset>
                    <?= CheckBoxSlide::widget([
                        'name' => 'dump[for_base]',
                        'value' => 1,
                        'choiceLabel' => 'База (' . IcmsHelper::getDsnAttribute('dbname') . ' с префиксом ' . Yii::$app->db->tablePrefix . ')',
                        'checked' => true
                    ]) ?>
                </fieldset>
                <fieldset>
                    <?= CheckBoxSlide::widget([
                        'name' => 'dump[for_upload]',
                        'value' => 1,
                        'choiceLabel' => 'Папка upload (≈' . IcmsHelper::getSymbolByQuantity(IcmsHelper::getDirecrotySize(Yii::getAlias('@webroot/upload'))) . ')',
                        'checked' => true
                    ]) ?>
                </fieldset>
                <fieldset>
                    <?= CheckBoxSlide::widget([
                        'name' => 'dump[clear]',
                        'value' => 1,
                        'choiceLabel' => 'Отчистить кеш (≈' . IcmsHelper::getSymbolByQuantity(array_sum($cacheSizes)) . ')',
                        'checked' => true
                    ]) ?>
                </fieldset>

                <div class="clear"></div>
                <div class='col-100'>
                    <div class="action_buttons">
                        <?= Html::submitButton('Создать', ['class' => 'save', 'name' => 'save-button']) ?>
                    </div>
                </div>
            </form>
            <div class="clear"></div>
        </div>

    </div>
    <div class="float_r col-75">
        <h2 class="padd">Список</h2>

        <?php if (empty($dumps) === false) { ?>
            <table class="backup-table">
                <tr>
                    <th>Название</th>
                    <th>Размер</th>
                    <th>Дата</th>
                    <th>Исходный код</th>
                    <th>Upload</th>
                    <th>База</th>
                    <th colspan="2">Действия</th>
                </tr>
                <?php foreach ($dumps as $dump) { ?>
                <tr class="<?= ($dump['name'] . '.zip') === $lastCreate?'backup-table-new':'' ?>">
                    <td><?= $dump['name'] ?>.zip</td>
                    <td class="text-right"><?= IcmsHelper::getSymbolByQuantity($dump['size']) ?></td>
                    <td><?= $dump['date'] ?></td>
                    <td class="text-center"><?= $dump['has_source']?'+':'-' ?></td>
                    <td class="text-center"><?= $dump['has_upload']?'+':'-' ?></td>
                    <td class="text-center"><?= $dump['has_base']?'+':'-' ?></td>
                    <td class="text-center">
                        <a href="<?= Url::to(['developer/dump_download', 'file' => $dump['name']]) ?>">Скачать</a>
                    </td>
                    <td class="text-center">
                        <a href="<?= Url::to(['developer/dump_remove', 'file' => $dump['name']]) ?>">Удалить</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <div class="notice">Резервных копий нет!</div>
        <?php } ?>

    </div>
</div>