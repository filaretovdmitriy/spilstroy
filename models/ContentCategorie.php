<?php

namespace app\models;

use app\components\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class ContentCategorie extends ActiveRecord
{

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите имя категории', 'on' => ['default']],
            ['name', 'unique', 'message' => 'Категория с таким с именем уже существует', 'on' => ['default']],
            [['in_preview', 'in_list', 'sort'], 'default', 'value' => 0],
            [['title_seo', 'description_seo', 'keywords_seo', 'sort', 'status', 'in_preview', 'in_list'], 'safe', 'on' => 'default'],
        ];
    }

    public function getContents()
    {
        return $this->hasMany(Content::class, ['content_categorie_id' => 'id']);
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
        return '{{%content_categorie}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeDelete()
    {
        foreach ($this->contents as $content) {
            $content->delete();
        }
        return parent::beforeDelete();
    }

}
