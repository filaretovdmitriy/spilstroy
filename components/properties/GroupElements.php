<?php

namespace app\components\properties;

use app\models\PropsGroup;

class GroupElements extends \ArrayObject
{

    private $_groupName;
    private $_groupAlias;

    function __construct($alias, $name = '', $input = [], $flags = self::ARRAY_AS_PROPS, $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);

        $this->_groupName = $name;
        $this->_groupAlias = $alias;
    }

    function getName()
    {
        if (empty($this->_groupName)) {
            $this->_groupName = PropsGroup::getByAlias($this->_groupAlias);
        }
        return $this->_groupName;
    }

}
