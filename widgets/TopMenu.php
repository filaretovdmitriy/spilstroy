<?php

namespace app\widgets;

use yii\base\Widget;
use app\models\Tree;

class TopMenu extends Widget
{

    public function run()
    {
        $pages = Tree::find()->andWhere(['status' => Tree::STATUS_ACTIVE, 'in_menu' => 1])->orderBy(['sort' => SORT_ASC])->limit(6)->all();

        if (!empty($pages)) {
            return $this->render('top_menu', [
                        'pages' => $pages
            ]);
        }
    }

}
