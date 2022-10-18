<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;
use app\modules\icms\assets\ChartC3Asset;
use app\components\YandexApi;
use app\models\Key;

class YandexActivity extends Widget
{

    public $dateRangeGET = 'date_range';
    private $visitorsInfo = [];
    static $dateRangeList = [
        'today' => 'Сегодня',
        'yesterday' => 'Вчера',
        'week' => 'Неделя',
        'month' => 'Месяц'
    ];

    private function getDateInterval()
    {
        $dateRange = \Yii::$app->request->get($this->dateRangeGET, 'week');
        $interval = ['start' => '', 'end' => date('Ymd')];
        switch ($dateRange) {
            case 'month':
                $interval['start'] = date('Ymd', time() - (60 * 60 * 24 * 30));
                break;
            case 'week':
                $interval['start'] = date('Ymd', time() - (60 * 60 * 24 * 6));
                break;
            case 'yesterday':
                $interval['start'] = date('Ymd', time() - (60 * 60 * 24 * 1));
                $interval['end'] = date('Ymd', time() - (60 * 60 * 24 * 1));
                break;
            default :
                $interval['start'] = date('Ymd');
                break;
        }

        return $interval;
    }

    public function init()
    {

        $parameters = Key::getGroup(5);

        if (!empty($parameters['yandex_metrika_access_token'])) {

            foreach ($parameters as $parameter) {
                if (empty($parameter)) {
                    return false;
                }
            }

            $interval = $this->getDateInterval();
            $yandexApi = new YandexApi(
                    $parameters['yandex_metrika_access_token'], $parameters['yandex_metrika_client_id'], $parameters['yandex_metrika_client_secret']
            );
            $result = $yandexApi->statTrafficSummary(
                    $parameters['yandex_metrika_counter_id'], null, $interval['start'], $interval['end']
            );
            $dataArray = [];
            if (!empty($result) && !isset($result['code'])) {
                foreach ($result['data'] as $data) {
                    $dataArray['visitors'][] = $data['visitors'];
                    $dataArray['page_views'][] = $data['page_views'];
                    $dataArray['new_visitors'][] = $data['new_visitors'];
                    $dataArray['date'][] = "'" . date('Y-m-d', strtotime($data['date'])) . "'";
                }
                $this->visitorsInfo = [
                    'visitors' => implode(',', $dataArray['visitors']),
                    'page_views' => implode(',', $dataArray['page_views']),
                    'new_visitors' => implode(',', $dataArray['new_visitors']),
                    'date' => implode(',', $dataArray['date']),
                ];
            } elseif (isset($result['code'])) {
                NotificationGritter::widget(['preset' => 'error', 'options' => [
                        'title' => 'Ошибка загрузки метрики (' . $result['code'] . ')',
                        'text' => $result['message'],
                        'time' => 9999
                ]]);
            }
        }
        return parent::init();
    }

    public function run()
    {
        if (count($this->visitorsInfo) === 0) {
            return '';
        }
        $script = <<<JS
var chart = c3.generate({
    bindto: '#chartYandex',
    data: {
        x: 'x',
        columns: [
            ['x', {$this->visitorsInfo['date']}],
            ['Посетители', {$this->visitorsInfo['visitors']}],
            ['Новых посетителей', {$this->visitorsInfo['new_visitors']}],
            ['Просмотры', {$this->visitorsInfo['page_views']}],
        ]
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                count: 7,
                format: '%d.%m.%y'
            }
        }
    },
    grid: {
        x: {
            show: true
        },
        y: {
            show: true
        }
    }

});
JS;
        $this->view->registerJs($script);
        ChartC3Asset::register($this->view);

        return $this->render('yandex_activity', [
                    'dateRangeGET' => $this->dateRangeGET
        ]);
    }

}
