<?php

namespace app\widgets\coolbaby;

use yii\base\Widget;
use app\models\Banner;

class BannersCircle extends Widget
{

    public $groupId;

    public function init()
    {
        if (is_null($this->groupId)) {
            throw new Exception('Не задана группа баннеров для вывода', 500);
        }
    }

    public function run()
    {
        $items = Banner::find()
                ->andWhere(['status' => Banner::STATUS_ACTIVE, 'banner_group_id' => $this->groupId])
                ->limit(3)
                ->orderBy(['sort' => SORT_ASC])
                ->all();

        if (!empty($items)) {
            return $this->render('banners_circle', [
                        'items' => $items,
                        'blockId' => $this->id . '-banner-circle'
            ]);
        } else {
            return '';
        }
    }

}
