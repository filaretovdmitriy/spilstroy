<?php

namespace app\widgets\coolbaby;

use app\models\Catalog;
use app\models\CatalogCategorie;
use app\components\IcmsHelper;
use yii\base\Widget;

class CatalogPopular extends Widget
{

    public function run()
    {
        $categorieAliases = IcmsHelper::map(CatalogCategorie::find()->select(['id', 'alias'])->all(), 'id', 'alias');

        $goods = Catalog::find()->andWhere(['status' => Catalog::STATUS_ACTIVE, 'is_popular' => 1])->orderBy(['sort' => SORT_ASC])->all();

        return $this->render('catalog_popular', [
                    'goods' => $goods,
                    'categorieAliases' => $categorieAliases
        ]);
    }

}
