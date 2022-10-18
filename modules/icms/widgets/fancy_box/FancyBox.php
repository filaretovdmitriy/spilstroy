<?php

namespace app\modules\icms\widgets\fancy_box;

use yii\base\Widget;
use yii\helpers\Json;
use yii\base\InvalidConfigException;
use yii\web\JsExpression;
use app\assets\FancyBoxAsset;

class FancyBox extends Widget
{

    public $target;
    public $helpers = false;
    public $mouse = true;
    public $config = [];
    public $group = [];
    public $icms = true;
    public $onCancel;
    public $beforeLoad;
    public $afterLoad;
    public $beforeShow;
    public $afterShow;
    public $beforeClose;
    public $afterClose;
    public $onUpdate;
    public $onPlayStart;
    public $onPlayEnd;
    private $defaultOptions = [
        'openEffect' => 'none',
        'closeEffect' => 'none',
        'helpers' => [
            'overlay' => [
                'locked' => false
            ]
        ],
        'tpl' => [
            'error' => '<p class="fancybox-error">Ошибка загрузки контента</p>',
            'closeBtn' => '<a title="Закрыть" class="fancybox-item fancybox-close" href="javascript:;"></a>',
            'next' => '<a title="Следующее" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
            'prev' => '<a title="Предыдущее" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
        ]
    ];

    public function init()
    {
        if (is_null($this->target)) {
            throw new InvalidConfigException('"Не установлен target');
        }

        if (!empty($this->onCancel)) {
            $this->defaultOptions['onCancel'] = new JsExpression($this->onCancel);
        }

        if (!empty($this->beforeLoad)) {
            $this->defaultOptions['beforeLoad'] = new JsExpression($this->beforeLoad);
        }

        if (!empty($this->afterLoad)) {
            $this->defaultOptions['afterLoad'] = new JsExpression($this->afterLoad);
        }

        if (!empty($this->beforeShow)) {
            $this->defaultOptions['beforeShow'] = new JsExpression($this->beforeShow);
        }

        if (!empty($this->afterShow)) {
            $this->defaultOptions['afterShow'] = new JsExpression($this->afterShow);
        }

        if (!empty($this->beforeClose)) {
            $this->defaultOptions['beforeClose'] = new JsExpression($this->beforeClose);
        }

        if (!empty($this->afterClose)) {
            $this->defaultOptions['afterClose'] = new JsExpression($this->afterClose);
        }

        if (!empty($this->onUpdate)) {
            $this->defaultOptions['onUpdate'] = new JsExpression($this->onUpdate);
        }

        if (!empty($this->onPlayStart)) {
            $this->defaultOptions['onPlayStart'] = new JsExpression($this->onPlayStart);
        }

        if (!empty($this->onPlayEnd)) {
            $this->defaultOptions['onPlayEnd'] = new JsExpression($this->onPlayEnd);
        }

        $this->registerClientScript();
    }

    public function run()
    {

        $config = Json::encode(\yii\helpers\ArrayHelper::merge($this->defaultOptions, $this->config));

        if (!empty($this->group)) {
            $config = Json::encode($this->group) . ',' . $config;
        }
        $this->view->registerJs("$('{$this->target}').fancybox({$config});");
    }

    public function registerClientScript()
    {
        $view = $this->getView();

        FancyBoxAsset::$customStyles = $this->icms;
        FancyBoxAsset::register($view);

        if ($this->mouse) {
            MousewheelAsset::register($view);
        }

        if ($this->helpers) {
            FancyBoxHelpersAsset::register($view);
        }
    }

}
