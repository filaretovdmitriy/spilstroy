<?php

namespace app\widgets\coolbaby;

use app\models\Slide;
use yii\base\Widget;

class Brands extends Widget
{

    public $sliderId = 2;

    public function run()
    {
        $slides = Slide::find()->andWhere(['status' => Slide::STATUS_ACTIVE, 'slider_id' => $this->sliderId])->orderBy(['sort' => SORT_ASC])->all();

        if (!empty($slides)) {
            return $this->render('brands', [
                        'slides' => $slides
            ]);
        }
    }

}
