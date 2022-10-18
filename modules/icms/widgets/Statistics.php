<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;
use app\modules\icms\assets\ChartC3Asset;
use yii\helpers\ArrayHelper;

class Statistics extends Widget
{

    public $title = '';
    public $interval = 'week';
    public $columns = [];
    public $requestParameter = 'interval';
    public $showIntervalSelector = true;
    private $_columns = [];
    private $_selectInterval = 'week';
    private $_intervalNames = ['day' => 'День', 'week' => 'Неделя', 'month' => 'Месяц', 'year' => 'Год'];
    private $_intervals = [];
    private $_templateIntervals = null;

    public function initIntervals()
    {
        $now = time();
        $this->_templateIntervals = [
            'day' => [$now - (60 * 60 * 24), $now],
            'week' => [$now - (60 * 60 * 24 * 6), $now],
            'month' => [$now - (60 * 60 * 24 * 30), $now],
            'year' => [$now - (60 * 60 * 24 * 365), $now],
        ];

        if (!is_array($this->interval)) {
            $this->interval = $this->_templateIntervals[$this->interval];
        } else {
            if (!is_numeric($this->interval[0])) {
                $this->interval[0] = strtotime($this->interval[0]);
            }
            if (!is_numeric($this->interval[1])) {
                $this->interval[1] = strtotime($this->interval[1]);
            }
        }

        if (($this->interval[1] - $this->interval[0]) < 0) {
            throw new Exception('Дата конца больше, чем дата начала');
        }
        $dateStart = new \DateTime(date('d-m-Y H:i:s', $this->interval[0]));
        $dateEnd = new \DateTime(date('d-m-Y H:i:s', $this->interval[1]));
        $interval = new \DateInterval('P1D');

        $this->_columns[0][] = 'x';
        while ($dateStart <= $dateEnd) {
            $formatDate = $dateStart->format('Y-m-d');
            $this->_intervals[$formatDate] = 0;
            $this->_columns[0][] = $formatDate;
            $dateStart->add($interval);
        }
    }

    public function init()
    {
        $this->requestParameter = $this->getId() . '-' . $this->requestParameter;
        $this->interval = \Yii::$app->request->get($this->requestParameter, $this->interval);
        $this->_selectInterval = $this->interval;

        $this->initIntervals();
        $this->initColumns();
        ChartC3Asset::register($this->view);
    }

    private function columnGet($model, $dateField, $conditions = null, $timestamp = false)
    {
        if ($dateField === 'created_at' || $dateField === 'updated_at') {
            $timestamp = true;
        }

        $schema = $model::tableName();

        $query = new \yii\db\Query();
        $query->from($schema);
        $query->andWhere($conditions);
        if ($timestamp) {
            $query->andWhere("`{$dateField}` >= {$this->interval[0]} AND `{$dateField}` <= {$this->interval[1]}");
            $query->select([
                'date' => "FROM_UNIXTIME( `{$dateField}`,  '%Y-%m-%d' )",
                'count' => 'count( 1 )'
            ]);
            $query->groupBy(new \yii\db\Expression("FROM_UNIXTIME(`{$dateField}`, '%Y-%m-%d' )"));
        } else {
            $startDate = date('Y-m-d', $this->interval[0]);
            $endDate = date('Y-m-d', $this->interval[1]);
            $query->andWhere("`{$dateField}` >= '{$startDate} 00:00:00' AND `{$dateField}` <= '{$endDate} 23:59:59'");
            $query->select([
                'date' => "DATE_FORMAT( `{$dateField}`,  '%Y-%m-%d' )",
                'count' => 'count( 1 )'
            ]);
            $query->groupBy(new \yii\db\Expression("DATE_FORMAT( `{$dateField}`, '%Y-%m-%d' )"));
        }
        $result = [];

        foreach ($query->each() as $row) {
            $result[$row['date']] = (int) $row['count'];
        }

        return $result;
    }

    private function initColumns()
    {
        foreach ($this->columns as $key => $columnInfo) {

            $modelCounts = $this->columnGet(
                    $columnInfo['model'], isset($columnInfo['dateField']) ? $columnInfo['dateField'] : 'created_at', isset($columnInfo['conditions']) ? $columnInfo['conditions'] : null, isset($columnInfo['timestamp']) ? $columnInfo['timestamp'] : false
            );
            $result = ArrayHelper::merge($this->_intervals, $modelCounts);
            array_unshift($result, isset($columnInfo['title']) ? $columnInfo['title'] : $columnInfo['model']);
            $this->_columns[$key + 1] = array_values($result);
        }
    }

    public function run()
    {
        $options = [
            'bindto' => '#' . $this->getId(),
            'data' => [
                'x' => 'x',
                'columns' => $this->_columns,
            ],
            'axis' => [
                'x' => [
                    'type' => 'timeseries',
                    'tick' => [
                        'count' => 7,
                        'format' => '%d.%m.%y',
                    ],
                ]
            ],
            'grid' => [
                'x' => ['show' => true],
                'y' => ['show' => true],
            ]
        ];
        $optionsJson = \yii\helpers\Json::encode($options);
        $this->view->registerJs("c3.generate($optionsJson);");
        return $this->render('statistics', [
                    'title' => $this->title,
                    'id' => $this->getId(),
                    'requestParameter' => $this->requestParameter,
                    'intervals' => $this->_intervalNames,
                    'selectInterval' => $this->_selectInterval,
                    'intervalSelector' => $this->showIntervalSelector
        ]);
    }

}
