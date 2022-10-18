<?php

namespace app\models;

use app\components\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class BannerGroup extends ActiveRecord
{

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите имя слайдера', 'on' => ['default']]
        ];
    }

    public function getBanners()
    {
        return $this->hasMany(Banner::class, ['banner_group_id' => 'id']);
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
        return '{{%banner_group}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeDelete()
    {
        foreach ($this->banners as $banner) {
            $banner->delete();
        }
        return parent::beforeDelete();
    }

}
