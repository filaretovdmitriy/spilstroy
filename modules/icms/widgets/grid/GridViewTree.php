<?php

namespace app\modules\icms\widgets\grid;

use app\modules\icms\widgets\grid\GridView;
use yii\helpers\Html;

class GridViewTree extends GridView
{

    public $treeField = 'pages';
    public $showChildren = true;
    public $layout = '{items}';
    public $ignoreKey = false;
    public $parentFieldName = 'pid';
    private $_filtered = true;

    public function init()
    {
        parent::init();

        if (!\Yii::$app->request->isPjax) {
            $script = <<<JS
function gridViewTreeHide() {
    key = $(this).data('key');
    childs = $('tr[data-parent-id=' + key + ']');
    
    if (childs.length > 0) {
        childs.each(gridViewTreeHide);
    }
    $(this).hide('fast');
}
function gridViewTreeShow() {
    key = $(this).data('key');
    childs = $('tr[data-parent-id=' + key + ']');
    isShow = $(this).children('.child-hide');
    
    if (childs.length > 0 && isShow.length == 0) {
        childs.each(gridViewTreeShow);
    }
    $(this).show('fast');
}
$('body').on('click', 'td.can-hiden', function() {
    parent = $(this).parent();
    parentId = parent.data('key');
                
    if ($(this).hasClass('child-hide')) {
        $(this).removeClass('child-hide');
        $('tr[data-parent-id=' + parentId + ']').each(gridViewTreeShow);
        $(this).html('▼');
    } else {
        $(this).addClass('child-hide');
        $('tr[data-parent-id=' + parentId + ']').each(gridViewTreeHide);
        $(this).html('►');
    }
});
JS;

            $this->view->registerJs($script);
        }

        if (!is_array($this->dataProvider->query->where)) {
            if ($this->ignoreKey === false) {
                $where = $this->parentFieldName . ' = 0';
            } else {
                $where = $this->parentFieldName . ' = 0 OR ' . $this->parentFieldName . ' = ' . $this->ignoreKey;
            }
            $this->dataProvider->query->andWhere($where);
            $this->_filtered = false;
        }
    }

    public function renderTableHeader()
    {
        if ($this->_filtered) {
            return parent::renderTableHeader();
        }

        $cells = [];
        if (!is_null($this->treeField)) {
            $cells[] = '<th style="width: 20px"></th>';
        }

        foreach ($this->columns as $column) {
            $cells[] = $column->renderHeaderCell();
        }

        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if ($this->filterPosition == self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition == self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        return "<thead>\n" . $content . "\n</thead>";
    }

    public function renderFilters()
    {
        if ($this->_filtered) {
            return parent::renderFilters();
        }

        if ($this->filterModel !== null) {
            $cells = [];
            if (!is_null($this->treeField)) {
                $cells[] = '<th></th>';
            }
            foreach ($this->columns as $column) {
                /* @var $column Column */
                $cells[] = $column->renderFilterCell();
            }

            return Html::tag('tr', implode('', $cells), $this->filterRowOptions);
        } else {
            return '';
        }
    }

    public function renderTableRow($model, $key, $index, $parentKey = 0)
    {
        if ($this->_filtered) {
            return parent::renderTableRow($model, $key, $index);
        }

        $cells = [];
        if (!is_null($this->treeField)) {
            $openCell = '<td></td>';

            if ($key !== $this->ignoreKey) {
                $childs = $model->{$this->treeField};
                if (count($childs) > 0) {
                    if ($this->showChildren) {
                        $openCell = Html::tag('td', '▼', ['class' => 'can-hiden']);
                    } else {
                        $openCell = Html::tag('td', '►', ['class' => 'can-hiden child-hide']);
                    }
                }
            }

            $cells[] = $openCell;
        }
        foreach ($this->columns as $column) {
            $cells[] = $column->renderDataCell($model, $key, $index);
        }
        if ($this->rowOptions instanceof Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }
        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;
        if ($parentKey != 0 && $parentKey !== $this->ignoreKey) {
            $options['data-parent-id'] = $parentKey;
            if (!$this->showChildren) {
                $options['style'] = 'display: none;';
            }
        }
        $result = Html::tag('tr', implode('', $cells), $options);

        if (!is_null($this->treeField) && $key !== $this->ignoreKey) {
            $childs = $model->{$this->treeField};

            foreach ($childs as $child) {
                $result .= $this->renderTableRow($child, $child->id, $index, $key);
            }
        }

        return $result;
    }

}
