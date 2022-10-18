<?php

namespace app\widgets;

use yii\base\Widget;
use app\models\CatalogCategorie;

class CatalogMenu extends Widget
{

    public function run()
    {
        $categories = CatalogCategorie::find()
                ->andWhere([
                    'status' => CatalogCategorie::STATUS_ACTIVE,
                    'pid' => 0
                ])
                ->orderBy(['sort' => SORT_ASC])
                ->all();

        if (!empty($categories)) {
            return $this->render('catalog_menu', [
                        'categories' => $categories
            ]);
        }
    }

}
