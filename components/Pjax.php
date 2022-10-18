<?php

namespace app\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;

class Pjax extends \yii\widgets\Pjax
{

    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if ($this->requiresPjax()) {
            ob_start();
            ob_implicit_flush(false);
            $view = $this->getView();
            $view->beginPage();
            $view->head();
            $view->beginBody();
            if ($view->title !== null) {
                echo Html::tag('title', Html::encode($view->title));
            }
        } else {
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'div');
            echo Html::beginTag($tag, array_merge([
                'data-pjax-container' => '',
                'data-pjax-push-state' => $this->enablePushState,
                'data-pjax-replace-state' => $this->enableReplaceState,
                'data-pjax-timeout' => $this->timeout,
                'data-pjax-scrollto' => $this->scrollTo,
                            ], $options));
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->requiresPjax()) {
            echo Html::endTag(ArrayHelper::remove($this->options, 'tag', 'div'));
            $this->registerClientScript();

            return;
        }

        $view = $this->getView();
        $view->endBody();

        $view->endPage(true);

        $content = ob_get_clean();

        // only need the content enclosed within this widget
        $response = Yii::$app->getResponse();
        $response->clearOutputBuffers();
        $response->setStatusCode(200);
        $response->format = Response::FORMAT_HTML;
        $response->content = $content;
        $response->send();

        Yii::$app->end();
    }

}
