<?php

namespace app\modules\icms\widgets\yandex_map;

use yii\base\Widget;
use yii\helpers\Html;

class YandexMapEdit extends Widget
{

    const TYPE_MAP = 1;
    const TYPE_MARK = 2;

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

    /**
     * Текущая редактируемая метка
     * @var \app\models\MapMark
     */
    public $mark;

    /**
     * Один из типов редактирования карты<br>
     * TYPE_MAP - Редартирование карты<br>
     * TYPE_MARK - Редактирование метки
     * @var integer
     */
    public $type;
    public $attributeZoom = 'zoom';
    public $attributeCenterX = 'center_x';
    public $attributeCenterY = 'center_y';
    public $attributeMarkX = 'coordinate_x';
    public $attributeMarkY = 'coordinate_y';
    public $attributeMarkText = 'content';

    /**
     * Сгенерированный скрипт
     * @var string
     */
    private $_js = '';

    /**
     * Генерирует скрипты для изменения общих параметров карты (зума и центрирования)
     */
    protected function generateMapEditScripts()
    {
        YandexMapAssets::register($this->view);

        $inputZoomId = Html::getInputId($this->map, $this->attributeZoom);
        $inputCenterXId = Html::getInputId($this->map, $this->attributeCenterX);
        $inputCenterYId = Html::getInputId($this->map, $this->attributeCenterY);
        $this->_js .= <<<JS
map_{$this->id}.events.add('boundschange', function (event) {
    if (event.get('newZoom') != event.get('oldZoom')) {
        $('#{$inputZoomId}').val(map_{$this->id}.getZoom());
    }

    if (event.get('newCenter') != event.get('oldCenter')) {
        coords = map_{$this->id}.getCenter();
        $('#{$inputCenterXId}').val(coords[0]);
        $('#{$inputCenterYId}').val(coords[1]);
    }
});
map_{$this->id}.controls.add(new CrossControl);
JS;
        echo Html::activeHiddenInput($this->map, $this->attributeZoom);
        echo Html::activeHiddenInput($this->map, $this->attributeCenterX);
        echo Html::activeHiddenInput($this->map, $this->attributeCenterY);
    }

    /**
     * Генерирует скрипты для изменения метки (положения)
     */
    protected function generateMarkEditScripts()
    {
        $mark_x = $this->mark->{$this->attributeMarkX} ?: 0;
        $mark_y = $this->mark->{$this->attributeMarkY} ?: 0;

        $xMarkInputId = Html::getInputId($this->mark, $this->attributeMarkX);
        $yMarkInputId = Html::getInputId($this->mark, $this->attributeMarkY);

        $this->_js .= <<<JS
markX = {$mark_x};
markY = {$mark_y};
coords = map_{$this->id}.getCenter();
if (markX == 0 || markY == 0) {
    markX = coords[0];
    markY = coords[1];
}
myPlacemark = new ymaps.Placemark([markX, markY],{}, {preset: "islands#redDotIcon", draggable: true});

map_{$this->id}.geoObjects.add(myPlacemark);

myPlacemark.events.add('dragend', function (e) {
    coords = this.geometry.getCoordinates();
    $('#{$xMarkInputId}').val(coords[0]);
    $('#{$yMarkInputId}').val(coords[1]);
}, myPlacemark);

$('#{$xMarkInputId}').val(markX);
$('#{$yMarkInputId}').val(markY);
JS;
        echo Html::activeHiddenInput($this->mark, $this->attributeMarkX);
        echo Html::activeHiddenInput($this->mark, $this->attributeMarkY);
    }

    public function init()
    {
        $view = $this->getView();
        $view->registerJsFile('https://api-maps.yandex.ru/2.1/?load=package.full&lang=' . $this->lang);
        $zoom = $this->map->zoom ?: 12;
        if ($this->map->isNewRecord || (empty($this->map->center_x) || empty($this->map->center_y))) {
            $this->_js = <<<JS
ymaps.geolocation.get().then(function (res) {
    var mapContainer = $('#{$this->id}'),
        bounds = res.geoObjects.get(0).properties.get('boundedBy');
    mapState = ymaps.util.bounds.getCenterAndZoom(
        bounds,
        [mapContainer.width(), mapContainer.height()]
    );
    map_{$this->id}.setCenter(mapState.center, mapState.zoom);
}, function (e) {});
var map_{$this->id} = new ymaps.Map('{$this->id}', {
    center: [57.76953534, 40.92887432], //Кострома по умолчанию
    zoom: {$zoom},
    controls: ['zoomControl', 'searchControl']
});
JS;
        } else {
            $this->_js = <<<JS
var map_{$this->id} = new ymaps.Map('{$this->id}', {
    center: [{$this->map->center_x}, {$this->map->center_y}],
    zoom: {$zoom},
    controls: ['zoomControl', 'searchControl']
});
JS;
        }
    }

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

    public function run()
    {

        if ($this->type == self::TYPE_MAP) {
            $this->generateMapEditScripts();
        }
        if ($this->type == self::TYPE_MARK) {
            $this->generateMarkEditScripts();
        }

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
        $this->view->registerJs($this->_js, \yii\web\View::POS_END, 'yandex-map-edit-' . $this->type . '-' . $this->getId());

        return Html::tag('div', '', [
                    'id' => $this->getId(),
                    'style' => 'width: 100%; height: 400px',
        ]);
    }

}
