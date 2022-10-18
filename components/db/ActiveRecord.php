<?php

namespace app\components\db;

use yii\helpers\ArrayHelper;

class ActiveRecord extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 2;

    /**
     * Получает дерево модели
     * @param boolean $addRoot - Добавлять ли нулевой элемент
     * @param array $array - массив
     * @param integer $startLevel - счетчик уровня
     * @param integer $pid - с какого элемента начинать
     * @param string $fieldSort - поле по которому сортируются данные
     * @param string $fieldPid - поле для связи
     * @param string $fieldName - поле имени
     * @param string $fieldPK - поле первичного ключа
     * @return array массив вида [id => $fieldName]
     */
    static function getNamedTreeAsArray($addRoot = false, $array = [], $startLevel = 0, $pid = 0, $fieldSort = 'sort', $fieldPid = 'pid', $fieldName = 'name', $fieldPK = 'id')
    {
        if ($addRoot) {
            $array[0] = 'Корневой';
            $addRoot = false;
        }
        $models = self::find()->andWhere([$fieldPid => $pid])->orderBy($fieldSort)->all();
        if (count($models) > 0) {
            $startLevel++;
            foreach ($models as $value) {
                $array[$value->{$fieldPK}] = str_repeat('-', $startLevel) . " " . $value->{$fieldName};
                $array = self::getNamedTreeAsArray($addRoot, $array, $startLevel, $value->{$fieldPK}, $fieldSort, $fieldPid, $fieldName, $fieldPK);
            }
            return $array;
        } else {
            return $array;
        }
    }

    /**
     * Получает значения для отключения их в select
     * @param integer $current - от какого узла ветки запретить выбор
     * @param string $fieldPid - поле для связи
     * @param string $fieldSort - поле по которому сортируются данные
     * @param string $fieldPk - поле первичного ключа
     * @return array массив вида [id => $fieldName]
     */
    static function getDisabledBranch($current, $fieldPid = 'pid', $fieldSort = 'sort', $fieldPk = 'id')
    {
        $branchIds = array_keys(self::getBranch($current, [], $fieldPid, $fieldSort, $fieldPk));
        $result = [];
        foreach ($branchIds as $branchId) {
            $result[$branchId] = ['disabled' => true];
        }
        return $result + [$current => ['disabled' => true]];
    }

    /**
     * Рекурсивно получает ветку от заданного узла
     * @param integer $startPointId - от какого узла полчить ветку
     * @param array $result - массив
     * @param string $fieldPid - поле для связи
     * @param string $fieldSort - поле по которому сортируются данные
     * @param string $fieldPk - поле первичного ключа
     * @return array массив моделей из ветки
     */
    static function getBranch($startPointId, $result = [], $fieldPid = 'pid', $fieldSort = 'sort', $fieldPk = 'id')
    {
        $models = self::find()->andWhere([$fieldPid => $startPointId])->orderBy([$fieldSort => SORT_ASC])->each();

        if (!empty($models)) {
            foreach ($models as $model) {
                $result[$model->{$fieldPk}] = $model;
                $result = self::getBranch($model->{$fieldPk}, $result, $fieldPid, $fieldSort, $fieldPk);
            }
        }
        return $result;
    }

    /**
     * Получает массив названий
     * @param string $fieldName - поле в базе из которого должно браться название
     * @return array массив вида [id => $fieldName]
     */
    public static function getNamesAsArray($fieldName = 'name')
    {
        $primaryKeys = self::primaryKey();
        $primaryKey = array_shift($primaryKeys);
        return ArrayHelper::map(self::find()->all(), $primaryKey, $fieldName);
    }

    /**
     * Возвращает массив статусов
     * @return array массив статусов в виде [status => statusName]
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Опубликован',
            self::STATUS_DISABLE => 'Редактируется'
        ];
    }

    /**
     * Получает первого родителя
     * @param string $relation - название связи
     * @return \yii\db\ActiveRecord  родитель
     */
    public function getFirstParent($relation = 'parent')
    {
        $parent = $this->{$relation};
        if (is_null($parent) === true) {
            return null;
        }

        while (is_null($parent) === false) {
            $result = $parent;
            $parent = $parent->{$relation};
        }

        return $result;
    }

    /**
     * Получает первого родителя по идентификатору элемента
     * @param integer $id - идентификатор элемента
     * @param integer $stopPid - на каком родителе остановиться
     * @param string $fieldPid - поле родителя
     * @param string $relation - название связи
     * @return \yii\db\ActiveRecord  родитель
     */
    public static function getFirstParentById($id, $stopPid = 0, $fieldPid = 'pid', $relation = 'parent')
    {
        $model = self::findOne($id);
        return $model->getFirstParent($stopPid, $fieldPid, $relation);
    }
    
    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return \Yii::createObject(ActiveQuery::class, [get_called_class()]);
    }

}
