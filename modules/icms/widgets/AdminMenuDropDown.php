<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;
use app\models\AdminMenu;

class AdminMenuDropDown extends Widget
{

    public $pids = [];

    public function init()
    {

        if (count($this->pids) == 0 && isset(\Yii::$app->view->params['adminMenuDropDown'])) {
            $this->pids = \Yii::$app->view->params['adminMenuDropDown']['pids'];
        }

        $js = <<<JS
$(document).on('click','.add span',function () {
    if ($(this).parent().hasClass('add-single') === true) {
        return true;
    }
    if ($(this).parent().hasClass('actv')){
        $(this).parent().removeClass("actv");
        $('.subnav').hide();
    }
    else{
        $(this).parent().addClass("actv");
        $('.subnav').slideToggle(250);
    }
    return false;
});
JS;
        if (!\Yii::$app->request->isPjax) {
            $this->view->registerJs($js, \yii\web\View::POS_READY, 'admin-menu-drop-down');
        }
    }

    public function run()
    {
        $controllerName = \Yii::$app->controller->id;
        $adminMenu = AdminMenu::find()
                        ->andWhere('pid != :pid AND isActive = :isActive AND in_button = :in_button AND controller = :controller', ['pid' => 0, 'isActive' => 1, 'in_button' => 1, 'controller' => $controllerName])
                        ->orderBy('sort ASC')->all();
        $adminMenuByRole = [];
        foreach ($adminMenu as $menuElem) {
            if ($menuElem->role != '' && !\Yii::$app->user->can($menuElem->role)) {
                continue;
            }
            $adminMenuByRole[] = $menuElem;
        }

        if (count($adminMenuByRole) > 0) {
            return $this->render('admin_menu_dropdown', ['adminMenu' => $adminMenuByRole, 'params' => $this->pids]);
        }
    }

}
