<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;
use yii\helpers\Json;
use \yii\helpers\ArrayHelper;

class NotificationGritter extends Widget
{

    public $flash = null;
    public $options = [];
    private $defaultOptions = [
        'position' => 'bottom-right'
    ];
    public $preset = '';
    private $_presets = [
        'save' => [
            'title' => 'Сохранено',
            'image' => 'gritter-ok.png'
        ],
        'message' => [
            'image' => 'gritter-message.png'
        ],
        'error' => [
            'title' => 'Ошибка!',
            'sticky' => true,
            'image' => 'gritter-remove.png',
        ],
    ];

    public function init()
    {
        $this->options = ArrayHelper::merge($this->defaultOptions, $this->options);
        if (!is_null($this->flash) && \Yii::$app->session->hasFlash($this->flash)) {
            $this->options['text'] = \Yii::$app->session->getFlash($this->flash);
        }
    }

    public function run()
    {
        $config = $this->options;
        if (!isset($config['text'])) {
            return;
        }
        if (!empty($this->preset)) {
            if (isset($this->_presets[$this->preset])) {
                $config = ArrayHelper::merge($this->_presets[$this->preset], $config);
                $config['image'] = \app\modules\icms\assets\IcmsAsset::path('img/' . $config['image']);
            } else {
                throw new \Exception('Несуществующий пресет: ' . $this->preset);
            }
        }
        $jsonConfig = Json::encode($config);
        if (\Yii::$app->request->isPjax) {
            echo "<script> $.gritter.add({$jsonConfig});</script>";
        } else {
            $this->view->registerJs("$.gritter.add({$jsonConfig});");
        }
    }

}
