<?php

namespace app\models;

class CatalogPay extends \app\components\db\ActiveRecord
{

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите доставки', 'on' => ['default']],
            ['status', 'in', 'range' => [1, 2], 'message' => 'Выберите правильное значение',],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            [['name', 'content', 'is_default'], 'safe']
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
        return '{{%catalog_pay}}';
    }

    public function getImages()
    {
        return $this->hasMany(CatalogPayGallery::class, ['catalog_pay_id' => 'id']);
    }

    static function getDefaultId()
    {
        $defaultPay = self::find()->andWhere(['is_default' => 1])->one();
        return $defaultPay->id;
    }

}
