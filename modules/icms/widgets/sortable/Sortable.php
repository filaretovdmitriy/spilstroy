<?php

namespace app\modules\icms\widgets\sortable;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Sortable extends \yii\jui\Sortable
{

    public function init()
    {
        parent::init();

        SortableAssets::register($this->view);
    }

    public function run()
    {
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        $html = Html::beginTag($tag, $options) . "\n";
        $html .= $this->renderItems() . "\n";
        $html .= Html::endTag($tag) . "\n";
        $this->registerWidget('sortable');
        return $html;
    }

}
