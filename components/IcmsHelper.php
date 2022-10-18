<?php

namespace app\components;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\CheckBoxSlide;
use app\modules\icms\widgets\CheckBoxList;
use app\modules\icms\widgets\RadioList;
use app\models\Tree;
use yii\helpers\FileHelper;
use yii\helpers\Html;

class IcmsHelper
{

    /**
     * Строит дерево страниц
     * @param array $pages - массив страниц
     * @param array $ulOptions - массив html опций тега ul
     * @param array $liOptions - массив html опций тега li
     * @return string html дерева структуры
     */
    public static function renderSiteMap($pages = null, $ulOptions = [], $liOptions = [])
    {
        if (is_null($pages)) {
            $pages = Tree::find()->andWhere(['status' => Tree::STATUS_ACTIVE, 'pid' => 1, 'in_map' => 1])
                ->orderBy(['sort' => SORT_ASC, 'name_menu' => SORT_ASC])
                ->all();
        }
        $html = Html::beginTag('ul', $ulOptions);

        foreach ($pages as $page) {
            $html .= Html::beginTag('li', $liOptions);
            $html .= Html::a($page->name_menu, str_replace('/root', '/', $page->url));
            $childrens = $page->getPages()
                ->andWhere(['status' => Tree::STATUS_ACTIVE, 'in_map' => 1])
                ->orderBy(['sort' => SORT_ASC, 'name_menu' => SORT_ASC])
                ->all();
            if (!empty($childrens)) {
                $html .= self::renderSiteMap($childrens);
            }
            $html .= Html::endTag('li');
        }

        return $html . Html::endTag('ul');
    }

    /**
     * Получает массив с выбранными полями по установленному индексному полю
     * @param array $length - длина пароля
     * @return string новый пароль
     */
    public static function generatePassword($length = 10)
    {
        $chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        shuffle($chars);
        $password = implode(array_slice($chars, 0, $length));
        return $password;
    }

    /**
     * Получает массив с выбранными полями по установленному индексному полю.<br>
     * Если поля не указаны, то вернет массив индексов
     * @param array $models - массив моделей
     * @param string $indexField - поле взятия значений для индекса массива
     * @param mixed $fields - массив названий полей. Если указана строка, то вложенный массив не создаётся
     * @return array массив с идексами из $indexField и элементам $fields
     */

    /**
     * Получает массив с выбранными полями по установленному индексному полю.<br>
     * Если поля не указаны, то вернет массив индексов
     * @param array $models - массив моделей
     * @param string|null $indexField - поле взятия значений для индекса массива.
     * Если равен null, будут браться индексы массива $models.
     * Должен быть обязательно заполнен, если не используется $fields
     * @param mixed $fields - массив названий полей. Если указана строка, то вложенный массив не создаётся
     * @return array массив с идексами из $indexField и элементам $fields
     */
    static function map($models, $indexField, $fields = [])
    {
        $array = [];

        if (!empty($fields)) {
            $fields = !is_array($fields) ? [0 => $fields] : $fields;
            foreach ($models as $key => $model) {
                foreach ($fields as $field) {
                    $fieldName = $fieldNameBase = $field;
                    if (is_array($field)) {
                        $fieldNameBase = key($field);
                        $fieldName = $field[$fieldNameBase];
                    }
                    if (is_null($indexField)) {
                        if (count($fields) === 1) {
                            $array[$key] = $model->{$fieldNameBase};
                        } else {
                            $array[$key][$fieldName] = $model->{$fieldNameBase};
                        }
                    } else {
                        if (count($fields) === 1) {
                            $array[$model->{$indexField}] = $model->{$fieldNameBase};
                        } else {
                            $array[$model->{$indexField}][$fieldName] = $model->{$fieldNameBase};
                        }
                    }
                }
            }
        } else {
            foreach ($models as $model) {
                $array[] = $model->{$indexField};
            }
        }

        return $array;
    }

    /**
     * Получает массив с выбранными полями по установленному индексному полю
     * @param array $models - массив моделей
     * @param string $indexField - поле взятия значений для индекса массива
     * @param array $fields - массив названий полей с именами для дата ['dataName' => 'name']
     * @return array массив с идексами из $indexField и элементам $fields
     */
    static function modelAttributesToData($models, $indexField, array $fields = [])
    {
        $array = self::map($models, $indexField, $fields);
        $dataArray = [];
        $flipFields = array_flip($fields);
        foreach ($array as $index => $elementArrtibutes) {
            $elementArrtibutes = count($fields) === 1 ? [$fields[0] => $elementArrtibutes] : $elementArrtibutes;
            foreach ($elementArrtibutes as $attrName => $attrValue) {
                if (!is_int($flipFields[$attrName])) {
                    $dataAttrName = 'data-' . $flipFields[$attrName];
                } else {
                    $dataAttrName = 'data-' . $attrName;
                }
                $dataArray[$index][$dataAttrName] = $attrValue;
            }
        }

        return $dataArray;
    }

    /**
     * Группирует модели из массива по значению поля groupField
     * @param array $models - массив поделей для группировки
     * @param string $groupField - название поля для группировки
     * @return array массив моделей сгруппированые по groupField
     */
    static function group($models, $groupField)
    {
        $array = [];
        foreach ($models as $model) {
            $array[$model->{$groupField}][] = $models;
        }
        return $array;
    }

    /**
     * Возвращает необходимую форму слова из массива $forms в зависимости от $count
     * @param integer $count - количество
     * @param array $forms - массив форм вида ['товар', 'товара', 'товаров']
     * @return string - форма слова из $forms
     */
    static function pluralForm($count, $forms)
    {
        return $count % 10 == 1 && $count % 100 != 11 ? $forms[0] : ($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20) ? $forms[1] : $forms[2]);
    }

    /**
     * Получает хлебные крошки для модели
     * @param string $url начальный путь для хлебных крошек
     * @param object $model модель от которой будет строится путь
     * @param $parentRelationField string поле связи по которому можно получить родителя элемента
     * @param $parentField string поле в базе по которой строится связь
     * @param $nameField string поле в базе для вывода заголовка
     * @param $firstName string название для первого элемента хлебных крошек
     * @param $addUrlField string название поля модели, которое будте добавляться к url
     * @param $stopIdentificator integer на каком идентификаторе закончить строить путь
     * @return array массив вида [['url', 'title'], ['url', 'title']]
     */
    static function getBreadCrumbs($url, $model, $parentRelationField = 'parent', $parentField = 'pid', $nameField = 'name', $firstName = '', $addUrlField = '', $stopIdentificator = 0)
    {
        $url = (array) $url;
        if (is_null($model) === true) {
            return [['url' => $url, 'title' => $firstName]];
        }
        $crumbs = [
            ['url' => $url + [$addUrlField => $model->id], 'title' => $model->{$nameField}]
        ];
        $parent = $model->{$parentRelationField};
        if (!is_null($parent)) {
            $resultUrl = empty($addUrlField) ? $url : $url + [$addUrlField => $parent->{$addUrlField}];
            $crumbs[] = ['url' => $resultUrl, 'title' => $parent->{$nameField}];
            while ($parent->{$parentField} != $stopIdentificator) {
                $parent = $parent->{$parentRelationField};
                $resultUrl = empty($addUrlField) ? $url : $url + [$addUrlField => $parent->{$addUrlField}];
                $crumbs[] = ['url' => $resultUrl, 'title' => $parent->{$nameField}];
            }
        }
        if (empty($firstName) === false) {
            $crumbs[] = ['url' => $url, 'title' => $firstName];
        }
        return array_reverse($crumbs);
    }

    /**
     * Получает хлебные крошки для модели
     * @param string $url начальный путь для хлебных крошек
     * @param object $model модель от которой будет строится путь
     * @param $parentRelationField string поле связи по которому можно получить родителя элемента
     * @param $parentField string поле в базе по которой строится связь
     * @param $nameField string поле в базе для вывода заголовка
     * @param $firstName string название для первого элемента хлебных крошек
     * @param $addUrlField string название поля модели, которое будте добавляться к url
     * @param $stopIdentificator integer на каком идентификаторе закончить строить путь
     * @param $addAllLinks boolean выводить ли все url'ы или не выводить последний
     * @return array массив вида [['url', 'title'], ['url', 'title']]
     */
    static function getBreadCrumbsLabel($url, $model, $parentRelationField = 'parent', $parentField = 'pid', $nameField = 'name', $firstName = '', $addUrlField = '', $stopIdentificator = 0, $addAllLinks = false)
    {
        if (is_null($model)) {
            return [['url' => $url, 'label' => $firstName]];
        }
        if ($addAllLinks) {
            $resultUrl = empty($addUrlField) ? $url : $url . '/' . $model->{$addUrlField};
            $crumbs = [['label' => $model->{$nameField}, 'url' => $resultUrl]];
        } else {
            $crumbs = [['label' => $model->{$nameField}]];
        }
        $parent = $model->{$parentRelationField};
        if (!is_null($parent)) {
            $resultUrl = empty($addUrlField) ? $url : $url . '/' . $parent->{$addUrlField};
            $crumbs[] = ['url' => $resultUrl, 'label' => $parent->{$nameField}];
            while ($parent->{$parentField} != $stopIdentificator) {
                $parent = $parent->{$parentRelationField};
                $resultUrl = empty($addUrlField) ? $url : $url . '/' . $parent->{$addUrlField};
                $crumbs[] = ['url' => $resultUrl, 'label' => $parent->{$nameField}];
            }
        }
        if (!empty($firstName)) {
            $crumbs[] = ['url' => $url, 'label' => $firstName];
        }
        return array_reverse($crumbs);
    }

    /**
     * Получает хлебные крошки для модели структуры
     * @param string $url начальный путь для хлебных крошек
     * @param boolean $lastActive активность последнего пункта хлебных крошек
     * @return array массив вида [['url', 'title'], ['url', 'title']]
     */
    static function getBreadCrumbsTree($model, $lastActive = false)
    {
        if ($lastActive) {
            $crumbs = [
                ['url' => $model->url, 'label' => $model->name_menu]
            ];
        } else {
            $crumbs = [
                ['label' => $model->name_menu]
            ];
        }
        $parent = $model->parent;
        if (!is_null($parent)) {
            if ($parent->pid != 0) {
                $crumbs[] = ['url' => $parent->url, 'label' => $parent->name_menu];
                while ($parent->pid != 0) {
                    $parent = $parent->parent;
                    if ($parent->pid != 0) {
                        $crumbs[] = ['url' => $parent->url, 'label' => $parent->name_menu];
                    }
                }
            }
        }
        return array_reverse($crumbs);
    }

    /**
     * Получает все родителей элемента модели
     * @param object $model модель от которой будет строится путь
     * @param $parentRelationField string поле связи по которому можно получить родителя элемента
     * @param $parentField string поле в базе по которой строится связь
     * @param $nameField string поле в базе для вывода заголовка
     * @param $stopIdentificator integer на каком идентификаторе закончить строить путь
     * @return array массив
     */
    static function getAllParents($model, $parentField = 'pid', $stopIdentificator = 0, $parentRelationField = 'parent', $nameField = 'name')
    {
        $crumbs = [];
        $crumbs[$model->id] = $model->{$nameField};
        $parent = $model->{$parentRelationField};
        if (!is_null($parent)) {
            $crumbs[$parent->id] = $parent->{$nameField};
            while ($parent->{$parentField} != $stopIdentificator) {
                $parent = $parent->{$parentRelationField};
                $crumbs[$parent->id] = $parent->{$nameField};
            }
        }
        return $crumbs;
    }

    /**
     * Проверят используется ли протокол https
     * @return boolean true в случае, если https используется
     */
    static function isHttps()
    {
        return \Yii::$app->request->port === 443;
    }

    /**
     * Проверят доступен ли файл из веба<br>
     * При использовании через встроенный в php web-сервер<br>
     * allow_url_fopen в php.ini поставить Off, иначе будет рекурсия<br>
     * <b>при запуске на localhost проверятеся через file_exists!!!</b>
     * @param string $url пусть до файла
     * @return boolean Доступность файла
     */
    static function hasFile($url)
    {
        if (\Yii::$app->request->serverName === 'localhost') {
            $filePath = str_replace(Url::home(true), '', $url);
            $path = str_replace('/icms', '', \Yii::getAlias('@webroot'));
            return file_exists($path . '/' . $filePath);
        }
        if (strpos($url, '/icms') !== false) {
            $url = str_replace('/icms', '', $url);
        }
        $Headers = @get_headers($url);
        return preg_match("|200|", $Headers[0]) !== 0;
    }

    /**
     * Ищет текст в файле
     * @param string $filePath - путь до файла
     * @param string $needle - подстрока для поиска
     * @return boolean Наличие подстроки в файле
     */
    static function fileHasString($filePath, $needle)
    {
        if (self::hasFile($filePath)) {
            $fileContent = file_get_contents($filePath);
            return strpos($fileContent, $needle) !== false;
        }

        return false;
    }

    /**
     * Экранирует строку JS<br>
     * После обработки строка может быть использована в двойных кавычках JS скрипта
     * @param string $js строка для экранирования
     * @param boolean $forUrl используется ли строка в URL
     * @return string Экранированная строка
     */
    static function JS_quote($js, $forUrl = false)
    {
        if ($forUrl) {
            return strtr($js, array('%' => '%25', "\t" => '\t', "\n" => '\n', "\r" => '\r', '"' => '\"', '\'' => '\\\'', '\\' => '\\\\', '</' => '<\/'));
        } else {
            return strtr($js, array("\t" => '\t', "\n" => '\n', "\r" => '\r', '"' => '\"', '\'' => '\\\'', '\\' => '\\\\', '</' => '<\/'));
        }
    }

    /**
     * Получает информацию о таблицах текущей базы
     * @return array массив информации о таблицах
     */
    static function getBaseInfo()
    {
        $baseName = self::getDsnAttribute('dbname');
        $baseHost = self::getDsnAttribute('host');
        $connection = new \yii\db\Connection();
        $connection->username = \Yii::$app->db->username;
        $connection->password = \Yii::$app->db->password;
        $connection->dsn = "mysql:host={$baseHost};dbname=information_schema";
        $query = new \yii\db\Query();
        return $query->from('TABLES')->andWhere(['TABLE_SCHEMA' => $baseName])->andFilterWhere(['LIKE', 'TABLE_NAME', \Yii::$app->db->tablePrefix])->all($connection);
    }

    /**
     * Получает значение атирибута из строки dsn настроек соединения с базой
     * @param string $name имя атрибута
     * @return string атрибут dsn или null, если атрибут не найден
     */
    static function getDsnAttribute($name)
    {
        if (preg_match('/' . $name . '=([^;]*)/', \Yii::$app->db->dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

    /**
     * Переводит байты в подходящие единицы и добавляет соответствующую приставку
     * @param integer $bytes размер
     * @return string размер с приставкой
     */
    static function getSymbolByQuantity($bytes)
    {
        $symbols = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        $exp = floor(log($bytes) / log(1024));
        if (is_infinite($exp) === true) {
            return '0&nbsp;' . $symbols[0];
        }
        if (isset($symbols[$exp]) === false || $exp < 0) {
            return '-';
        }
        return sprintf('%.2f&nbsp;' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));
    }

    static function getFileUploadMaxSize()
    {
        static $maxSize = -1;

        if ($maxSize < 0) {
            // Start with post_max_size.
            $postMaxSize = self::parseSize(ini_get('post_max_size'));
            if ($postMaxSize > 0) {
                $maxSize = $postMaxSize;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $uploadMax = self::parseSize(ini_get('upload_max_filesize'));
            if ($uploadMax > 0 && $uploadMax < $maxSize) {
                $maxSize = $uploadMax;
            }
        }
        return $maxSize;
    }

    /**
     * @param string $size размер файла (1000, 2B, 2K, 2M, 2G, 2T, 2P, 2E, 2Z, 2Y)
     * @return integer размер в байтах
     */
    static function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

    /**
     * Удаляет все папки в папке
     * @param string $folderPath путь до директории без слеша в конце
     */
    static function deleteCasheFolder($folderPath)
    {
        foreach (glob($folderPath . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $folder) {
            FileHelper::removeDirectory($folder);
        }
    }

    /**
     * Удаляет все файлы в папке, не трогая начинающиеся с точки
     * @param string $folderPath путь до директории без слеша в конце
     */
    static function deleteCasheFiles($folderPath)
    {
        foreach (glob($folderPath . '/*', GLOB_NOSORT) as $filePath) {
            $fileName = preg_replace('/(^.*\/)/', '', $filePath);
            if (strpos($fileName, '.') !== 0) {
                unlink($filePath);
            }
        }
    }

    /**
     * Посчитывает размер директории в байтах
     * @param string $directoryPath путь до директории без слеша в конце
     * @return integer размер
     */
    static function getDirecrotySize($directoryPath)
    {
        $size = 0;

        foreach (glob($directoryPath . '/{,.}*', GLOB_BRACE | GLOB_NOSORT) as $filename) {
            if ($filename == $directoryPath . '/.' || $filename == $directoryPath . '/..') {
                continue;
            }
            if (is_dir($filename)) {
                $size += self::getDirecrotySize($filename);
            } else {
                $size += filesize($filename);
            }
        }

        return $size;
    }

    /**
     * Рекурсивно получает все файлы внутри $directoryPath
     * @param string $directoryPath путь до директории без слеша в конце
     * @return array список файлов
     */
    static function getDirectoryList($directoryPath)
    {
        $files = [];

        foreach (glob($directoryPath . '/{,.}*', GLOB_BRACE | GLOB_NOSORT) as $filename) {
            if ($filename == $directoryPath . '/.' || $filename == $directoryPath . '/..') {
                continue;
            }
            if (is_dir($filename)) {
                $files = array_merge($files, self::getDirectoryList($filename));
            } else {
                $files[] = $filename;
            }
        }

        return $files;
    }

    /**
     * Обрезает строку по заданное количество символов не обрезая последнее слово и вставляет <b>...</b> в конец строки<br>
     * При этом отбрасываются все теги, кроме br.<br>
     * Работает с UTF-8
     * @param string $string строка для обрезки
     * @param integer $maxlen маскимальное количество символов в результирующей строке
     * @return string Обрезанная строка
     */
    static function cutString($string, $maxlen)
    {
        $string = strip_tags($string, '<br>');
        $len = (mb_strlen($string, 'utf-8') > $maxlen) ? mb_strripos(mb_substr($string, 0, $maxlen, 'utf-8'), ' ', 0, 'utf-8') : $maxlen;
        $cutStr = mb_substr($string, 0, $len ?: $maxlen, 'utf-8');
        return (mb_strlen($string, 'utf-8') > $maxlen) ? $cutStr . '...' : $cutStr;
    }

    /**
     * Отчищает строку от всего, кроме букв и чисел
     * @param string $string строка для отчистки
     * @return string Отчищенная строка
     */
    static function clearString($string)
    {
        return preg_replace('![^\w\d\s]*!', '', $string);
    }

    /**
     * Экранирует специальные символы XML
     * @param string $string строка для экранирования
     * @return string экранированная строка
     */
    static function xmlSpecialChars($string)
    {
        return str_replace(['&', '\'', '"', '>', '<'], ['&amp;', '&apos;', '&quot;', '&gt;', '&lt;'], $string);
    }

    /**
     * Преобразует экранированные XML символы в обычные
     * @param string $string строка для преобразования
     * @return string строка без экранированных xml символов
     */
    static function xmlSpecialCharsDecode($string)
    {
        return str_replace(['&amp;', '&apos;', '&quot;', '&gt;', '&lt;'], ['&', '\'', '"', '>', '<'], $string);
    }

    /**
     * Транслитерирует строку для использования в алиасе
     * @param string $string - строка для транслитерации
     * @param boolean $replaceDot - заменять ли точку
     * @return string транслитерированная строка
     */
    static function transliterate($string, $replaceDot = true)
    {
        $str = preg_replace('/[^\a-z|^а-я|^\d|^_|^-]/iu', ' ', mb_strtolower($string, 'utf-8'));
        $str = preg_replace('/[-`~!#$%^&*()_=+\\\\@|\\/\\[\\]{};:"\',<>?]+/u', ' ', $str);
        $in = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и',
            'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т',
            'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь',
            'э', 'ю', 'я', ' ', '.'
        ];
        $to = [
            'a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i',
            'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't',
            'u', 'f', 'h', 'c', 'ch', 'sh', 'shch', '', 'y', '',
            'e', 'yu', 'ya', '-', ($replaceDot ? '-' : '.')
        ];

        $clearString = preg_replace('/[-]{2,}/imu', '-', preg_replace('/[_]{2,}/imu', '_', str_replace($in, $to, trim($str))));

        return $clearString;
    }

    /**
     * Получает значение ТИц для текущего домена<br>
     * При вызове на localhost <b>всегда</b> возвращает <b>0</b>
     * @return integer|null значение ТИц
     */
    static function getTIC()
    {
        if (\Yii::$app->request->serverName === 'localhost') {
            return 0;
        }
        $domain = \Yii::$app->request->hostInfo;
        $yandexTIC = file_get_contents(
            "http://bar-navig.yandex.ru/u?ver=2&show=32&url={$domain}", false, stream_context_create(['http' => ['ignore_errors' => true]])
        );
        return !is_null($yandexTIC) ? (int) simplexml_load_string($yandexTIC)->tcy['value'] : null;
    }

    /**
     * Генерирует путь до обрезаной картинки
     * @param string $picPath путь до изображения
     * @param string $width ширина итогового
     * @param string $height высока итогового изображения
     * @param string $type тип ресайза<br>
     * <b>1</b> - точное значение высоты и ширины<br>
     * <b>2</b> - изменяем по большему размеру ресайза в зависимости от картинки<br>
     * <b>3</b> - тянем по ширине до заданного размера и режем по высоте<br>
     * <b>4</b> - тянем по высоте до заданного размера и режем по ширине
     * @param string $resizePath путь до ресайза
     * @return string путь до картинки
     */
    static function getResizePath($picPath, $width = 100, $height = 100, $type = 1, $resizePath = '/resize/')
    {
        $parameterString = http_build_query(['pic' => '..' . $picPath, 'w' => $width, 'h' => $height, 'tp' => $type]);
        return $resizePath . '?' . $parameterString;
    }

    /**
     * Преобразовывает элементы вида {{model::id}} в представление этой модели и {{date::format}} в дату заданного формата<br>
     * Поддерживаемые паттерны:<br>
     * <b>{{map::id}}</b> - карта с метками (модель \app\models\Map)
     * <b>{{parameter::id}}</b> - значение параметра (модель \app\models\Parameter)
     * <b>{{date::format}}</b> - дата формата из функции self::dateTimeFormat(format, 'now')
     * <b>{{lorem::count::tag}}</b> - lorem ipsum count - количество абзацев (5), tag - тэг (p)
     * @param string $content строка для преобразования
     * @return string преобразованная строка
     */
    static function viewContent($content)
    {
        //В модели должен быть статический метод renderView($id),
        //возвращающий строковое представление элемента
        $safeModels = [
            'map' => \app\models\Map::class,
            'parameter' => \app\models\Parameter::class,
        ];

        $chortCodes = [];
        $temp = [];
        preg_match_all('/{{(.+?)}}/mi', $content, $temp);
        foreach ($temp[1] as $key => $tem) {
            $tems = explode('::', $tem);
            $code = array_shift($tems);
            $chortCodes[] = [
                'code' => $code,
                'parameters' => $tems,
                'original' => $temp[0][$key],
            ];
        }

        foreach ($chortCodes as $info) {
            $code = $info['code'];
            $parameters = $info['parameters'];
            switch ($code) {
                case 'date':
                    $result = self::dateTimeFormat($parameters[0]);
                    break;
                case 'lorem':
                    if (YII_DEBUG === false || class_exists('\\joshtronic\\LoremIpsum') === false) {
                        $result = '';
                        break;
                    }
                    $lipsum = new \joshtronic\LoremIpsum();
                    $count = ArrayHelper::getValue($parameters, 0, 5);
                    $tag = htmlspecialchars_decode(ArrayHelper::getValue($parameters, 1, 'p'));
                    $result = $lipsum->paragraphs($count, $tag);
                    break;
                default:
                    $result = '';
            }

            if (isset($safeModels[$code])) {
                $modelName = $safeModels[$code];
                if (!method_exists($modelName, 'renderView')) {
                    throw new \Exception('Метод renderView($id) не найден в классе ' . $modelName);
                }

                $result = $modelName::renderView($parameters[0]);
            }

            $content = str_replace($info['original'], $result, $content);
        }

        return $content;
    }

    /**
     * Форматирует дату с поддержкой русских названий месяцев и дней недели<br>
     * q - название месяца с маленой буквы именительный падеж<br>
     * Q - название месяца с большой буквы именительный падеж<br>
     * f - название месяца с маленой буквы родительный падеж<br>
     * F - название месяца с большой буквы родительный падеж<br>
     * l - название дня недели с маленькой буквы<br>
     * L - название дня недели с большой буквы
     * @param string $format - формат для вывода
     * @param string $date - дата, полученная из базы (без преобразования)
     * @return string отформатированная дата
     */
    static function dateTimeFormat($format = 'd.m.Y H:i:s', $date = 'now')
    {
        if (is_numeric($date) === true) {
            $dateRaw = '@' . $date;
        } else {
            $dateRaw = $date;
        }
        $formatRaw = str_replace(['f', 'F', 'l', 'L', 'q', 'Q'], ['#1#', '#2#', '#3#', '#4#', '#5#', '#6#'], $format);
        $dateTime = new \DateTime($dateRaw);

        $months = [
            1 => 'январь', 2 => 'февраль', 3 => 'март',
            4 => 'апрель', 5 => 'май', 6 => 'июнь',
            7 => 'июль', 8 => 'август', 9 => 'сентябрь',
            10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь'
        ];
        $monthsUp = [
            1 => 'Январь', 2 => 'Февраль', 3 => 'Март',
            4 => 'Апрель', 5 => 'Май', 6 => 'Июнь',
            7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь',
            10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
        ];
        $months_r = [
            1 => 'января', 2 => 'февраля', 3 => 'марта',
            4 => 'апреля', 5 => 'мая', 6 => 'июня',
            7 => 'июля', 8 => 'августа', 9 => 'сентября',
            10 => 'октября', 11 => 'ноября', 12 => 'декабря'
        ];
        $monthsUp_r = [
            1 => 'Января', 2 => 'Февраля', 3 => 'Марта',
            4 => 'Апреля', 5 => 'Мая', 6 => 'Июня',
            7 => 'Июля', 8 => 'Августа', 9 => 'Сентября',
            10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
        ];

        $dayName = [1 => 'понедельник', 2 => 'вторник', 3 => 'среда', 4 => 'четверг', 5 => 'пятница', 6 => 'суббота', 0 => 'воскресенье'];
        $dayNameUp = [1 => 'Понедельник', 2 => 'Вторник', 3 => 'Среда', 4 => 'Четверг', 5 => 'Пятница', 6 => 'Суббота', 0 => 'Воскресенье'];

        $month = (int) $dateTime->format('n');
        $weekDay = (int) $dateTime->format('w');

        return str_replace(['#1#', '#2#', '#3#', '#4#', '#5#', '#6#'], [$months_r[$month], $monthsUp_r[$month], $dayName[$weekDay], $dayNameUp[$weekDay], $months[$month], $monthsUp[$month]], $dateTime->format($formatRaw));
    }

    static function renderProp($model, $prop, $form, $allPropsArray)
    {
        $html = '';
        switch ($allPropsArray[$prop]['prop_type_id']) {
            case 1:
            case 2:
            case 3:
                $html .= $form->field($model, $prop)->textInput(['class' => 'width-100'])->label($allPropsArray[$prop]['name']);
                break;
            case 4:
                switch ($allPropsArray[$prop]['prop_type_list_id']) {
                    case 1:
                        $html .= $form->field($model, $prop)->widget(
                            DropDownList::class, [
                                'items' => ArrayHelper::merge(['Не выбрано'], $allPropsArray[$prop]['values']),
                                'placeholder' => 'Не выбрано']
                        )->label($allPropsArray[$prop]['name']);
                        break;
                    case 2:
                        $html .= $form->field($model, $prop)->widget(CheckBoxList::class, ['items' => $allPropsArray[$prop]['values'], 'addHiddenInput' => true])->label($allPropsArray[$prop]['name']);
                        break;
                    case 3:
                        $html .= $form->field($model, $prop)->widget(RadioList::class, ['items' => ArrayHelper::merge(['Не выбрано'], $allPropsArray[$prop]['values'])])->label($allPropsArray[$prop]['name']);
                        break;
                }
                break;
            case 7:
                $html .= $form->field($model, $prop)->widget(CheckBoxSlide::class, ['choiceLabel' => 'Нет /да'])->label($allPropsArray[$prop]['name']);
                break;
        }
        return $html;
    }

    static function getMenu($arrayPage, $pid, $in = [])
    {
        $page = Tree::find()->andWhere(['in', 'pid', $pid]);
        if (!empty($in)) {
            $page->andWhere($in);
        }
        $page = $page->orderBy('sort')->all();

        if (count($page) >= 1) {

            foreach ($page as $value) {
                $elems = [];
                $elems['label'] = $value->name_menu;
                if ($value->url == "/root") {
                    $value->url = "/";
                }
                $elems['url'] = $value->url;
                if ($value->id != 1) {
                    $sub = self::getMenu([], [$value->id], $in);
                }
                if (!empty($sub)) {
                    $elems['items'] = $sub;
                }
                $arrayPage[] = $elems;
            }
        }
        return $arrayPage;
    }

    private static function mergeParameters($data, $addRowName, $addRow)
    {
        $result = [];
        foreach ($addRow as $newValue) {
            foreach ($data as $block) {
                $block[$addRowName] = $newValue;
                $result[] = $block;
            }
        }
        return $result;
    }

    static function generateParameters($parameters)
    {
        $propsNames = array_keys($parameters);
        $firstParamName = array_shift($propsNames);
        $dataParse = array_shift($parameters);
        $data = [];
        foreach ($dataParse as $value) {
            $data[] = [$firstParamName => $value];
        }
        foreach ($propsNames as $propName) {
            $data = self::mergeParameters($data, $propName, $parameters[$propName]);
        }
        return $data;
    }

    /**
     * Делает количество знаков $count путем добавление нулей перед числом
     * @param integer $number
     * @param integer $count
     * @return string
     */
    static function addZero($number, $count)
    {
        $numberCount = mb_strlen((string) $number, 'utf-8');
        $count -= $numberCount;
        return str_repeat('0', $count > 0 ? $count : 0) . $number;
    }

    /**
     * Генерирует строку с ошибками валидации модели
     * @param array $errors массив ошибок
     * @return string html текст ошибок
     */
    static function getErrorList($errors)
    {
        $html = [];

        foreach ($errors as $name => $attrErrors) {
            $error = implode("<br>\n", $attrErrors);
            $html[] = $error;
        }

        return implode("<br>\n", $html);
    }

    /**
     * Скачивает файл в указанную папку
     * @param string $url путь до удаленного файла
     * @param string $savePath локальный путь сохранения
     * @param string|false $fileName новое имя файла или сохранение оригинального (если false)
     * @return string|false имя скаченного файла
     */
    public static function downloadFile($url, $savePath, $fileName = false)
    {
        if ($fileName === false) {
            $fileNameParse = explode('/', $url);
            $fileName = urldecode($fileNameParse[count($fileNameParse) - 1]);
        }
        if (file_exists($savePath) === false) {
            mkdir($savePath, 0777, true);
        }
        $filePath = str_replace('//', '/', $savePath . '/' . $fileName);

        if (file_exists($filePath) === true) {
            unlink($filePath);
        }

        $options = [
            'http' => [
                'ignore_errors' => true,
            ],
            'https' => [
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
        try {
            if (copy($url, $savePath . $fileName, stream_context_create($options)) === false) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return $fileName;
    }

}
