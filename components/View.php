<?php

namespace app\components;

class View extends \rmrevin\yii\minify\View
{
    /**
     * @var string Кеш метрик
     */
    const METRIC_CASHE = 'metrics';

    /**
     * @var \app\models\Tree текущая страница
     */
    public $tree = null;

    /**
     * @var string Описание
     */
    public $description = '';

    /**
     * @var string Ключевые слова
     */
    public $keywords = '';

    /**
     * @var string Заголок H1 страницы
     */
    public $h1 = '';

    /**
     * Подключение скриптов метрик
     */
    private function metricsInit()
    {
        if (\Yii::$app->request->isAjax === true || \Yii::$app->request->isPjax === true) {
            return;
        }
        $metrics = \Yii::$app->cache->get(self::METRIC_CASHE);
        if ($metrics === false) {
            $metrics = \app\models\Key::getGroup(12);
            \Yii::$app->cache->add(self::METRIC_CASHE, $metrics);
        }
        foreach ($metrics as $name => $metric) {
            $this->registerJs(strip_tags($metric), \yii\web\View::POS_END, $name);
        }
    }

    public function init()
    {
        $user = \Yii::$app->getUser();
        if ($user->isGuest === false && $user->can('developer') === true) {
            $this->enableMinify = false;
        }

        $this->on(self::EVENT_BEGIN_PAGE, function($event) {
            /* @var $event \yii\base\Event */
            /* @var $view \app\components\View */
            $view = $event->sender;

            if (empty($view->description) === false) {
                $view->registerMetaTag(['content' => $view->description, 'name' => 'description']);
            }
            if (empty($view->keywords) === false) {
                $view->registerMetaTag(['content' => $view->keywords, 'name' => 'keywords']);
            }

            $request = \Yii::$app->getRequest();
            if ($request instanceof \yii\web\Request && $request->enableCsrfValidation === true) {
                $view->registerCsrfMetaTags();
            }

        });

        parent::init();
        $this->metricsInit();
    }

}
