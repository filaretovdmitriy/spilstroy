<?php

namespace app\widgets\coolbaby;

use yii\base\Widget;
use app\models\Content;

class Blog extends Widget
{

    public $contentId = 2;

    public function run()
    {
        $items = Content::find()
                ->andWhere(['status' => Content::STATUS_ACTIVE, 'content_categorie_id' => $this->contentId])
                ->andWhere('g_date <= :currentDate', ['currentDate' => date('Y-m-d H:i:s')])
                ->orderBy(['sort' => SORT_ASC])
                ->limit(10)
                ->all();

        if (!empty($items)) {
            return $this->render('blog', [
                        'items' => $items
            ]);
        } else {
            return '';
        }
    }

}
