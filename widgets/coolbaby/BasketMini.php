<?php

namespace app\widgets\coolbaby;

use yii\base\Widget;
use app\models\CatalogOrder;

class BasketMini extends Widget
{

    public function run()
    {
        $count = 0;
        $order = CatalogOrder::getUserOrder(false);
        if (!is_null($order)) {
            $count = $order->total_count;
        }

        return $this->render('basket_mini', [
                    'count' => $count
        ]);
    }

}
