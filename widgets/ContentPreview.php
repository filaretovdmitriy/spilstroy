<?php

namespace app\widgets;

use yii\base\Widget;

class ContentPreview extends Widget
{

    public $content_categorie_id;
    public $limit = 10;

    public function run()
    {

        $items = \app\models\Content::find()->andWhere('status=1 and content_categorie_id=' . $this->content_categorie_id)->limit($this->limit)->orderBy('g_date desc')->all();
        return $this->render('content_preview', [
            'items' => $items
        ]);
    }

}
