<?php

namespace app\widgets\slick;

use app\models\Slide;
use app\widgets\slick\SlickAssets;
use yii\base\Widget;

class Slick extends Widget
{

    public $id;
    public $slidesToShow = 1;
    public $responsive = '';

    public function init()
    {
        SlickAssets::register($this->view);
    }

    public function run()
    {
        $js = <<<JS
            $('#slick_$this->id').slick({
                adaptiveHeight: true,
                slidesToShow:{$this->slidesToShow}{$this->responsive}
            });
JS;

        $this->view->registerJs($js, \yii\web\View::POS_READY, 'slick_' . $this->id);
        $items = Slide::find()->andWhere(['status' => 1, 'slider_id' => $this->id])->orderBy('sort')->all();
        echo $this->render('slick', [
            'items' => $items,
            'id' => $this->id
        ]);
    }

}
