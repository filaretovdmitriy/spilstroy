<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Feedback extends ActiveRecord
{

    const STATUS_HIDDEN = 2;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return '{{%feedbacks}}';
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_HIDDEN => 'Не проверен (скрыт)',
            self::STATUS_ACTIVE => 'Проверен (отображается)'
        ];
    }

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Заполните поле ФИО'],
            ['content', 'required', 'message' => 'Заполните поле текст', 'except' => 'search'],
            ['created_date', 'required', 'message' => 'Выберите дату', 'except' => 'search'],
            [['name', 'content', 'status', 'created_date'], 'safe'],
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

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeSave($insert)
    {
        $this->created_date = date('Y-m-d H:i:s', strtotime($this->created_date));
        return parent::beforeSave($insert);
    }

}
