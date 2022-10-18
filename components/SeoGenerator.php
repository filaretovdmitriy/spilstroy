<?php

namespace app\components;

use Yii;
use app\models\CatalogCategorie;
use app\models\Catalog;
use yii\helpers\ArrayHelper;

class SeoGenerator
{

    private $_cateogrie = null;
    private $_categoriesParameter = null;
    private $_catalogsParameter = null;
    private $_defaultParameters = [
        'start' => false,
        'override' => false,
        'title' => '',
        'description' => '',
        'keywords' => '',
    ];
    private $_codes = [
        'название категории' => 'name',
        'название товара' => 'name',
        'цена' => 'price',
        'артикул' => 'article',
    ];
    public $requestName = 'generator';

    public function __construct($categorieModel)
    {
        $this->_cateogrie = $categorieModel;
    }

    public function getParameters($parameters = false)
    {
        if ($parameters === false) {
            $parameters = Yii::$app->request->post($this->requestName, null);
        }
        if (isset($parameters['catalog'])) {
            $this->_catalogsParameter = ArrayHelper::merge($this->_defaultParameters, $parameters['catalog']);
        }
        if (isset($parameters['categories'])) {
            $this->_categoriesParameter = ArrayHelper::merge($this->_defaultParameters, $parameters['categories']);
        }
    }

    private function parseTemplate($templateString)
    {
        $temp = [];
        preg_match_all('/{{(.+?)}}/mi', $templateString, $temp);
        return $temp[1];
    }

    private function generateCatalog()
    {
        $title = isset($this->_catalogsParameter['title']) ? $this->_catalogsParameter['title'] : null;
        $description = isset($this->_catalogsParameter['description']) ? $this->_catalogsParameter['description'] : null;
        $keywords = isset($this->_catalogsParameter['keywords']) ? $this->_catalogsParameter['keywords'] : null;

        $codes = array_unique(array_merge($this->parseTemplate($title), $this->parseTemplate($description), $this->parseTemplate($keywords)));

        $categories = [$this->_cateogrie->id => $this->_cateogrie] + CatalogCategorie::getBranch($this->_cateogrie->id);
        $categorieNames = ArrayHelper::map($categories, 'id', 'name');
        $categoriesIds = array_keys($categories);
        $goods = Catalog::find()->andWhere(['in', 'catalog_categorie_id', $categoriesIds])->all();

        foreach ($goods as $good) {
            $titleUpdate = $title;
            $descriptionUpdate = $description;
            $keywordsUpdate = $keywords;
            foreach ($codes as $code) {
                if ($code === 'название категории') {
                    $value = $categorieNames[$good->catalog_categorie_id];
                } else {
                    $value = $good->{$this->_codes[$code]};
                }
                $titleUpdate = str_replace('{{' . $code . '}}', $value, $titleUpdate);
                $descriptionUpdate = str_replace('{{' . $code . '}}', $value, $descriptionUpdate);
                $keywordsUpdate = str_replace('{{' . $code . '}}', $value, $keywordsUpdate);
            }
            if (empty($good->title_seo) || $this->_catalogsParameter['override']) {
                $good->title_seo = trim($titleUpdate);
            }
            if (empty($good->description_seo) || $this->_catalogsParameter['override']) {
                $good->description_seo = trim($descriptionUpdate);
            }
            if (empty($good->keywords_seo) || $this->_catalogsParameter['override']) {
                $good->keywords_seo = trim($keywordsUpdate);
            }
            $good->save();
        }
    }

    private function generateCategorie()
    {
        $title = isset($this->_categoriesParameter['title']) ? $this->_categoriesParameter['title'] : null;
        $description = isset($this->_categoriesParameter['description']) ? $this->_categoriesParameter['description'] : null;
        $keywords = isset($this->_categoriesParameter['keywords']) ? $this->_categoriesParameter['keywords'] : null;

        $codes = array_unique(array_merge($this->parseTemplate($title), $this->parseTemplate($description), $this->parseTemplate($keywords)));

        $categories = [-1 => $this->_cateogrie] + CatalogCategorie::getBranch($this->_cateogrie->id);

        foreach ($categories as $categorie) {
            $titleUpdate = $title;
            $descriptionUpdate = $description;
            $keywordsUpdate = $keywords;
            foreach ($codes as $code) {
                $value = $categorie->{$this->_codes[$code]};
                $titleUpdate = str_replace('{{' . $code . '}}', $value, $titleUpdate);
                $descriptionUpdate = str_replace('{{' . $code . '}}', $value, $descriptionUpdate);
                $keywordsUpdate = str_replace('{{' . $code . '}}', $value, $keywordsUpdate);
            }
            if (empty($categorie->title_seo) || $this->_categoriesParameter['override']) {
                $categorie->title_seo = trim($titleUpdate);
            }
            if (empty($categorie->description_seo) || $this->_categoriesParameter['override']) {
                $categorie->description_seo = trim($descriptionUpdate);
            }
            if (empty($categorie->keywords_seo) || $this->_categoriesParameter['override']) {
                $categorie->keywords_seo = trim($keywordsUpdate);
            }
            $categorie->save();
        }
    }

    public function genereate()
    {
        if (empty($this->_catalogsParameter) && empty($this->_categoriesParameter)) {
            return;
        }

        if (!empty($this->_catalogsParameter)) {
            $this->generateCatalog();
        }

        if (!empty($this->_categoriesParameter)) {
            $this->generateCategorie();
        }
    }

}
