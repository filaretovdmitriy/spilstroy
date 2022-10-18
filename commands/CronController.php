<?php

namespace app\commands;

class CronController extends \app\components\controller\Console
{

    public function actionBackup()
    {

        \app\components\Backup::run(true, true, true, true);

        return self::EXIT_CODE_NORMAL;
    }

}
