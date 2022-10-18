<?php

namespace app\widgets;

use app\models\CatalogCategorie;
use yii\base\Widget;
use yii\helpers\Html;

class CatalogMenu extends Widget
{

    public $menu = [];
    public $active = null;

    public function init()
    {
        if (empty($this->menu)) {
            if (\Yii::$app->cache->exists('catalogMenu')) {
                $this->menu = \Yii::$app->cache->get('catalogMenu');
            }
        }

        if (is_null($this->active)) {
            $this->active = \Yii::$app->request->get('catalog_categorie_alias', null);
        }
    }

    private function _getCategoriesTreeArray($pid = 0)
    {
        $categories = CatalogCategorie::find()
                ->andWhere([
                    'status' => CatalogCategorie::STATUS_ACTIVE,
                    'pid' => $pid
                ])
                ->orderBy(['sort' => SORT_ASC])
                ->all();

        $result = [];
        foreach ($categories as $categorie) {
            $categorieInfo = [
                'name' => $categorie->name,
                'alias' => $categorie->alias,
            ];

            $childrens = $this->_getCategoriesTreeArray($categorie->id);

            if (!empty($childrens)) {
                $categorieInfo['childrens'] = $childrens;
            }

            $result[$categorie->id] = $categorieInfo;
        }

        return $result;
    }

    private function _renderChildCategorie($categories)
    {
        $html = '';
        foreach ($categories as $categorie) {
            $active = $categorie['alias'] === $this->active;
            $categorieHtml = Html::a($categorie['name'], ['site/catalog', 'catalog_categorie_alias' => $categorie['alias']]);
            $class = '';
            if (isset($categorie['childrens'])) {
                $categorieInfo = $this->_renderChildCategorie($categorie['childrens']);
                $categorieHtml .= $categorieInfo['html'];
                $class = 'dropdown-submenu';
                $active = $categorieInfo['active'] || $active;
            }

            $html .= Html::tag('li', $categorieHtml, ['class' => $class . ($active ? ' active' : '')]);
        }

        return [
            'active' => $active,
            'html' => Html::tag('ul', $html, ['class' => 'dropdown-menu'])
        ];
    }

    public function run()
    {
        $categories = $this->menu;

        if (empty($categories)) {
            $categories = $this->_getCategoriesTreeArray();

            \Yii::$app->cache->add('catalogMenu', $categories);
        }

        $html = '';
        foreach ($categories as $categorie) {

            $active = $categorie['alias'] === $this->active;

            $categorieHtml = Html::a($categorie['name'], ['site/catalog', 'catalog_categorie_alias' => $categorie['alias']]);
            if (isset($categorie['childrens'])) {
                $categorieInfo = $this->_renderChildCategorie($categorie['childrens']);
                $categorieHtml .= $categorieInfo['html'];
                $active = $active || $categorieInfo['active'];
            }
            $html .= Html::tag('li', $categorieHtml, ['class' => 'dropdown' . ($active ? ' active' : '')]);
        }

        return Html::tag('ul', $html, ['class' => 'nav navbar-nav']);
    }

}
