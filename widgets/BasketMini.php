<?php

namespace app\widgets;

use yii\base\Widget;
use app\models\CatalogOrder;

class BasketMini extends Widget
{

    public function run()
    {
        $count = 0;
        $price = 0;
        $order = CatalogOrder::getUserOrder(false);
        if (!is_null($order)) {
            $count = $order->total_count;
            $price = $order->total_price;
        }

        return $this->render('basket_mini', [
                    'count' => $count,
                    'price' => $price
        ]);
    }

}
