<?php

namespace app\widgets;

use yii\base\Widget;

class CatalogPreview extends Widget
{

    public $condition = [];
    public $sort = 'price asc';
    public $limit = 1;

    public function run()
    {

        $items = \app\models\Catalog::find()->andWhere($this->condition)->limit($this->limit)->orderBy($this->sort)->all();
        return $this->render('catalog_preview', [
            'items' => $items
        ]);
    }

}
