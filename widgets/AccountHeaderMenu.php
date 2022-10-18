<?php

namespace app\widgets;

use yii\base\Widget;

class AccountHeaderMenu extends Widget
{

    public function run()
    {
        return $this->render('account_header_menu');
    }

}
