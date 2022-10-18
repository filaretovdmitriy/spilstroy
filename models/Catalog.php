<?php

namespace app\models;

use app\components\IcmsHelper;
use yii\behaviors\TimestampBehavior;
use app\models\CatalogCategorie;
use app\models\CatalogGallery;
use yii\helpers\ArrayHelper;

class Catalog extends \app\components\db\ActiveRecordFiles
{

    public $imageFields = [
        'image' => ['catalog', 'catalog']
    ];
    private $_propsCode;
    private $_props;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название', 'on' => ['default']],
            ['alias', 'required', 'message' => 'Введите название', 'on' => ['default']],
            ['alias', 'match', 'pattern' => '/[a-zA-Z0-9_]+$/', 'message' => 'Только латинские буквы и цифры', 'on' => ['default']],
            ['status', 'in', 'range' => [1, 2], 'message' => 'Выберите правильное значение',],
            ['status', 'default', 'value' => 1],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            ['price', 'integer', 'message' => 'Введите целое число'],
            ['moscowprice', 'integer', 'message' => 'Введите целое число'],
            ['price_old', 'integer', 'message' => 'Введите целое число'],
            [['content', 'title_seo', 'description_seo', 'keywords_seo', 'auto_url', 'is_popular', 'article'], 'safe', 'on' => 'default'],
            ['catalog_categorie_id', 'exist', 'targetClass' => CatalogCategorie::class, 'targetAttribute' => 'id'],
            [
                '!image', 'file',
                'extensions' => ['png', 'jpg', 'gif'], 'wrongExtension' => 'Доступные форматы: {extensions}',
            ],
        ];
    }

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['name'],
        ];
    }

    public function getImages()
    {
        return $this->hasMany(CatalogGallery::class, ['catalog_id' => 'id']);
    }

    public function getFeedbacks()
    {
        return $this->hasMany(Feedback::class, ['catalog_id' => 'id']);
    }

    public function getFeedbacksPublic()
    {
        return $this->hasMany(Feedback::class, ['catalog_id' => 'id'])->andWhere(['status' => Feedback::STATUS_ACTIVE]);
    }

    public function getCategorie()
    {
        return $this->hasOne(CatalogCategorie::class, ['id' => 'catalog_categorie_id']);
    }

    public function getPropsValues()
    {
        return $this->hasMany(CatalogProp::class, ['catalog_id' => 'id']);
    }

    public function getSkus()
    {
        return $this->hasMany(CatalogSku::class, ['catalog_id' => 'id']);
    }

    public function getRelatedGoods()
    {
        return $this->hasMany(Catalog::class, ['id' => 'catalog_related_id'])->
                        viaTable('{{%catalog_related}}', ['catalog_id' => 'id']);
    }

    public function getRelatingGoods()
    {
        return $this->hasMany(Catalog::class, ['id' => 'catalog_id'])->
                        viaTable('{{%catalog_related}}', ['catalog_related_id' => 'id']);
    }

    public static function tableName()
    {
        return '{{%catalog}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * Получает свойства товара и его <b>код</b> значения
     * @param boolean $refresh - Обновить ли из базы свойства
     * @return \app\components\properties\Props - модель alias->code
     */
    public function getPropsCode($refresh = false)
    {
        if (!empty($this->_propsCode) && !$refresh) {
            return $this->_propsCode;
        }

        $props_cat = $this->getProperties();

        $props = new \app\components\properties\Props();
        if (count($props_cat) > 0) {
            foreach ($props_cat as $value) {
                $result = null;
                $result_props = CatalogProp::find()->andWhere(['catalog_id' => $this->id, 'props_id' => $value->id])->all();
                if (!is_null($result_props)) {
                    if (($value->prop_type_list_id == 2) && ($value->prop_type_id == 4)) {
                        $result = ArrayHelper::map($result_props, 'id', 'value');
                    } else {
                        $result = ArrayHelper::map($result_props, 'id', 'value');
                        $result = array_shift($result);
                    }
                }
                $props->defineAttribute($value->alias, $result);
                $group = $value->group;
                if (!is_null($group)) {
                    $props->groups->addToGroup($group->alias, $value->alias, $group->name);
                }
                if ($value->is_most == 1) {
                    $props->addRule($value->alias, 'required');
                }
                if ($value->prop_type_id == 2) {
                    $props->addRule($value->alias, 'number', ['integerOnly' => true]);
                }
                if ($value->prop_type_id == 3) {
                    $props->addRule($value->alias, 'number');
                }
                $props->addRule($value->alias, 'safe');
            }
        }
        $this->_propsCode = $props;
        return $props;
    }

    /**
     * Получает свойства товара и его <b>значение</b>
     * @param boolean $refresh - Обновить ли из базы свойства
     * @return \app\components\properties\Props - модель alias->code
     */
    public function getProps($refresh = false)
    {
        if (!empty($this->_props) && !$refresh) {
            return $this->_props;
        }

        $props_cat = $this->getProperties();

        $props = new \app\components\properties\Props();
        if (count($props_cat) > 0) {
            foreach ($props_cat as $value) {
                $result = null;
                $result_props = CatalogProp::find()->andWhere(['catalog_id' => $this->id, 'props_id' => $value->id])->all();
                if (!is_null($result_props)) {
                    switch ($value->prop_type_id) {
                        case 1:
                        case 2:
                        case 3:
                            if (isset($result_props[0])) {
                                $result = $result_props[0]->value;
                            } else {
                                $result = null;
                            }
                            break;
                        case 4:
                            $valuesCodes = ArrayHelper::map($result_props, 'id', 'value');
                            $valuesQuery = PropsValue::find()->andWhere(['in', 'id', $valuesCodes])->orderBy('sort');
                            if ($value->prop_type_list_id == 2) {
                                $values = $valuesQuery->all();
                                $result = ArrayHelper::map($values, 'id', 'name');
                            } else {
                                $values = $valuesQuery->one();
                                if (!is_null($values)) {
                                    $result = $values->name;
                                }
                            }
                            break;
                        case 7:
                            if (isset($result_props[0])) {
                                $result = $result_props[0]->value == 1;
                            } else {
                                $result = null;
                            }
                            break;
                    }
                }
                if (empty($result)) {
                    continue;
                }
                $props->defineAttribute($value->alias, $result);
                $group = $value->group;
                if (!is_null($group)) {
                    $props->groups->addToGroup($group->alias, $value->alias, $group->name);
                }
            }
        }
        $this->_props = $props;
        return $props;
    }

    public function getProperties($get_sku = false)
    {
        $all_parent = \app\components\IcmsHelper::getAllParents(CatalogCategorie::findOne($this->catalog_categorie_id), 'pid');
        $props_codes = \yii\helpers\ArrayHelper::map(CatalogCategorieProp::find()->andWhere(['in', 'catalog_categorie_id', array_keys($all_parent)])->all(), 'props_id', 'props_id');
        return \app\models\Prop::find()->andWhere(['in', 'id', array_keys($props_codes)])->andWhere(['is_sku' => $get_sku])->all();
    }

    public function getSkuList($skuId = null)
    {
        $props_cat = $this->getProperties(true);
        $props = new \app\components\properties\Sku();
        if (count($props_cat) > 0) {
            foreach ($props_cat as $value) {
                $result = null;
                if (!is_null($skuId)) {
                    $result_props = CatalogSkuPropsValue::find()->andWhere(['catalog_sku_id' => $skuId, 'props_id' => $value->id])->all();
                    if (!is_null($result_props)) {
                        if (($value->prop_type_list_id == 2) && ($value->prop_type_id == 4)) {
                            $result = \yii\helpers\ArrayHelper::map($result_props, 'id', 'value');
                        } else {
                            $result = \yii\helpers\ArrayHelper::map($result_props, 'id', 'value');
                            $result = array_shift($result);
                        }
                    }
                }

                $props->defineAttribute($value->alias, $result);
                $group = $value->group;
                if (!is_null($group)) {
                    $props->groups->addToGroup($group->alias, $value->alias, $group->name);
                }
                if ($value->is_most == 1) {
                    $props->addRule($value->alias, 'required');
                }
                if ($value->prop_type_id == 2) {
                    $props->addRule($value->alias, 'number', ['integerOnly' => true]);
                }
                if ($value->prop_type_id == 3) {
                    $props->addRule($value->alias, 'number');
                }
                $props->addRule($value->alias, 'safe');
            }
        }
        return $props;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert && array_key_exists('catalog_categorie_id', $changedAttributes) && $changedAttributes['catalog_categorie_id'] != $this->catalog_categorie_id) {
            $newProps = CatalogProp::getAllCategoriePropsId($this->catalog_categorie_id);
            foreach ($this->propsValues as $props) {
                if (!in_array($props->props_id, $newProps)) {
                    $props->delete();
                }
            }
        }
    }

    public function beforeDelete()
    {
        foreach ($this->skus as $sku) {
            $sku->delete();
        }
        $this->unlinkAll('images', true);
        $this->unlinkAll('propsValues', true);
        return parent::beforeDelete();
    }

    static function siteMap()
    {
        return [
            'title' => 'Каталог',
            'route' => 'site/catalog_element',
            'url' => [
                'catalog_id' => 'id',
                'catalog_alias' => 'alias',
                'catalog_categorie_alias' => ['categorie', 'alias']
            ],
            'condition' => ['status' => self::STATUS_ACTIVE],
            'priority' => 0.8
        ];
    }

    public function getAllMyProps()
    {
        $all_props = [];
        if (count($this->propsValues) > 0) {
            foreach ($this->propsValues as $elem) {
                $all_props[$elem->props_id][] = $elem->value;
            }
        }
        return $all_props;
    }

    public function renderItem()
    {
        if ($this->image != "") {
            $img = "/upload/icms/images/catalog/" . $this->image;
        } else {
            $img = "https://upload.wikimedia.org/wikipedia/commons/thumb/b/b3/No_free_image_man_%28en%29.svg/220px-No_free_image_man_%28en%29.svg.png";
        }

        $cat = $this->categorie;

        $link = \yii\helpers\Url::to([
                    'site/catalog',
                    'catalog_categorie_alias' => $cat->alias,
                    'catalog_id' => $this->id,
                    'catalog_alias' => $this->alias,
        ]);
        echo "<div class=\"col-md-4\">
                            <div class=\"product-img product-img-brd\">
                                <a href=\"{$link}\"><img class=\"full-width img-responsive\" src=\"{$img}\" alt=\"{$this->name}\"></a>
                                ";
        if ($this->is_popular == 1) {
            echo "<div class=\"shop-rgba-dark-green rgba-banner\">Хит!</div>";
        }
        echo "</div>
                            <div class=\"product-description product-description-brd margin-bottom-30\">
                                <div class=\"overflow-h margin-bottom-5\">
                                    <div class=\"pull-left\">
                                        <h4 class=\"title-price\"><a href=\"{$link}\">{$this->name}</a></h4>
                                        <span class=\"gender text-uppercase\">{$cat->name}</span>
                                    </div>
                                    <div class=\"product-price\">
                                        <span class=\"title-price\">{$this->price} <i class=\"fa fa-rub\"></i></span>
                                        ";
        if (!empty($this->price_old)) {
            echo "<span class=\"title-price line-through\">{$this->price_old} <i class=\"fa fa-rub\"></i></span>";
        }
        echo "</div>";

        if (!\Yii::$app->user->isGuest) {
            $goodsId = \Yii::$app->user->identity->getWishGoodsId();
            echo "</div>
                        <ul class=\"list-inline product-ratings\">";
            if (in_array($this->id, $goodsId)) {
                echo "<li class=\"like-icon wishlist-in wishlist-add-good\" data-id=\"{$this->id}\"><a data-original-title=\"Убрать из списка желаний\" data-toggle=\"tooltip\" data-placement=\"left\" class=\"tooltips\"><i class=\"fa fa-heart\"></i></a></li>";
            } else {
                echo "<li class=\"like-icon wishlist-in wishlist-add-good\" data-id=\"{$this->id}\" ><a data-original-title=\"В список желаний\" data-toggle=\"tooltip\" data-placement=\"left\" class=\"tooltips\"><i class=\"fa fa-heart-o\"></i></a></li>";
            }
            echo "</ul></div>";
        }
        echo "</div>";
    }

    public function renderItemMini()
    {
        if ($this->image != "") {
            $img = "/upload/icms/images/catalog/" . $this->image;
            $img = IcmsHelper::getResizePath($img, 300, 250, 3);
        } else {
            $img = "https://upload.wikimedia.org/wikipedia/commons/thumb/b/b3/No_free_image_man_%28en%29.svg/220px-No_free_image_man_%28en%29.svg.png";
        }

        $cat = $this->categorie;
        $link = \yii\helpers\Url::to([
                    'site/catalog',
                    'catalog_categorie_alias' => $cat->alias,
                    'catalog_id' => $this->id,
                    'catalog_alias' => $this->alias,
        ]);


        echo "<li class=\"item\">
                <a href=\"{$link}\"><img class=\"img-responsive\" src=\"{$img}\" alt=\"{$this->name}\"></a>
                <div class=\"product-description-v2\">
                    <div class=\"margin-bottom-5\">
                        <h4 class=\"title-price\"><a href=\"{$link}\">{$this->name}</a></h4>
                        <span class=\"title-price\">{$this->price} <i class=\"fa fa-rub\"></i></span>";
        if (!empty($this->price_old)) {
            echo "<span class=\"title-price line-through\">{$this->price_old} <i class=\"fa fa-rub\"></i></span>";
        }
        if (!\Yii::$app->user->isGuest) {
            $goodsId = \Yii::$app->user->identity->getWishGoodsId();
            echo "
                        <ul class=\"list-inline product-ratings\">";
            if (in_array($this->id, $goodsId)) {
                echo "<li class=\"like-icon wishlist-in\" data-id=\"{$this->id}\"><a data-original-title=\"Убрать из списка желаний\" data-toggle=\"tooltip\" data-placement=\"left\" class=\"tooltips\"><i class=\"fa fa-heart\"></i></a></li>";
            } else {
                echo "<li class=\"like-icon wishlist-in\" data-id=\"{$this->id}\" ><a data-original-title=\"В список желаний\" data-toggle=\"tooltip\" data-placement=\"left\" class=\"tooltips\"><i class=\"fa fa-heart-o\"></i></a></li>";
            }
            echo "</ul>";
        }
        echo "</div></div></li>";
    }

}
