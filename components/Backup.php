<?php

namespace app\components;

use Yii;
use app\components\IcmsHelper;
use yii\helpers\FileHelper;

class Backup
{

    /**
     * Отчистка кеша
     */
    private static function _clear()
    {
        IcmsHelper::deleteCasheFolder(Yii::getAlias('@webroot/icms/assets'));
        IcmsHelper::deleteCasheFolder(Yii::getAlias('@webroot/assets'));
        IcmsHelper::deleteCasheFolder(Yii::getAlias('@runtime'));
        IcmsHelper::deleteCasheFiles(Yii::getAlias('@image_cache'));
    }

    /**
     * Дамп исходных кодов
     */
    private static function _source(&$zip)
    {
        $sourcePath = \Yii::getAlias('@app');

        $files = IcmsHelper::getDirectoryList($sourcePath);

        $webroot = Yii::getAlias('@webroot/upload');
        foreach ($files as $fileName) {
            if (strpos($fileName, $webroot) === 0) {
                continue;
            }

            $newPath = str_replace($sourcePath, '', $fileName);
            if (strpos($newPath, '/backups') === 0 || strpos($newPath, '/nbproject') === 0 || strpos($newPath, '/.git') === 0 || strpos($newPath, '.DS_Store') !== false || strpos($newPath, 'Thumbs.db') !== false || strpos($newPath, '.idea') !== false) {
                continue;
            }
            $cpName = iconv('utf-8', 'cp866', $newPath);
            $zip->addFile($fileName, $cpName);
        }

        $zip->addEmptyDir('/backups');
    }

    /**
     * Дамп папки upload
     */
    private static function _upload(&$zip, $withSource = false)
    {
        $sourcePath = \Yii::getAlias('@webroot/upload');

        $files = IcmsHelper::getDirectoryList($sourcePath);

        $uploadRootPath = str_replace(Yii::getAlias('@app'), '', $sourcePath);

        foreach ($files as $fileName) {
            $newPath = str_replace($sourcePath, $withSource ? $uploadRootPath : '/upload', $fileName);
            if (strpos($newPath, '/nbproject') === 0 || strpos($newPath, '/.git') === 0 || strpos($newPath, '.DS_Store') !== false || strpos($newPath, 'Thumbs.db') !== false || strpos($newPath, '.idea') !== false) {
                continue;
            }
            $cpName = iconv('utf-8', 'cp866', $newPath);
            $zip->addFile($fileName, $cpName);
        }
    }

    /**
     * Дамп базы
     */
    private static function _base(&$zip, $currentDate, $tmpFolder)
    {
        $dumpFolder = \Yii::getAlias('@backups/');
        $mysqlFileName = 'dump-' . $currentDate . '.sql';
        $mysqlFilePath = $dumpFolder . $tmpFolder . '/' . $mysqlFileName;
        $baseHost = IcmsHelper::getDsnAttribute('host');
        $baseName = IcmsHelper::getDsnAttribute('dbname');
        $baseUserName = \Yii::$app->db->username;
        $basePassword = \Yii::$app->db->password;
        $basePrefix = Yii::$app->db->tablePrefix;

        $tables = \Yii::$app->db->createCommand("SHOW TABLES LIKE '{$basePrefix}%'")->queryAll(\PDO::FETCH_COLUMN);
        $implodeTables = implode(' ', (array) $tables);

        $dumpCommand = "mysqldump -u{$baseUserName} --password={$basePassword} -h {$baseHost} -n {$baseName} --add-drop-table --all-tablespaces --complete-insert --add-locks --tables {$implodeTables} > {$mysqlFilePath}";

        system($dumpCommand);

        $zip->addFile($mysqlFilePath, $mysqlFileName);
    }

    /**
     * Запуск создания дампов
     */
    public static function run($source = false, $upload = false, $base = false, $clear = false)
    {
        if ($clear == true) {
            self::_clear();
        }

        if ($source === false && $upload === false && $base === false) {
            return;
        }

        $dumpFolder = \Yii::getAlias('@backups/');

        $tmpFolder = md5(time() + rand(0, 999));

        if (file_exists($dumpFolder . $tmpFolder) === true) {
            FileHelper::removeDirectory($dumpFolder . $tmpFolder);
        }
        mkdir($dumpFolder . $tmpFolder, 0777, true);

        $currentDate = date('d.m.Y-H-i-s');

        $zipName = $currentDate . '_';
        $addBot = false;
        if ($source) {
            $zipName .= 'source';
            $addBot = true;
        }
        if ($upload) {
            $zipName .= ($addBot ? '-' : '') . 'upload';
            $addBot = true;
        }
        if ($base) {
            $zipName .= ($addBot ? '-' : '') . 'base';
            $addBot = true;
        }
        $zipName .= '.zip';

        $zip = new \ZipArchive();
        $zip->open($dumpFolder . $zipName, \ZipArchive::CREATE);

        if ($source) {
            self::_source($zip);
        }

        if ($upload) {
            self::_upload($zip, $source);
        }

        if ($base) {
            self::_base($zip, $currentDate, $tmpFolder);
        }

        $zip->close();

        FileHelper::removeDirectory($dumpFolder . $tmpFolder);

        return $zipName;
    }

}
