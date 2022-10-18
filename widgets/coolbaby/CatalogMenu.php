<?php

namespace app\widgets\coolbaby;

use app\models\CatalogCategorie;
use yii\base\Widget;

class CatalogMenu extends Widget
{

    public $menu = [];
    public $offMode = false;

    public function init()
    {
        if (empty($this->menu)) {
            if (\Yii::$app->cache->exists('catalogMenu')) {
                $this->menu = \Yii::$app->cache->get('catalogMenu');
            }
        }
    }

    public function run()
    {
        $categories = $this->menu;

        if (empty($categories) || YII_DEBUG) {
            $categories = [];
            $categoriesBase = CatalogCategorie::find()->andWhere(['status' => CatalogCategorie::STATUS_ACTIVE, 'pid' => 0])->orderBy(['sort' => SORT_ASC])->all();
            foreach ($categoriesBase as $categorie) {
                $catResult = [
                    'name' => $categorie->name,
                    'alias' => $categorie->alias,
                ];

                $childs = $categorie->getCategories()->andWhere(['status' => CatalogCategorie::STATUS_ACTIVE])->orderBy(['sort' => SORT_ASC])->all();
                foreach ($childs as $child) {
                    $catResult['childs'][] = [
                        'name' => $child->name,
                        'alias' => $child->alias,
                    ];
                }
                $categories[] = $catResult;
            }
            \Yii::$app->cache->delete('catalogMenu');
            \Yii::$app->cache->add('catalogMenu', $categories);
        }

        if ($this->offMode === false) {
            return $this->render('catalog_menu', [
                        'categories' => $categories
            ]);
        } else {
            return $this->render('catalog_menu_off_canvas', [
                        'categories' => $categories
            ]);
        }
    }

}
