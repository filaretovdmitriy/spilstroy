<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Key extends ActiveRecord
{

    public $_level;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название ключа', 'on' => ['default']],
            ['name', 'unique', 'message' => 'Такой ключ уже есть в базе', 'on' => ['default']],
            [['name', 'value', 'pid'], 'safe']
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
        return '{{%keys}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getKeys()
    {
        return $this->hasMany($this->className(), ['pid' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
    }

    static function getAsArray($elements = null, &$items = [], $level = 1)
    {
        if (is_null($elements)) {
            $elements = self::find()->andWhere(['pid' => 0])->all();
        }

        foreach ($elements as $element) {
            $items[$element->id] = str_repeat('--', $level) . ' ' . $element->name;
            $childs = $element->keys;
            if (count($childs) > 0) {
                self::getAsArray($childs, $items, $level + 1);
            }
        }
        return $items;
    }

    /**
     * Получает все элементы группы по её id или названию
     * @param integer $groupIdOrName - id группы или её название
     * @param boolean $andParent - добавлять ли в массив родителя id или название которого идет первым аргументом
     * @return array массив вида ['name' => 'value']
     */
    static function getGroup($groupIdOrName, $andParent = false)
    {
        $keysQuery = self::find();
        if ($andParent) {
            $keysQuery->orWhere(['id' => $groupIdOrName]);
        }
        if (is_int($groupIdOrName)) {
            $keysQuery->orWhere(['pid' => $groupIdOrName]);
            $keys = $keysQuery->all();
        } else {
            $keysQuery->orWhere(['name' => $groupIdOrName]);
            $keys = $keysQuery->all();
        }
        return \yii\helpers\ArrayHelper::map($keys, 'name', 'value');
    }

    /**
     * Получает значение ключа по его названию
     * @param string $keyName - название ключа
     * @param string $default - занчение по умолчанию, если не найдено
     * @return string - значение ключа или <b>null</b>, если ключ не найден
     */
    static function getKeyValue($keyName, $default = null)
    {
        $key = self::find()->andWhere(['name' => $keyName])->one();
        if (!is_null($key)) {
            return $key->value;
        }

        return $default;
    }

    public function afterSave($insert, $changedAttributes)
    {

        \Yii::$app->cache->delete(\app\components\View::METRIC_CASHE);

        parent::afterSave($insert, $changedAttributes);
    }

    public function getLevel()
    {

        if (is_null($this->_level) === false) {
            return $this->_level;
        }

        $this->_level = 0;
        $parent = $this->parent;

        while (is_null($parent) === false) {
            $this->_level++;
            $parent = $parent->parent;
        }

        return $this->_level;
    }

}
