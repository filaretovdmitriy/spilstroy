<?php

namespace app\modules\icms\widgets\grid;

use yii\helpers\Html;
use app\components\IcmsHelper;
use yii\helpers\ArrayHelper;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\CheckBox;
use app\modules\icms\widgets\Radio;
use \yii\base\Model;
use app\modules\icms\widgets\FilterInput;

class GridFormatColumn extends \yii\grid\DataColumn
{

    public $attribute;
    public $format;
    public $placeholder = '';
    public $function;
    public $options = [];
    public $sortLinkOptions = [
        'data-is-pjax' => true
    ];
    private $relationName = null;

    public function init()
    {

        $attribute = $this->attribute;
        $parseAttribute = explode('.', $attribute);
        if (count($parseAttribute) === 2) {
            $this->relationName = $parseAttribute[0];
            $this->attribute = $parseAttribute[1];
        }
    }

    protected function renderFilterCellContent()
    {
        if (is_string($this->filter)) {
            return $this->filter;
        }
        $model = $this->grid->filterModel;

        if ($this->filter !== false && $model instanceof Model && $this->attribute !== null && $model->isAttributeActive($this->attribute)) {
            if ($model->hasErrors($this->attribute)) {
                Html::addCssClass($this->filterOptions, 'has-error');
                $error = ' ' . Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
            } else {
                $error = '';
            }
            if (is_array($this->filter)) {
                return DropDownList::widget(['model' => $model, 'attribute' => $this->attribute, 'items' => [null => $this->placeholder ?: 'Выберите значение'] + $this->filter]) . $error;
            } else {
                return FilterInput::widget(['model' => $model, 'attribute' => $this->attribute, 'placeholder' => $this->placeholder]) . $error;
            }
        } else {
            return parent::renderFilterCellContent();
        }
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        if (!is_null($this->relationName)) {
            $model = $model->{$this->relationName};

            if (is_null($model) === true) {
                return '---';
            }

            $key = $model->getPrimaryKey();
        }

        if (empty($this->format)) {
            return $model->{$this->attribute};
        }

        if (!is_array($this->format)) {
            $formats = [$this->format];
        } else {
            $formats = $this->format;
        }

        if (!empty($this->attribute)) {
            $result = $model->{$this->attribute};
        }
        foreach ($formats as $format) {
            switch ($format) {
                case 'link';
                    $result = $this->generateLink($result, $key, $model);
                    break;
                case 'cut':
                    $result = $this->generateCuts($result);
                    break;
                case 'nl2br':
                    $result = nl2br($result);
                    break;
                case 'select':
                    $result = $this->generateSelect($model);
                    break;
                case 'date':
                    $result = $this->generateDate($result);
                    break;
                case 'img':
                    $result = $this->generateImg($model);
                    break;
                case 'input':
                    $result = $this->generateInput($model);
                    break;
                case 'radio':
                    $result = $this->generateRadio($model);
                    break;
                case 'textarea':
                    $result = $this->generateTextArea($model);
                    break;
                case 'checkbox':
                    $result = CheckBox::widget(['model' => $model, 'attribute' => $this->attribute]);
                    break;
                case 'count':
                    $result = count($model->{$this->attribute});
                    break;
                case 'repeat':
                    $result = $this->generateRepeat($model);
                    break;
                case 'array':
                    $result = $this->generateArray($model);
                    break;
                case 'raw':
                    $result = $this->generateRaw($model, $key, $index);
                    break;
                case 'number':
                    $result = $this->numberFormat($model);
                    break;
                case 'prefix':
                    $result = $this->prefix($model) . $result;
                    break;
                default :
                    throw new \Exception('Неизвестный формат - ' . $format);
            }
        }

        return $result;
    }

    private function generateRepeat($model)
    {
        if (!isset($this->options['text'])) {
            throw new \Exception('Нет текста для повторения');
        }
        $count = (int) $model->{$this->attribute};
        return $count > 0 ? str_repeat($this->options['text'], $count) : '';
    }

    private function generateInput($model)
    {
        if (isset($this->options['class'])) {
            return Html::textInput(Html::getInputName($model, $this->attribute), $model->{$this->attribute}, ['class' => $this->options['class']]);
        } else {
            return Html::textInput(Html::getInputName($model, $this->attribute), $model->{$this->attribute});
        }
    }

    private function generateTextArea($model)
    {
        if (isset($this->options['class'])) {
            return Html::textarea(Html::getInputName($model, $this->attribute), $model->{$this->attribute}, ['class' => $this->options['class']]);
        } else {
            return Html::textarea(Html::getInputName($model, $this->attribute), $model->{$this->attribute});
        }
    }

    private function generateImg($model)
    {
        if (empty($model->{$this->attribute})) {
            return 'Нет';
        }
        $fullName = $model->getPath($this->attribute);

        if (isset($this->options['resize'])) {
            $params = ArrayHelper::merge([
                        'width' => 100,
                        'height' => 100,
                        'type' => 1,
                        'resizePath' => '/resize/'
                            ], $this->options['resize']);

            $fullName = IcmsHelper::getResizePath($fullName, $params['width'], $params['height'], $params['type'], $params['resizePath']);
        }

        return Html::img($fullName);
    }

    private function generateDate($date)
    {
        $dateFormat = isset($this->options['dateFormat']) ? $this->options['dateFormat'] : 'd.m.Y H:i:s';
        if (is_string($date)) {
            $date = strtotime($date);
        }
        return date($dateFormat, $date);
    }

    private function generateSelect($model)
    {
        if (!isset($this->options['items'])) {
            throw new \Exception('Нет списка элементов');
        }
        return DropDownList::widget([
                    'name' => Html::getInputName($model, $this->attribute),
                    'items' => $this->options['items'],
                    'selection' => $model->{$this->attribute}
        ]);
    }

    private function generateArray($model)
    {
        if (!isset($this->options['items'])) {
            throw new \Exception('Нет списка элементов');
        }

        if (isset($this->options['items'][$model->{$this->attribute}])) {
            return $this->options['items'][$model->{$this->attribute}];
        } else {
            return '---';
        }
    }

    private function generateCuts($string)
    {
        if (!isset($this->options['length'])) {
            throw new \Exception('Не уканана длина обрезки');
        }
        return IcmsHelper::cutString($string, $this->options['length']);
    }

    private function generateLink($string, $key, $model)
    {
        if (!isset($this->options['link'])) {
            throw new \Exception('Не задана ссылка');
        }
        $link = $this->options['link'];

        if (is_array($link) === true) {
            $routeLink = [array_shift($link)];

            foreach ($link as $key => $value) {
                if (is_string($key) === true) {
                    $routeLink[$key] = $model->{$value};
                } else {
                    $routeLink[$value] = $model->{$value};
                }
            }

            $link = $routeLink;
        } else {
            $link .= isset($this->options['addField']) ? $model->{$this->options['addField']} : $key;
        }

        $options = [];
        if (isset($this->options['is-pjax']) === true) {
            $options = ['data-is-pjax' => true];
        }

        if (isset($this->options['htmlOptions'])) {
            $options = ArrayHelper::merge($options, $this->options['htmlOptions']);
        }

        return Html::a($string, $link, $options);
    }

    private function generateRaw($model, $key, $index)
    {
        if (!isset($this->function)) {
            throw new \Exception('Не задана функция');
        }
        $function = $this->function;
        if (!is_callable($function)) {
            throw new \Exception('Не функция');
        }
        return $function($model, $key, $index);
    }

    private function numberFormat($model)
    {
        $default = [
            'decimals' => 2,
            'dec_point' => '.',
            'thousands_sep' => ' ',
        ];
        $value = (int) $model->{$this->attribute};
        if (isset($this->options['format'])) {
            $params = ArrayHelper::merge($default, $this->options['format']);
        } else {
            $params = $default;
        }
        return number_format($value, $params['decimals'], $params['dec_point'], $params['thousands_sep']);
    }

    private function prefix($model)
    {
        if (!isset($this->options['text'])) {
            throw new \Exception('Нет текста для повторения');
        }
        if (isset($this->options['countField'])) {
            $count = (int) $model->{$this->options['countField']};
            if (isset($this->options['skipFirst']) && $this->options['skipFirst'] == true) {
                $count -= 1;
            }
            $prefix = $count > 0 ? str_repeat($this->options['text'], $count) : '';
        } else {
            $prefix = $this->options['text'];
        }
        return $prefix;
    }

    private function generateRadio($model)
    {

        \Yii::$app->view->registerJs(<<<JS
$('.save-unique-model-value').on('change', function() {
    elem = $(this);
    model = elem.data('model');
    attribute = elem.data('attribute');
    id = elem.data('id');
    $.post('/icms/ajax/save_unique_model_value', {model: model, attribute: attribute, id: id}, function(data) {
        if (data.success) {
            $.gritter.add({title: 'Сохранение', text: 'Значение установлено'});
        } else {
            $.gritter.add({title: 'Сохранение', text: 'Ошибка!'});
        }
    }, 'json');
});
JS
        );

        return Radio::widget([
                    'name' => Html::getInputName($model, $this->attribute),
                    'checked' => (boolean) $model->{$this->attribute},
                    'value' => $model->id,
                    'options' => ['class' => 'save-unique-model-value', 'data-model' => get_class($model), 'data-attribute' => $this->attribute, 'data-id' => $model->id]
        ]);
    }

}
