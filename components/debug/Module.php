<?php

namespace app\components\debug;

use Yii;
use yii\debug\Module as DebugModule;

class Module extends DebugModule
{

    private $_basePath;

    protected function checkAccess($action = null)
    {
        $user = Yii::$app->getUser();
        if ($user->isGuest === false && $user->can('developer') === true) {
            return true;
        }
        $this->logTarget->enabled = false;
        return false;
    }

    /**
     * Returns the root directory of the module.
     * It defaults to the directory containing the module class file.
     * @return string the root directory of the module.
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            $class = new \ReflectionClass(new yii\debug\Module('debug'));
            $this->_basePath = dirname($class->getFileName());
        }

        return $this->_basePath;
    }

}
