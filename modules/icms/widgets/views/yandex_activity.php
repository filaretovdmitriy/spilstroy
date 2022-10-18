<?php

use yii\helpers\Html;
use app\modules\icms\widgets\YandexActivity;
?>
<div class="widget activity">
    <div class="whead">
        <div class="wtitle w-15">Посещаемость</div>
        <div class="wlinks w-70">
            <?php
            foreach (YandexActivity::$dateRangeList as $dateRange => $dateRangeName) {
                echo Html::a($dateRangeName, '/icms/dashboard?' . $dateRangeGET . '=' . $dateRange, [
                    'class' => \Yii::$app->request->get($dateRangeGET, 'week') === $dateRange ? 'active' : ''
                ]);
            }
            ?>
        </div>
        <div class="ya w-14">
            <a class="float_r" href="http://metrika.yandex.ru" target="_blank">
                <span style="color:#cd2c24;">Я</span>
                <span>ндекс.Метрика</span>
                <div class="clear"></div>
            </a>
        </div>
    </div>
    <div class="wbody">
        <div id="chartYandex"></div>
    </div>
</div>