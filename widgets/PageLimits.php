<?php

namespace app\widgets;

use Yii;
use yii\helpers\ArrayHelper;

class PageLimits extends \yii\base\Widget
{

    public $pages;
    public $parameters = [];
    public $limits = [15, 12, 9, 6];
    private $_parameters = [];

    public function init()
    {

        $getParameters = Yii::$app->request->get();
        if (isset($getParameters[$this->pages->pageSizeParam])) {
            unset($getParameters[$this->pages->pageSizeParam]);
        }
        if (isset($getParameters[$this->pages->pageParam])) {
            unset($getParameters[$this->pages->pageParam]);
        }

        $this->_parameters = ArrayHelper::merge($getParameters, $this->parameters);
        $this->_parameters[0] = Yii::$app->controller->getRoute();
    }

    public function run()
    {
        return $this->render('page_limits', [
                    'pages' => $this->pages,
                    'parameters' => $this->_parameters,
                    'limits' => $this->limits
        ]);
    }

}
