<?php

namespace app\widgets\coolbaby;

use yii\base\Widget;
use app\models\Slide;

class SliderCategories extends Widget
{

    public $sliderId;

    public function init()
    {
        if (is_null($this->sliderId)) {
            throw new Exception('Не задан слайдер', 500);
        }
    }

    public function run()
    {
        $items = Slide::find()
                ->andWhere(['status' => Slide::STATUS_ACTIVE, 'slider_id' => $this->sliderId])
                ->orderBy(['sort' => SORT_ASC])
                ->all();

        if (!empty($items)) {
            return $this->render('slider_categories', [
                        'items' => $items,
                        'id' => $this->sliderId
            ]);
        } else {
            return '';
        }
    }

}
