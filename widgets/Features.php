<?php

namespace app\widgets;

use yii\base\Widget;
use app\models\Banner;

class Features extends Widget
{

    public function run()
    {
        $Banners = Banner::find()
                        ->andWhere([
                            'banner_group_id' => 2,
                            'status' => Banner::STATUS_ACTIVE,
                        ])->all();

        

        if (!empty($Banners)) {
            return $this->render('features', [
                        'banners' => $Banners
            ]);
        }
    }

}
