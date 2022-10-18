<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;
use app\models\AdminMenu;

class AdminMenuLeft extends Widget
{

    public function _registerJs()
    {
        $js = <<<JS
function updateMenuHeight() {
    var blockHeight = $('.sidebar_wrapper .sidebar_footer').outerHeight(true);
    menu = $('#main-left-menu');
    oldMenuHeight = menu.height();
    menu.height('auto');
    fullHeight = menu.height();
    menu.height('' + oldMenuHeight + 'px');
                
    $('.sidebar_wrapper .sidebar').children().not('#main-left-menu').each(function() {
        blockHeight += $(this).outerHeight(true);
    });
    pageHeight = $('body').height();
                
    if ((blockHeight + fullHeight) > pageHeight) {
        newMenuHeight = pageHeight - blockHeight;
        menu.height('' + newMenuHeight + 'px');
    } else {
        menu.height('auto');
    }
    menu.perfectScrollbar('update');
}

$('#main-left-menu').perfectScrollbar();
updateMenuHeight();
$(window).resize(updateMenuHeight);
JS;
        $this->view->registerJs($js);
    }

    public function run()
    {
        $this->_registerJs();
        $adminMenu = AdminMenu::find()
                ->andWhere(['pid' => 0, 'isActive' => 1])
                ->orderBy(['sort' => SORT_ASC])
                ->all();
        return $this->render('admin_menu_left', [
            'adminMenu' => $adminMenu
        ]);
    }

}
