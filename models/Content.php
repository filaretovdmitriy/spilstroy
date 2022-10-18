<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use app\models\ContentCategorie;

class Content extends \app\components\db\ActiveRecordFiles
{

    public $imageFields = [
        'image' => ['content', 'content']
    ];

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название', 'on' => ['default']],
            ['g_date', 'required', 'message' => 'Введите дату', 'on' => ['default']],
            ['status', 'in', 'range' => [1, 2], 'message' => 'Выберите правильное значение',],
            ['status', 'default', 'value' => 1],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            ['alias', 'unique', 'message' => 'Категория с таким с алиасом уже существет', 'on' => ['default']],
            ['alias', 'match', 'pattern' => '/[a-zA-Z0-9_]+$/', 'message' => 'Только латинские буквы и цифры', 'on' => ['default']],
            ['content_categorie_id', 'exist', 'targetClass' => ContentCategorie::class, 'targetAttribute' => 'id'],
            [
                '!image', 'file',
                'extensions' => ['png', 'jpg', 'gif'], 'wrongExtension' => 'Доступные форматы: {extensions}',
            ],
            [['content', 'anons', 'alias', 'author', 'author_link', 'count_views', 'count_comments', 'title_seo', 'description_seo', 'keywords_seo', 'auto_alias'], 'safe', 'on' => 'default'],
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

    public function getContentCategorie()
    {
        return $this->hasOne(Contentcategorie::class, ['id' => 'content_categorie_id']);
    }

    public static function tableName()
    {
        return '{{%content}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeSave($insert)
    {
        $this->g_date = date('Y-m-d H:i:s', strtotime($this->g_date));
        return parent::beforeSave($insert);
    }

    static function siteMap()
    {
        return [
            [
                'title' => 'Новости',
                'route' => 'site/news_element',
                'url' => [
                    'alias' => 'alias'
                ],
                'condition' => ['status' => self::STATUS_ACTIVE, 'content_categorie_id' => 1],
            ],
            [
                'title' => 'Статьи',
                'route' => 'site/news_element',
                'url' => [
                    'alias' => 'alias'
                ],
                'condition' => ['status' => self::STATUS_ACTIVE, 'content_categorie_id' => 2],
            ],
        ];
    }

}
