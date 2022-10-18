<?php

namespace app\models;

use app\components\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\models\PropsGroup;

class Prop extends ActiveRecord
{

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название', 'on' => ['default']],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            ['is_sku', 'default', 'value' => 0],
            ['is_filter', 'default', 'value' => 0],
            ['is_most', 'default', 'value' => 0],
            ['alias', 'required', 'message' => 'Введите алиас свойства', 'on' => ['default']],
            ['alias', 'compare', 'compareValue' => 'groups', 'operator' => '!=', 'message' => 'Алиас не может быть groups'],
            ['alias', 'unique', 'message' => 'Свойство с таким с алиасом уже существет', 'on' => ['default']],
            ['alias', 'match', 'pattern' => '/[a-zA-Z0-9-_]+$/', 'message' => 'Только латинские буквы и цифры', 'on' => ['default']],
            ['prop_type_id', 'integer'],
            ['prop_type_list_id', 'integer'],
            ['props_groups_id', 'safe']
        ];
    }

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['name', 'props_groups_id'],
        ];
    }

    public function getType()
    {
        return $this->hasOne(PropType::class, ['id' => 'prop_type_id']);
    }

    public function getGroup()
    {
        return $this->hasOne(PropsGroup::class, ['id' => 'props_groups_id']);
    }

    public function getPropsValues()
    {
        return $this->hasMany(PropsValue::class, ['props_id' => 'id']);
    }

    public function getCatalogCategorieProps()
    {
        return $this->hasMany(CatalogCategorieProp::class, ['props_id' => 'id']);
    }

    public static function getGroupedAsArray()
    {
        $result = [];
        $props = self::find()->all();
        foreach ($props as $prop) {
            $group = $prop->group;
            if (!is_null($group)) {
                if (!array_key_exists($group->id, $result)) {
                    $result[$group->id] = [
                        'groupName' => $group->name . ' (' . $group->alias . ')',
                        'props' => [$prop]
                    ];
                } else {
                    $result[$group->id]['props'][] = $prop;
                }
            } else {
                if (!array_key_exists(0, $result)) {
                    $result[0] = [
                        'groupName' => 'Без группы',
                        'props' => [$prop]
                    ];
                } else {
                    $result[0]['props'][] = $prop;
                }
            }
        }

        return $result;
    }

    public static function getByAlias($alias)
    {
        return self::find()->andWhere(['alias' => $alias])->one();
    }

    public static function tableName()
    {
        return '{{%props}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            if ($this->prop_type_id == 4) {
                $this->prop_type_list_id = 1;
            }
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->prop_type_id == 4) {
            if ((isset($changedAttributes['prop_type_list_id'])) && ($this->prop_type_list_id != $changedAttributes['prop_type_list_id'])) {
                CatalogProp::deleteAll('props_id=:props_id', ['props_id' => $this->id]);
            }
        }
        if ($this->isAttributeChanged('is_sku') === true) {
            CatalogSkuPropsValue::deleteAll('props_id=:props_id', ['props_id' => $this->id]);
            CatalogProp::deleteAll('props_id=:props_id', ['props_id' => $this->id]);
        }
    }

    public function beforeDelete()
    {
        $this->unlinkAll('propsValues', true);
        return parent::beforeDelete();
    }

}
