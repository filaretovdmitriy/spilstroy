<?php

namespace app\components\properties;

use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use app\models\CatalogProp;
use app\components\properties\Groups;

class Props extends DynamicModel
{

    public $groups;

    public function attributeLabels()
    {
        return ArrayHelper::map(\app\models\Prop::find()->andWhere(['is_sku' => 0])->all(), 'alias', 'name');
    }

    public function __construct(array $attributes = [], $config = [])
    {
        parent::__construct($attributes, $config);

        $this->groups = new Groups($this);
    }

    public function save($catalog_id)
    {
        CatalogProp::deleteAll(['catalog_id' => $catalog_id]);
        $db_prop = \app\components\IcmsHelper::map(\app\models\Prop::find()->all(), 'alias', ['id', 'is_most', 'prop_type_id']);
        foreach ($this->attributes as $key => $prop) {
            if (empty($prop)) {
                continue;
            }
            if (!is_array($prop)) {
                $prop = (array) $prop;
            }
            foreach ($prop as $value) {
                $catalog_props = new CatalogProp();
                $catalog_props->catalog_id = $catalog_id;
                $catalog_props->props_id = $db_prop[$key]['id'];
                $catalog_props->value = $value;
                $catalog_props->save();
            }
        }
    }

}
