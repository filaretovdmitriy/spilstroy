<?php

namespace app\modules\icms\widgets\date_time_input;

use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class DateTimeInput extends InputWidget
{

    /**
     * Опции DateTimePicker https://github.com/xdan/datetimepicker <br>
     * Значение format не учитывается и берется из свойтсва format виджета
     * @var array
     */
    public $clientOptions = [];

    /**
     * Формат по умолчанию зависит от $clientOptions['timepicker'] d.m.Y или d.m.Y H:i:s или H:i:s<br>
     * Так же значение атрибута модели или value будет преобразовано в этот формат при выводе<br>
     * @var string
     */
    public $format = null;

    /**
     * Если null, то будет текущее значение даты/времени в заданном формате<br>
     * Если значение false, то поле будет пустое
     * @var mixed
     */
    public $value = null;

    /**
     * Настройки js скрипта по умолчанию
     * @var array
     */
    private $_defaultClientOptions = [
        'lang' => 'ru',
        'step' => 5,
        'dayOfWeekStart' => 1,
        'timepicker' => true,
        'datepicker' => true,
        'scrollMonth' => false,
    ];

    /**
     * Идентификатор инпута.<br>
     * Если модель, то генерируется из названия модели и поля<br>
     * Если виджет, то берется название виджета или указанный в массиве options id
     * @var string
     */
    private $_target;

    public function init()
    {
        $this->_registerAssets();

        $this->_initTarget();

        $clientOptions = ArrayHelper::merge($this->_defaultClientOptions, $this->clientOptions);

        if (is_null($this->format) === true) {
            $this->_initFormat($clientOptions);
        }

        $clientOptions['format'] = $this->format;

        $optionsJson = Json::encode($clientOptions);

        $js = <<<JS
$('#{$this->_target}').datetimepicker({$optionsJson});
JS;
        $this->view->registerJs($js);
    }

    /**
     * Инициализация селектора и идентификатора
     */
    private function _initTarget()
    {
        if (empty($this->options['id']) === false) {
            $this->_target = $this->options['id'];
            return;
        }
        if ($this->hasModel()) {
            $this->_target = Html::getInputId($this->model, $this->attribute);
        } else {
            $this->_target = $this->getId();
        }
        $this->options['id'] = $this->_target;
    }

    /**
     * Инициализация формата даты/времени
     * @param array $clientOptions настройки скрипта
     */
    private function _initFormat($clientOptions)
    {
        $format = [];
        if ($clientOptions['datepicker'] === true) {
            $format[] = 'd.m.Y';
        }
        if ($clientOptions['timepicker'] === true) {
            $format[] = 'H:i:s';
        }
        $this->format = implode(' ', $format);
    }

    public function run()
    {

        $value = $this->hasModel() === true ? $this->model->{$this->attribute} : $this->value;
        if (is_null($value) === true) {
            $value = date($this->format);
        } elseif ($value === false) {
            $value = '';
        } else {
            if (is_numeric($value) === false) {
                $value = strtotime($value);
            }
            $value = date($this->format, $value);
        }

        if ($this->hasModel()) {
            if (empty($this->options['readonly']) === true) {
                $this->options['readonly'] = true;
            }
            $this->model->{$this->attribute} = $value;
            return Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            return Html::textInput($this->name, $value, $this->options);
        }
    }

    private function _registerAssets()
    {
        $view = $this->getView();
        DateTimeAssets::register($view);
    }

}
