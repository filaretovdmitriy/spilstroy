<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;
use app\models\AdminMenu;

class AdminMenuAdvanced extends Widget
{

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $controllerName = \Yii::$app->controller->id;
        $adminMenu = AdminMenu::find()->andWhere('pid != :pid AND isActive = :isActive AND in_button = :in_button AND controller = :controller', ['pid' => 0, 'isActive' => 1, 'in_button' => 0, 'controller' => $controllerName])
                        ->orderBy('sort ASC')->all();
        $adminMenuByRole = [];
        foreach ($adminMenu as $menuElem) {
            if ($menuElem->role != '' && !\Yii::$app->user->can($menuElem->role)) {
                continue;
            }
            $adminMenuByRole[] = $menuElem;
        }

        if (count($adminMenuByRole) > 0) {
            return $this->render('admin_menu_advenced', ['adminMenu' => $adminMenuByRole]);
        }
    }

}
