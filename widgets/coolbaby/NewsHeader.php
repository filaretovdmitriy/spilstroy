<?php

namespace app\widgets\coolbaby;

use yii\base\Widget;
use app\models\Content;

class NewsHeader extends Widget
{

    public $contentId;

    public function init()
    {
        if (is_null($this->contentId)) {
            throw new Exception('Не задана группа новостей', 500);
        }
    }

    public function run()
    {
        $items = Content::find()
                ->andWhere(['status' => Content::STATUS_ACTIVE, 'content_categorie_id' => $this->contentId])
                ->andWhere('g_date <= :currentDate', ['currentDate' => date('Y-m-d H:i:s')])
                ->orderBy(['sort' => SORT_ASC])
                ->all();

        if (!empty($items)) {
            return $this->render('news_header', [
                        'items' => $items
            ]);
        } else {
            return '';
        }
    }

}
