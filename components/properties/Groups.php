<?php

namespace app\components\properties;

use app\components\properties\GroupElements;

class Groups implements \Iterator
{

    private $_attributes = [];
    private $_props;

    public function rewind()
    {
        reset($this->_attributes);
    }

    public function current()
    {
        return current($this->_attributes);
    }

    public function key()
    {
        return key($this->_attributes);
    }

    public function next()
    {
        return next($this->_attributes);
    }

    public function valid()
    {
        $key = key($this->_attributes);
        $var = ($key !== null && $key !== false);
        return $var;
    }

    public function __construct(&$props)
    {
        $this->_props = $props;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        } else {
            throw new Exception('Группы ' . $name . ' не существует', 500);
        }
    }

    public function exists($key)
    {
        return array_key_exists($key, $this->_attributes);
    }

    /**
     * Массово добавляет алиасы свойств в группу
     * @param array $groups массив вида ['groupAlias1' => ['propAlias1','propAlias2','propAlias3'], 'groupAlias2' => ['propAlias4','propAlias5']]
     */
    public function addGroups(array $groups)
    {
        foreach ($groups as $groupAlias => $propAliases) {
            foreach ($propAliases as $propAlias) {
                $this->addToGroup($groupAlias, $propAlias);
            }
        }
    }

    /*
     * Добавляет алиас в группу. Алиас свойства уже должен существовать!
     * @param string $groupAlias - Алиас группы
     * @param string $propAlias - Алиас свойства
     */

    public function addToGroup($groupAlias, $propAlias, $groupName = '')
    {
        if (array_key_exists($groupAlias, $this->_attributes)) {
            $this->_attributes[$groupAlias]->offsetSet($propAlias, $this->_props->{$propAlias});
        } else {
            $this->_attributes[$groupAlias] = new GroupElements($groupAlias, $groupName, [$propAlias => $this->_props->{$propAlias}]);
        }
    }

}
