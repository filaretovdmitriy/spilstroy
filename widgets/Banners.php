<?php

namespace app\widgets;

use yii\base\Widget;
use app\models\Banner;

class Banners extends Widget
{

    public $banner_group_id;
    public $limit = 1;

    public function run()
    {

        $items = Banner::find()
                ->andWhere([
                    'status' => Banner::STATUS_ACTIVE,
                    'banner_group_id' => $this->banner_group_id
                ])
                ->limit($this->limit)
                ->orderBy(['sort' => SORT_ASC])
                ->all();
        return $this->render('banners', [
            'items' => $items
        ]);
    }

}
