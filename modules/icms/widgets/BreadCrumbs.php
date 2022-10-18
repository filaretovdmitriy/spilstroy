<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;

class BreadCrumbs extends Widget
{

    public $crumbs = [];

    public function init()
    {
        if (empty($this->crumbs) === true && isset(\Yii::$app->view->params['breadCrumbs']) === true) {
            $this->crumbs = \Yii::$app->view->params['breadCrumbs']['crumbs'];
        }
    }

    public function run()
    {
        if (count($this->crumbs) > 0) {
            return $this->render('breadcrumbs', ['crumbs' => $this->crumbs]);
        } else {
            return '';
        }
    }

}
