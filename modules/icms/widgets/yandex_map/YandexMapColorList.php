<?php

namespace app\modules\icms\widgets\yandex_map;

use yii\widgets\InputWidget;
use app\modules\icms\widgets\Radio;
use yii\helpers\Html;

class YandexMapColorList extends InputWidget
{

    public $colors = [
        'blue' => 'blueStretchyIcon',
        'darkblue' => 'darkblueStretchyIcon',
        'darkgreen' => 'darkgreenStretchyIcon',
        'darkorange' => 'darkorangeStretchyIcon',
        'green' => 'greenStretchyIcon',
        'grey' => 'greyStretchyIcon',
        'lightblue' => 'lightblueStretchyIcon',
        'night' => 'nightStretchyIcon',
        'orange' => 'orangeStretchyIcon',
        'pink' => 'pinkStretchyIcon',
        'red' => 'redStretchyIcon',
        'violet' => 'violetStretchyIcon',
        'white' => 'whiteStretchyIcon',
        'yellow' => 'yellowStretchyIcon',
        'brown' => 'brownStretchyIcon',
        'black' => 'blackStretchyIcon',
    ];

    public function init()
    {
        parent::init();

        $name = Html::getInputName($this->model, $this->attribute);
        echo Html::tag('div', Radio::widget([
                    'name' => $name,
                    'value' => '',
                    'checked' => empty($this->model->{$this->attribute}),
                    'label' => 'По умолчанию'
                ]), ['class' => 'ya-color-wrapper']);
        foreach ($this->colors as $color) {
            echo Html::tag('div', Radio::widget([
                        'name' => $name,
                        'value' => $color,
                        'checked' => $this->model->{$this->attribute} === $color,
                        'label' => Html::tag('i', '', ['class' => 'ya-color ya-color-' . $color])
                    ]), ['class' => 'ya-color-wrapper']);
        }
    }

}
