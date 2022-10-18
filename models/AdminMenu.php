<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class AdminMenu extends \app\components\db\ActiveRecord
{

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['title']
        ];
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
    }

    public function getChildrens()
    {
        return $this->hasMany(self::class, ['pid' => 'id']);
    }

    public function getLevel()
    {
        $level = 1;

        $parent = $this->parent;
        while (!is_null($parent)) {
            $level++;
            $parent = $parent->parent;
        }

        return $level;
    }

    public function rules()
    {
        return [
            ['title', 'required', 'message' => 'Введите название', 'on' => self::SCENARIO_DEFAULT],
            ['route', 'required', 'message' => 'Введите роут'],
            ['sort', 'default', 'value' => 0],
            ['sort', 'integer', 'message' => 'Введите целое число'],
            ['controller', 'required', 'message' => 'Введите название контроллера', 'on' => self::SCENARIO_DEFAULT],
            ['pid', 'integer', 'on' => self::SCENARIO_DEFAULT],
            [['parentName', 'sort', 'isActive', 'in_button', 'icon_class', 'role'], 'safe', 'on' => 'default']
        ];
    }

    public static function tableName()
    {
        return '{{%admin_menu}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function getTreeArray($array = [], $startLevel = 0, $pid = 0)
    {

        $elems = self::find()->andWhere(['pid' => $pid])->orderBy('sort')->all();

        if (count($elems) >= 1) {
            $startLevel++;
            foreach ($elems as $value) {
                $array[$value->id] = str_repeat('-', $startLevel) . " " . $value->title . " (" . $value->route . ")";
                $array = self::getTreeArray($array, $startLevel, $value->id);
            }
            return $array;
        } else {
            return $array;
        }
    }

    public static function getIconClasses()
    {
        return [
            'icon_nav_structure' => 'icon_nav_structure',
            'icon_nav_news' => 'icon_nav_news',
            'icon_nav_gallery' => 'icon_nav_gallery',
            'icon_nav_catalog' => 'icon_nav_catalog',
            'icon_nav_users' => 'icon_nav_users'
        ];
    }

}
