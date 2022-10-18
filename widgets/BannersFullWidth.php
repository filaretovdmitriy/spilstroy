<?php

namespace app\widgets;

use yii\base\Widget;
use app\models\Banner;

class BannersFullWidth extends Widget
{

    public $banner_group_id;
    public $limit = 1;

    public function run()
    {
        $items = Banner::find()->where([
                    'status' => Banner::STATUS_ACTIVE,
                    'banner_group_id' => $this->banner_group_id
                ])
                ->limit($this->limit)
                ->orderBy(['sort' => SORT_ASC])
                ->all();
        return $this->render('banners_full_width', [
            'items' => $items
        ]);
    }

}
