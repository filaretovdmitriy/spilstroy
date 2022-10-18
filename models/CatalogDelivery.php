<?php

namespace app\models;

class CatalogDelivery extends \app\components\db\ActiveRecord
{

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите доставки', 'on' => ['default']],
            ['status', 'in', 'range' => [1, 2], 'message' => 'Выберите правильное значение',],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['price', 'integer', 'message' => 'Введите целое число'],
            ['price', 'default', 'value' => 0],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            [['name', 'content', 'have_address', 'is_default'], 'safe']
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

    public static function tableName()
    {
        return '{{%catalog_delivery}}';
    }

    public function getImages()
    {
        return $this->hasMany(CatalogDeliveryGallery::class, ['catalog_delivery_id' => 'id']);
    }

    static function getDefaultId()
    {
        $defaultDelivery = self::find()->andWhere(['is_default' => 1])->one();
        return $defaultDelivery->id;
    }

}
