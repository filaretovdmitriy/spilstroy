<?php
use yii\helpers\Html;
use app\components\IcmsHelper;
?>
<div class="data">
    <table class="table width-100 table-striped">
        <thead>
            <tr>
                <th>Файл</th>
                <th>Состояние</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>robots.txt</td>
                <td>
                    <?php if ($hasRobots == true) {
                        echo Html::tag('span', 'Существует ', ['class' => 'success']);
                    } else {
                        echo Html::tag('span', 'Не существует', ['class' => 'error']);
                    } ?>
                </td>
                <td>
                    <?php if ($hasRobots == true) {
                        echo Html::a('Редактировать', ['seo/robots']);
                    } else {
                        echo Html::a('Создать', ['seo/robots']);
                    } ?>
                </td>
            </tr>
            <tr>
                <td>favicon.ico</td>
                <td>
                    <?php if ($hasFavicon == true) {
                        echo Html::tag('span', 'Существует ' . Html::img('/favicon.ico', ['class' => 'fav-icon']), ['class' => 'success']);
                    } else {
                        echo Html::tag('span', 'Не существует', ['class' => 'error']);
                    } ?>
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td>sitemap.xml</td>
                <td>
                    <?php if ($hasSitemap == true) {
                        echo Html::tag('span', 'Существует ', ['class' => 'success']);
                        if ($hasSitemapInRobots === false) {
                            echo Html::tag('span', '(не указан в robots.txt)', ['class' => 'error']);
                        }
                    } else {
                        echo Html::tag('span', 'Не существует', ['class' => 'error']);
                    }?>
                </td>
                <td>
                    <?= Html::a('Генерировать', ['seo/site_map']) ?>
                    /
                    <?= Html::a('Загрузить', ['seo/site_map_upload']) ?>
                </td>
            </tr>
            <tr>
                <td>Редиректы</td>
                <td>
                    <?php if ($redirectCount > 0) {
                        echo Html::tag('span', $redirectCount . ' шт.', ['class' => 'success']);
                    } else { 
                        echo Html::tag('span', 'Нет', ['class' => 'success']);
                    } ?>
                </td>
                <td><?= Html::a('Редактировать', ['seo/redirects']) ?></td>
            </tr>
            <tr>
                <td>Метрики, информеры</td>
                <td>
                    <?php if (!empty($metrics)) {
                        echo Html::tag('span', count($metrics) . ': ' . implode(', ', array_keys($metrics)) , ['class' => 'success']);
                    } else { 
                        echo Html::tag('span', 'Нет', ['class' => 'error']);
                    } ?>
                </td>
                <td><?= Html::a('Редактировать', ['seo/metrics']) ?></td>
            </tr>
            <tr>
                <td><abbr title="Тематический индекс цитирования">тИЦ</abbr></td>
                <td><?= IcmsHelper::getTIC() ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div class="padd">
        <h3>Информация о домене</h3>
        <pre><?= $whoIsText ?></pre>
    </div>
</div>