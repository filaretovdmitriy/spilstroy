<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class YandexMap extends Widget
{

    /**
     * Локализация карты<br>
     * Сейчас поддерживаются API:<br>
     * ru_RU, en_US, ru_UA, uk_UA, tr_TR
     * @var string
     */
    public $lang = 'ru_RU';

    /**
     * Модель карты
     * @var \app\models\Map
     */
    public $map;

    /**
     * Массив меток карты
     * @var array
     */
    public $marks = [];
    public $attributeZoom = 'zoom';
    public $attributeCenterX = 'center_x';
    public $attributeCenterY = 'center_y';
    public $attributeMarkX = 'coordinate_x';
    public $attributeMarkY = 'coordinate_y';
    public $attributeMarkText = 'content';
    public $options = [
        'style' => 'width: 100%; height: 400px',
    ];
    public $mapOptions = [
        'controls' => ['smallMapDefaultSet'],
    ];
    private $_js = '';

    private function addDefaultMark($mark)
    {
        $mark_x = $mark->{$this->attributeMarkX};
        $mark_y = $mark->{$this->attributeMarkY};
        $preset = $mark->color ?: $this->map->mark_default_color;
        if (empty($preset) === true) {
            $preset = 'blueStretchyIcon';
        }
        
        $markIconContent = Html::tag('div', $mark->name);
        $mark_text = \app\components\IcmsHelper::JS_quote(nl2br($mark->{$this->attributeMarkText}));
        $this->_js .= <<<JS
map_{$this->id}.geoObjects.add(
    new ymaps.Placemark(
        [{$mark_x}, {$mark_y}],
        {
            iconContent: '{$markIconContent}',
            hintContent: '{$mark->name}',
            balloonContentHeader: '{$mark->name}',
            balloonContentBody: '{$mark_text}'
        },
        {
            preset: 'islands#{$preset}'
        }
    )
);
JS;
    }

    private function addImagedMark($mark)
    {
        $mark_x = $mark->{$this->attributeMarkX};
        $mark_y = $mark->{$this->attributeMarkY};
        $mark_text = \app\components\IcmsHelper::JS_quote(nl2br($mark->{$this->attributeMarkText}));
        $image = $mark->getPath('image');
        $this->_js .= <<<JS
map_{$this->id}.geoObjects.add(
    new ymaps.Placemark(
        [{$mark_x}, {$mark_y}],
        {
            hintContent: '{$mark->name}',
            balloonContentHeader: '{$mark->name}',
            balloonContentBody: '{$mark_text}'
        },
        {
            iconLayout: 'default#image',
            iconImageHref: '{$image}',
            iconImageSize: [{$mark->image_width}, {$mark->image_height}],
            iconImageOffset: [-{$mark->image_x}, -{$mark->image_y}]
        }
    )
);
JS;
    }

    public function init()
    {
        $view = $this->getView();
        $view->registerJsFile('https://api-maps.yandex.ru/2.1/?load=package.full&lang=' . $this->lang);

        $this->mapOptions['center'] = [$this->map->center_x, $this->map->center_y];
        $this->mapOptions['zoom'] = isset($this->mapOptions['zoom']) ? $this->mapOptions['zoom'] : $this->map->zoom;
        $controlsDefault = [];
        $controlsCustom = '';
        if (isset($this->mapOptions['controls'])) {
            foreach ($this->mapOptions['controls'] as $key => $control) {
                if (is_numeric($key)) {
                    $controlsDefault[] = $control;
                } else {
                    $options = \yii\helpers\Json::encode($control);
                    $controlsCustom .= <<<JS
map_{$this->id}.controls.add('{$key}', $options);
JS;
                }
            }
            $this->mapOptions['controls'] = $controlsDefault;
        }
        $controls = \yii\helpers\Json::encode($this->mapOptions);
        $this->_js = <<<JS
map_{$this->id} = new ymaps.Map('{$this->id}', {$controls});
{$controlsCustom}
JS;
    }

    public function run()
    {

        foreach ($this->marks as $mark) {
            if (!empty($this->mark) && $this->type == self::TYPE_MARK && $mark->id == $this->mark->id) {
                continue;
            }
            if (!empty($mark->image)) {
                $this->addImagedMark($mark);
            } else {
                $this->addDefaultMark($mark);
            }
        }

        $this->_js = <<<JS
var map_{$this->id};
ymaps.ready(function () {
    {$this->_js}
});
JS;
        $this->view->registerJs($this->_js, \yii\web\View::POS_END, 'yandex-map-' . $this->getId());
        $this->options['id'] = $this->getId();
        return Html::tag('div', '', $this->options);
    }

}
