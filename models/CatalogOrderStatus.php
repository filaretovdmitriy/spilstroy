<?php

namespace app\models;

use yii\helpers\ArrayHelper;

class CatalogOrderStatus extends \app\components\db\ActiveRecord
{

    public function rules()
    {
        return [
            [['name'], 'safe', 'on' => 'default'],
            ['can_cancel', 'default', 'value' => 0],
        ];
    }

    public static function tableName()
    {
        return '{{%catalog_order_status}}';
    }

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['name'],
        ];
    }

    static function getStatuses()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }

}
