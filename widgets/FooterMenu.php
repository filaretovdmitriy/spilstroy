<?php

namespace app\widgets;

use yii\base\Widget;
use app\models\Tree;

class FooterMenu extends Widget
{

    public function run()
    {
        $pages = Tree::find()->andWhere(['status' => Tree::STATUS_ACTIVE, 'in_menu_bottom' => 1])->orderBy(['sort' => SORT_ASC])->limit(6)->all();

        if (!empty($pages)) {
            return $this->render('footer_menu', [
                        'pages' => $pages
            ]);
        }
    }

}
