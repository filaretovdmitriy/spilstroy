<?php

namespace app\modules\icms\widgets\grid;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class GridView extends \yii\grid\GridView
{

    const FILTER_TYPE_RESET = 4;
    const FILTER_TYPE_BUTTON = 1;
    const FILTER_TYPE_SELECT = 2;
    const FILTER_TYPE_FIND = 3;
    const FILTER_TYPE_DATE_FROM = 5;
    const FILTER_TYPE_DATE_TO = 6;

    public $layout = '{items}{pager}{summary}';
    public $pager = [
        'firstPageLabel' => '',
        'lastPageLabel' => '',
        'nextPageLabel' => '',
        'prevPageLabel' => '',
        'linkOptions' => ['data-is-pjax' => true],
    ];
    public $defaultSort = false;
    public $filterScenario;
    public $tableName = "";
    public $modelName;
    public $pageSize = 100;
    public $relations = null;
    public $conditions = [];
    public $filterParameter = false;
    public $filter = [
        [
            'title' => 'Поиск...',
            'type' => self::FILTER_TYPE_FIND,
        ]
    ];
    private $filterFields = [];

    public function init()
    {
        if ($this->filterParameter === false) {
            $this->filterParameter = "{$this->id}-filter";
        }

        $filterValues = \Yii::$app->request->get($this->filterParameter, []);
        foreach ($filterValues as $number => $value) {
            $this->filter[$number]['value'] = $value;
        }

        if (!empty($this->modelName)) {
            $modelName = $this->modelName;
            $this->filterScenario = empty($this->filterScenario) ? $modelName::SCENARIO_DEFAULT : $this->filterScenario;

            $this->filterModel = new $modelName([
                'scenario' => $this->filterScenario
            ]);

            $scenatios = $this->filterModel->scenarios();
            foreach ($scenatios[$this->filterScenario] as $field) {
                $this->filterFields[] = str_replace('!', '', $field);
            }

            if (empty($this->dataProvider) && count($this->filterFields) > 0) {
                $this->dataProvider = $this->search();
            }
        }

        parent::init();
    }

    private function search()
    {

        $modelName = $this->modelName;
        $with = [];

        $query = $modelName::find();
        /* @var $query \yii\db\ActiveQuery */
        if (empty($this->conditions) === false) {
            $query->andFilterWhere($this->conditions);
        }

        if (empty($this->filter) === false && $this->filter !== false) {
            foreach ($this->filter as $filter) {
                if (empty($filter['type']) === true) {
                    continue;
                }
                if (empty($filter['value']) === true && $filter['type'] !== self::FILTER_TYPE_BUTTON) {
                    continue;
                }
                if (isset($filter['value']) === false && $filter['type'] === self::FILTER_TYPE_BUTTON) {
                    continue;
                }

                switch ($filter['type']) {
                    case self::FILTER_TYPE_BUTTON:
                        if (empty($filter['value']) === true) {
                            continue 2;
                        }
                        if (empty($filter['with']) === false) {
                            $with = array_merge($with, (array) $filter['with']);
                        }
                        $search = $filter['condition'];

                        break;
                    case self::FILTER_TYPE_SELECT:
                        if (isset($filter['items'][$filter['value']]) === false) {
                            continue 2;
                        }
                        $parseName = explode('.', $filter['field']);
                        if (count($parseName) === 2) {
                            $with[] = $parseName[0] . ' ' . $parseName[0];
                        }
                        $search = ['=', $filter['field'], $filter['value']];

                        break;
                    case self::FILTER_TYPE_FIND:
                        $search = ['OR'];
                        $searchFields = empty($filter['fields']) === true ? $this->filterFields : (array) $filter['fields'];
                        foreach ($searchFields as $fieldName) {
                            $parseName = explode('.', $fieldName);
                            if (count($parseName) === 2) {
                                $with[] = $parseName[0] . ' ' . $parseName[0];
                            }
                            $search[] = ['LIKE', $fieldName, $filter['value']];
                        }
                        break;
                    case self::FILTER_TYPE_DATE_FROM:

                        $parseName = explode('.', $filter['field']);
                        if (count($parseName) === 2) {
                            $with[] = $parseName[0] . ' ' . $parseName[0];
                        }

                        if (empty($filter['clientOptions']) === true) {
                            $filter['clientOptions'] = [];
                        }

                        $hasTime = ArrayHelper::getValue($filter['clientOptions'], 'timepicker', false);

                        $isTimestamp = ArrayHelper::remove($filter, 'timestamp', false);
                        $value = $filter['value'];
                        if (is_numeric($filter['value']) === false && $isTimestamp === true) {
                            $value = strtotime($filter['value']);
                        } elseif ($isTimestamp === false) {
                            if ($hasTime === true) {
                                $value = date('Y-m-d H:i:s', strtotime($filter['value']));
                            } else {
                                $value = date('Y-m-d 00:00:00', strtotime($filter['value']));
                            }
                        }

                        $search = ['>=', $filter['field'], $value];
                        break;
                    case self::FILTER_TYPE_DATE_TO:

                        $parseName = explode('.', $filter['field']);
                        if (count($parseName) === 2) {
                            $with[] = $parseName[0] . ' ' . $parseName[0];
                        }

                        if (empty($filter['clientOptions']) === true) {
                            $filter['clientOptions'] = [];
                        }

                        $hasTime = ArrayHelper::getValue($filter['clientOptions'], 'timepicker', false);

                        $isTimestamp = ArrayHelper::remove($filter, 'timestamp', false);
                        $value = $filter['value'];
                        if (is_numeric($filter['value']) === false && $isTimestamp === true) {
                            if ($hasTime === true) {
                                $value = strtotime($filter['value']);
                            } else {
                                $value = strtotime($filter['value']) + 86399;
                            }
                        } elseif ($isTimestamp === false) {
                            if ($hasTime === true) {
                                $value = date('Y-m-d H:i:s', strtotime($filter['value']));
                            } else {
                                $value = date('Y-m-d 23:59:59', strtotime($filter['value']));
                            }
                        }

                        $search = ['<=', $filter['field'], $value];
                        break;
                }
                $query->andFilterWhere($search);
            }
        }

        if (is_null($this->relations) === false) {
            foreach ($this->relations as $fieldName => $queryParamName) {
                $value = \Yii::$app->request->get($queryParamName, 0);
                if (is_int($fieldName)) {
                    $fieldName = $queryParamName;
                }
                $query->andFilterWhere(['=', $fieldName, $value]);
            }
        }

        if (empty($with) === false) {
            $query->joinWith(array_unique($with));
        }

        $parameters = [
            'query' => $query,
            'pagination' => ['pageSize' => $this->pageSize],
        ];

        if ($this->defaultSort !== false) {
            $parameters['sort'] = ['defaultOrder' => $this->defaultSort];
        }

        return new \yii\data\ActiveDataProvider($parameters);
    }

    public function renderItems()
    {
        $caption = $this->renderCaption();
        $columnGroup = $this->renderColumnGroup();
        $tableHeader = $this->showHeader ? $this->renderTableHeader() : false;
        $tableBody = $this->renderTableBody();
        $tableFooter = $this->showFooter ? $this->renderTableFooter() : false;
        $content = array_filter([
            $caption,
            $columnGroup,
            $tableHeader,
            $tableFooter,
            $tableBody,
        ]);

        $html = '';
        if (empty($this->tableName) === false) {
            $html .= Html::tag('h2', $this->tableName, ['class' => 'padd float_l']);
        }

        if (empty($this->filter) === false) {
            $html .= $this->initFilter();
        }

        $html .= Html::tag('table', implode("\n", $content), $this->tableOptions);
        return $html;
    }

    private function initFilter()
    {

        $html = [];

        $default = [
            self::FILTER_TYPE_RESET => [
                'title' => 'Все',
                'class' => 'button',
            ],
            self::FILTER_TYPE_BUTTON => [
                'with' => false,
            ],
            self::FILTER_TYPE_SELECT => [
                'title' => 'Выберите значение',
                'items' => [],
            ],
            self::FILTER_TYPE_FIND => [
                'title' => 'Поиск...',
                'fields' => false,
            ],
            self::FILTER_TYPE_DATE_FROM => [
                'title' => 'с...',
            ],
            self::FILTER_TYPE_DATE_TO => [
                'title' => 'по...',
            ],
        ];


        foreach ($this->filter as $number => $filter) {
            $type = ArrayHelper::remove($filter, 'type', false);
            if (empty($default[$type]) === true) {
                continue;
            }
            if (empty($default[$type]) === true) {
                throw new Exception('Unknown filter type');
            }

            $option = ArrayHelper::merge($default[$type], $filter);
            $value = ArrayHelper::remove($option, 'value');

            $filterName = "{$this->filterParameter}[{$number}]";

            switch ($type) {
                case self::FILTER_TYPE_RESET:
                    $html[] = $this->filterTypeReset($filterName, $option, $value);
                    break;
                case self::FILTER_TYPE_BUTTON:
                    $html[] = $this->filterTypeButton($filterName, $option, $value, $number);
                    break;
                case self::FILTER_TYPE_SELECT:
                    $html[] = $this->filterTypeSelect($filterName, $option, $value);
                    break;
                case self::FILTER_TYPE_FIND:
                    $html[] = $this->filterTypeFind($filterName, $option, $value);
                    break;
                case self::FILTER_TYPE_DATE_FROM:
                case self::FILTER_TYPE_DATE_TO:
                    $html[] = $this->filterDates($filterName, $option, $value);
                    break;
            }
        }

        if (empty($html) === false) {

            $this->view->registerJs(<<<'JS'
function updateGridViewFilter(elem) {
    filterBlock = elem.closest('.js-filter-block');

    var parameters = Helper.parse_url(location.search);
    var newParameters = Helper.parse_url($.param(filterBlock.find('input, select').not('[type="checkbox"]')));

    $.each(newParameters, function(name, value) {
        parameters[name] = value;
    });

    filterBlock.find('input[type="checkbox"]').each(function() {
        checkbox = $(this);
        name = checkbox.attr('name');
        value = checkbox.is(':checked') === true ? 1 : 0;
        parameters[name] = value;
    });

    url = location.pathname;
    url += '?';
    url += $.param(parameters);
    url += location.hash;

    grid = filterBlock.closest('.grid-view');
    grid.yiiGridView({filterUrl: url});
    grid.yiiGridView('applyFilter');
}
JS
            );
        }

        return Html::tag('div', implode("\n", $html), ['class' => 'clearfix float_r filter-block-header js-filter-block']);
    }

    private function filterTypeReset($name, $options, $value)
    {

        $title = ArrayHelper::remove($options, 'title');
        $options['name'] = $name;

        $class = ArrayHelper::remove($options, 'class', '');
        $class .= ' js-filter-type-reset button';
        $options['class'] = $class;

        $html = Html::button($title, $options);

        $this->view->registerJs(<<<JS
$('#{$this->id}').on('click', '.js-filter-type-reset', function() {
    elem = $(this);

    block = elem.closest('.js-filter-block');
    block.find('input[type="text"]').each(function() {
        $(this).val('');
    });
    block.find('input[type="checkbox"]').each(function() {
        $(this).attr('checked', false);
    });
    block.find('select').each(function() {
        select = $(this);
        select.find('option').attr('selected', false);
        select.trigger('refresh');
    });

    updateGridViewFilter(elem);
});
JS
        );

        return $html;
    }

    private function filterTypeButton($name, $options, $value, $number)
    {

        $title = ArrayHelper::remove($options, 'title');

        if (empty($options['id']) === true) {
            $options['id'] = $this->getId() . '_filter_button_' . $number;
        }

        $html = Html::checkbox($name, $value != 0, $options);

        $this->view->registerJs(<<<JS
$('#{$this->id}').on('change', '.js-filter-type-button', function() {
    elem = $(this);
    updateGridViewFilter(elem);
});
JS
        );

        return Html::tag(
                        'label', $html . Html::tag('span', $title), [
                    'class' => 'grid-filter-check-button js-filter-type-button',
                    'for' => $options['id'],
                        ]
        );
    }

    private function filterTypeSelect($name, $options, $value)
    {
        $title = ArrayHelper::remove($options, 'title');
        $items = ArrayHelper::remove($options, 'items');

        $style = ArrayHelper::remove($options, 'style', false);
        $options['style'] = $style;

        $class = ArrayHelper::remove($options, 'class', '');
        $class .= ' js-filter-type-select';
        $options['class'] = $class;
        $options['prompt'] = $title;

        $html = \app\modules\icms\widgets\drop_down_list\DropDownList::widget([
                    'name' => $name,
                    'selection' => $value,
                    'items' => $items,
                    'options' => $options,
                    'divClass' => 'inline-block',
        ]);


        $this->view->registerJs(<<<JS
$('#{$this->id}').on('change', '.js-filter-type-select', function() {
    elem = $(this);
    updateGridViewFilter(elem);
});
JS
        );

        return $html;
    }

    private function filterDates($name, $options, $value)
    {
        $title = ArrayHelper::remove($options, 'title', false);

        $clientOptions = ArrayHelper::remove($options, 'clientOptions', []);

        $class = ArrayHelper::remove($options, 'class', '');
        $class .= ' js-filter-type-dates';

        $options['class'] = $class;
        $options['placeholder'] = $title;

        $format = ArrayHelper::remove($options, 'format', null);

        if (empty($value) === true) {
            $value = false;
        }

        $html = \app\modules\icms\widgets\date_time_input\DateTimeInput::widget([
                    'name' => $name,
                    'value' => $value,
                    'format' => $format,
                    'options' => $options,
                    'clientOptions' => $clientOptions,
        ]);


        $this->view->registerJs(<<<JS
$('#{$this->id}').on('change', '.js-filter-type-dates', function() {
    elem = $(this);
    updateGridViewFilter(elem);
});
JS
        );

        return Html::tag('fieldset', $html, ['class' => 'inline-block']);
    }

    private function filterTypeFind($name, $options, $value)
    {
        $title = ArrayHelper::remove($options, 'title');
        $options['placeholder'] = $title;
        $class = ArrayHelper::remove($options, 'class', '');
        $class .= ' input filter-input-text-header js-filter-type-find';
        $options['class'] = $class;

        $html = Html::textInput($name, $value, $options);
        $html .= Html::button('<i class="filter-button search" title="Фильтровать"></i>', ['class' => 'button filter-input-btn js-filter-type-find-button']);


        $this->view->registerJs(<<<JS
$('#{$this->id}').on('keydown', '.js-filter-type-find', function(event) {
    if (event.keyCode !== 13) {
        return;
    }
    elem = $(this);
    updateGridViewFilter(elem);
});
$('#{$this->id}').on('click', '.js-filter-type-find-button', function() {
    elem = $(this);
    updateGridViewFilter(elem);
});
JS
        );

        return $html;
    }

}
