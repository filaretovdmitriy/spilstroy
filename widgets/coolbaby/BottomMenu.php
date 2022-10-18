<?php

namespace app\widgets\coolbaby;

use yii\base\Widget;
use app\models\Tree;

class BottomMenu extends Widget
{

    public function run()
    {
        $pages = Tree::find()->andWhere(['status' => Tree::STATUS_ACTIVE, 'in_menu' => 1])->orderBy(['sort' => SORT_ASC])->limit(4)->all();

        if (!empty($pages)) {
            return $this->render('bottom_menu', [
                        'pages' => $pages
            ]);
        }
    }

}
