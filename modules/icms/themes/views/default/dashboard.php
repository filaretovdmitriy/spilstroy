<?php

use app\modules\icms\widgets\YandexActivity;
use app\modules\icms\widgets\Statistics;
use app\components\IcmsHelper;
use yii\helpers\Url;
?>
<?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'message', 'options' => ['title' => 'Добро пожаловать'], 'flash' => 'welcome']) ?>
<div class="widgets-wrapper">
    <?= YandexActivity::widget() ?>

    <?=
    Statistics::widget([
        'title' => 'Активность на сайте',
        'interval' => 'month', // Интервал по умолчанию. Не обязательный
        'requestParameter' => 'interval', // Название переменной запроса по умолчанию. Не обязательный
        'showIntervalSelector' => true, // Показывать ли выбор интервалов по умолчанию. Не обязательный
        'columns' => [
            [
                'title' => 'Активность пользователей',
                'model' => \app\models\UserLog::class,
                'dateField' => 'created_at',
                'conditions' => ['developer_only' => 0],
            ],
            [
                'title' => 'Комментарии (отзывы)',
                'model' => \app\models\Feedback::class,
            ],
            [
                'title' => 'Заказы (Всего)',
                'model' => \app\models\CatalogOrder::class,
                'dateField' => 'g_date',
                'conditions' => ['!=', 'catalog_order_status_id', app\models\CatalogOrder::STATUS_NEW],
            ],
            [
                'title' => 'Заказы (Не обработанные)',
                'model' => \app\models\CatalogOrder::class,
                'dateField' => 'g_date',
                'conditions' => ['catalog_order_status_id' => app\models\CatalogOrder::STATUS_SEND],
            ],
            [
                'title' => 'Заказы (Исполненые)',
                'model' => \app\models\CatalogOrder::class,
                'dateField' => 'g_date',
                'conditions' => ['NOT IN', 'catalog_order_status_id', [app\models\CatalogOrder::STATUS_NEW, app\models\CatalogOrder::STATUS_SEND]],
            ],
        ]
    ])
    ?>

    
    <div class="clear"></div>
    <div class="widget w-49 float_l">
        <div class="whead">
            <div class="wtitle">Активность</div>
        </div>
        <div class="wbody">
            <div class="data">
                <table class="table">
                    <tbody>
                        <?php foreach ($userLog as $log) { ?>
                            <tr>
                                <td><?= $log->name ?></td>
                                <td class="role"><?= $log->ip ?></td>
                                <td><?= date('d.m.Y H:i', $log->created_at) ?></td>
                            </tr>
                        <?php } ?>
                        <?php if (empty($userLog)) { ?>
                            <tr>
                                <td colspan="3" style="text-align: center">
                                    Нет активности
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php $form = yii\widgets\ActiveForm::begin() ?>
    <div class="widget w-49 float_r">
        <div class="whead">
            <div class="wtitle">Информация о сайте</div>
            <?php if (Yii::$app->user->can('developer')) { ?>
                <a href="<?= Url::to(['default/php_info']) ?>" class="phpinfo-link">phpinfo()</a>
            <?php } ?>
        </div>
        <div class="wbody">
            <div class="data">
                <table class="table">
                    <tbody>
                        <?php if (Yii::$app->user->can('developer')) { ?>
                            <tr>
                                <td>Хост</td>
                                <td colspan="2"><?= IcmsHelper::getDsnAttribute('host') ?></td>
                            </tr>
                            <tr>
                                <td>Имя</td>
                                <td colspan="2"><?= IcmsHelper::getDsnAttribute('dbname') ?></td>
                            </tr>
                            <tr>
                                <td>Префикс</td>
                                <td colspan="2"><?= Yii::$app->db->tablePrefix ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td><span title="<?= Yii::getAlias('@webroot/icms/assets') ?>">Кеш CMS</span></td>
                            <td><?= IcmsHelper::getSymbolByQuantity($cacheSizes['cms']) ?></td>
                            <td>
                                <?php if (Yii::$app->user->can('developer')) { ?>
                                    <button class="button" type="submit" name="clear" value="cms">Отчистить</button>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span title="<?= Yii::getAlias('@webroot/assets') ?>">Кеш сайта</span></td>
                            <td><?= IcmsHelper::getSymbolByQuantity($cacheSizes['site']) ?></td>
                            <td>
                                <?php if (Yii::$app->user->can('developer')) { ?>
                                    <button class="button" type="submit" name="clear" value="site">Отчистить</button>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span title="<?= Yii::getAlias('@runtime') ?>">Кеш фреймворка</span></td>
                            <td><?= IcmsHelper::getSymbolByQuantity($cacheSizes['framework']) ?></td>
                            <td>
                                <?php if (Yii::$app->user->can('developer')) { ?>
                                    <button class="button" type="submit" name="clear" value="framework">Отчистить</button>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span title="<?= Yii::getAlias('@image_cache') ?>">Кеш изображений</span></td>
                            <td><?= IcmsHelper::getSymbolByQuantity($cacheSizes['images']) ?></td>
                            <td>
                                <button class="button" type="submit" name="clear" value="images">Отчистить</button>
                            </td>
                        </tr>
                        <tr>
                            <td><span>Весь кеш</span></td>
                            <td><?= IcmsHelper::getSymbolByQuantity(array_sum($cacheSizes)) ?></td>
                            <td>
                                <?php if (Yii::$app->user->can('developer')) { ?>
                                    <button class="button" type="submit" name="clear" value="all">Отчистить</button>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php if (Yii::$app->user->can('developer') === true) { ?>
                            <tr>
                                <td><span title="<?= Yii::getAlias('@backups') ?>">Папка backups</span></td>
                                <td><?= IcmsHelper::getSymbolByQuantity(IcmsHelper::getDirecrotySize(Yii::getAlias('@backups'))) ?></td>
                                <td></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td><span title="<?= Yii::getAlias('@webroot/upload') ?>">Папка Upload</span></td>
                            <td><?= IcmsHelper::getSymbolByQuantity(IcmsHelper::getDirecrotySize(Yii::getAlias('@webroot/upload'))) ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><span title="<?= Yii::getAlias('@app') ?>">Проект</span></td>
                            <td><?= IcmsHelper::getSymbolByQuantity(IcmsHelper::getDirecrotySize(Yii::getAlias('@app'))) ?></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php $form->end(); ?>
</div>