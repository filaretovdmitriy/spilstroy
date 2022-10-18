<?php

namespace app\components\db;

class ActiveQuery extends \yii\db\ActiveQuery
{

    /**
     * Добавляет в условие выборки self::STATUS_ACTIVE
     * @return $this
     */
    public function isActive()
    {
        $modelClass = $this->modelClass;
        list(, $alias) = $this->getTableNameAndAlias();
        return $this->andWhere([$alias . '.status' => $modelClass::STATUS_ACTIVE]);
    }

    /**
     * Сортировка по полю sort
     * @param integer $order SORT_ASC или SORT_DESC
     * @return $this
     */
    public function bySort($order = SORT_ASC)
    {
        list(, $alias) = $this->getTableNameAndAlias();
        return $this->orderBy([$alias . '.sort' => $order]);
    }

    /**
     * Получить в сгруппированном виде по указанному полю
     * @param string $froupField поле для группировки
     * @param boolean $saveKeys сохранять ли ключи
     * @return array массив сгуппированных моделей
     */
    public function asGroup($froupField, $saveKeys = false)
    {
        $objects = $this->all();
        $result = [];

        foreach ($objects as $key => $object) {
            if ($saveKeys === true) {
                $result[$object->{$froupField}][$key] = $object;
            } else {
                $result[$object->{$froupField}][] = $object;
            }
        }

        return $result;
    }

}
