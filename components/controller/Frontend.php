<?php

namespace app\components\controller;

use Yii;
use app\components\IcmsHelper;
use app\models\Tree;

/**
 * Контроллер фронтэнда
 * @property array $menu Верхнее меню
 * @property array $menu_bottom Нижнее меню
 * @property array $bread Хлебные крошки
 * @property app\components\View $view Класс вьюхи
 */
class Frontend extends \yii\web\Controller
{

    public $menu;
    public $menu_bottom;
    public $bread;
    public $layout = 'textpage';

    public function beforeAction($action)
    {
        $this->view->h1 = Yii::$app->name;
        if (is_null(\Yii::$app->urlManager->pageId) === false) {
            $page = \app\models\Tree::findOne(\Yii::$app->urlManager->pageId);
            $this->view->tree = $page;

            $this->view->h1 = empty($page->h1_seo) === true ? $page->name_menu : $page->h1_seo;
            $this->view->title = empty($page->title_seo) === true ? $page->name_menu : $page->title_seo;
            $this->view->keywords = $page->keywords_seo;
            $this->view->description = $page->description_seo;

            $this->bread = IcmsHelper::getBreadCrumbsTree($page);
        }

        $this->menu = IcmsHelper::getMenu([], [0, 1], ['status' => Tree::STATUS_ACTIVE, 'in_menu' => 1]);
        $this->menu_bottom = IcmsHelper::getMenu([], [0, 1], ['status' => Tree::STATUS_ACTIVE, 'in_menu_bottom' => 1]);

        return parent::beforeAction($action);
    }
    
}
