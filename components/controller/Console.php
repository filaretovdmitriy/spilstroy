<?php

namespace app\components\controller;

class Console extends \yii\console\Controller
{

    /**
     * Вывод лога в консоль
     * @var boolean
     */
    public $log = false;
    /**
     * Запись о запусках в базу
     * @var boolean
     */
    public $baseLog = true;
    /**
     * @var \app\models\log\Console
     */
    private $_baseLog = null;
    /**
     * Вывод лога в файл
     * @var boolean
     */
    public $logToFile = false;
    /**
     * Очистка файла .lock, если по каким-то причинам старый файл не удалился
     * @var boolean
     */
    public $clearLock = false;
    public $defaultAction = false;

    private $lockFile = '';

    public function options($actionID)
    {
        return ['log', 'logToFile', 'clearLock', 'profiling', 'baseLog'];
    }

    public function optionAliases()
    {
        return ['l' => 'log', 'lf' => 'logToFile', 'cl' => 'clearLock', 'pf' => 'profiling', 'bl' => 'baseLog'];
    }

    public function log($message, $addNR = true)
    {
        if ($this->log === true || $this->logToFile === true) {
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $date = new \DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $string = $date->format('d.m.Y H:i:s:u') . ' - ' . $message . ($addNR === true ? PHP_EOL : '');
        }

        if ($this->log === true) {
            echo $string;
        }

        if ($this->logToFile === true) {
            $logFolder = \Yii::getAlias('@runtime/console/log/' . $this->id . '/');
            if (file_exists($logFolder) === false) {
                mkdir($logFolder, 0777, true);
            }
            file_put_contents($logFolder . '/' . $this->action->id . '.log', $string, FILE_APPEND);
        }
    }

    private function _openBaseLog()
    {
        $this->_baseLog = new \app\models\log\Console();
        $processUser = posix_getpwuid(posix_geteuid());
        $this->_baseLog->user = $processUser['name'];
        $this->_baseLog->controller = $this->id;
        $this->_baseLog->action = $this->action->id;
        $this->_baseLog->command = $_SERVER['SCRIPT_NAME'] . ' ' . implode(' ', \Yii::$app->getRequest()->getParams());
        $this->_baseLog->start = date('Y-m-d H:i:s');
        return $this->_baseLog->save();
    }

    private function _closeBaseLog($result)
    {
        $this->_baseLog->end = date('Y-m-d H:i:s');
        $this->_baseLog->exit_code = $result;
        return $this->_baseLog->save();
    }

    public function beforeAction($action)
    {
        $route = $this->getRoute();

        $pidFolder =  \Yii::getAlias('@runtime/console_pids');
        if (file_exists($pidFolder) === false) {
            mkdir($pidFolder, 0777, true);
        }
        $this->lockFile = $pidFolder . '/' . str_replace('/', '-', $route) . '.lock';

        if ($this->clearLock === true && $this->_isLock() === true) {
            unlink($this->lockFile);
        }

        if ($this->_isLock() === true) {
            if ($this->log === true) {
                $pid = trim(file_get_contents($this->lockFile));
                $this->log('Процесс уже запущен PID - ' . $pid);
            }
            return false;
        }

        if ($this->baseLog === true) {
            $this->_openBaseLog();
        }

        $this->_setLock();

        return parent::beforeAction($action);
    }

    private function _isLock()
    {
        if (file_exists($this->lockFile) === false) {
            return false;
        }

        $pid = trim(file_get_contents($this->lockFile));

        return posix_getpgid($pid) !== false;
    }

    public function shutdown()
    {
        if (file_exists($this->lockFile) === true) {
            unlink($this->lockFile);
        }
        exit(self::EXIT_CODE_NORMAL);
    }

    private function _getCurrentPGID()
    {
        $pid = posix_getppid();
        return posix_getpgid($pid);
    }

    private function _setLock()
    {
        $currentPid = $this->_getCurrentPGID();

        file_put_contents($this->lockFile, $currentPid);

        register_shutdown_function([$this, 'shutdown']);
    }

    public function afterAction($action, $result)
    {
        $logFolder = \Yii::getAlias('@runtime/console/log/' . $this->id . '/');
        $logFile = $logFolder . '/' . $this->action->id . '.log';
        if (file_exists($logFile) === true) {
            $zipName = $logFolder . $this->action->id . '.zip';
            if (file_exists($zipName) === true) {
                unlink($zipName);
            }
            $zip = new \ZipArchive();
            $zip->open($zipName, \ZipArchive::CREATE);
            $zip->addFile($logFile, './' . $this->action->id . '.log');
            $zip->close();

            unlink($logFile);
        }

        if ($this->baseLog === true) {
            $this->_closeBaseLog($result);
        }

        return parent::afterAction($action, $result);
    }
}
