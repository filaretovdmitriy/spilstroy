<?php

namespace app\components\db;

use yii\web\UploadedFile;
use app\components\IcmsHelper;

class ActiveRecordFiles extends ActiveRecord
{

    const TYPE_FILE_IMAGE = 1;
    const TYPE_FILE_FILE = 2;
    const CROP_REQUEST_NAME = 'image-crop';
    const NAME_RANDOM = 0x0100;
    const NAME_ORIGINAL = 0x0300;
    const NAME_ORIGINAL_ID = 0x0400;
    const NAME_TRANSLIT_ORIGINAL = 0x0301;
    const NAME_TRANSLIT_ORIGINAL_ID = 0x0401;

    /**
     * Информация о полях, содержащих изображение в виде ['field1' => ['folder1', 'prefix1'], 'field2' => ['folder2', 'prefix2']]
     * @var array
     */
    public $imageFields = [];

    /**
     * Информация о полях, содержащих файлы отличные от изображений в виде ['field1' => ['folder1', 'prefix1'], 'field2' => ['folder2', 'prefix2']]
     * @var array
     */
    public $fileFields = [];

    /**
     * Разделитель в названии файла между префиксом и id модели
     * @var string
     */
    public $filesNameSeparator = '_';

    private $_filesInfo = [];

    /**
     * Собирает информацию о файловых полях модели
     */
    private function _initFileInfo()
    {
        if (!empty($this->imageFields)) {
            foreach ($this->imageFields as $fileField => $fileFieldInfo) {
                $name = self::NAME_TRANSLIT_ORIGINAL;
                if (isset($fileFieldInfo[1]) && !empty($fileFieldInfo[1])) {
                    $name = $fileFieldInfo[1];
                }
                $this->_filesInfo[$fileField] = [
                    'path' => \Yii::getAlias('@upload/images/') . $this->compileFilePath($fileFieldInfo[0]),
                    'name' => $name,
                    'webPath' => \Yii::getAlias('@images/') . $this->compileFilePath($fileFieldInfo[0])
                ];
            }
        }

        if (!empty($this->fileFields)) {
            foreach ($this->fileFields as $fileField => $fileFieldInfo) {
                $name = self::NAME_TRANSLIT_ORIGINAL;
                if (isset($fileFieldInfo[1]) && !empty($fileFieldInfo[1])) {
                    $name = $fileFieldInfo[1];
                }
                if (isset($this->_filesInfo[$fileField])) {
                    $fileField .= '__file';
                }
                $this->_filesInfo[$fileField] = [
                    'path' => \Yii::getAlias('@upload/files/') . $this->compileFilePath($fileFieldInfo[0]),
                    'name' => $name,
                    'webPath' => \Yii::getAlias('@files/') . $this->compileFilePath($fileFieldInfo[0])
                ];
            }
        }
    }

    private function _initLoadFiles()
    {
        foreach ($this->_filesInfo as $fieldName => $fieldInfo) {
            $loadFile = UploadedFile::getInstance($this, $fieldName);
            if (is_null($loadFile) === false && $loadFile->error === UPLOAD_ERR_OK) {
                $this->{$fieldName} = $loadFile;
            }
        }
    }

    public function load($data, $formName = null)
    {
        if (empty($this->_filesInfo) === true) {
            $this->_initFileInfo();
        }

        $this->_initLoadFiles();
        return parent::load($data, $formName);
    }

    public function afterValidate()
    {
        parent::afterValidate();

        foreach ($this->_filesInfo as $fieldName => $fieldInfo) {
            $fieldNameReal = str_replace('__file', '', $fieldName);
            if ($this->isAttributeSafe($fieldName) === true || $this->{$fieldNameReal} === false) {
                continue;
            }

            if (isset($this->attributes[$fieldName]) === false) {
                continue;
            }

            if (($this->{$fieldNameReal} instanceof UploadedFile) === false || (is_null($this->{$fieldNameReal}) === true)) {
                $this->{$fieldNameReal} = $this->getOldAttribute($fieldNameReal);
            }
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->_initFileInfo();
    }

    /**
     * Генерирует путь до файла с учетом
     * @param array|string $pathPieces - путь или массив из папок или названий полей, значение которые будет использовано как навзание папки
     * @return string - итоговый путь до файла
     */
    public function compileFilePath($pathPieces)
    {
        if (!is_array($pathPieces)) {
            return $pathPieces . '/';
        }

        $path = [];
        foreach ($pathPieces as $pathPiece) {
            if ($this->hasAttribute($pathPiece)) {
                $path[] = $this->{$pathPiece};
            } else {
                $path[] = $pathPiece;
            }
        }

        return implode('/', $path) . '/';
    }

    /**
     * Сохраняет все файловые поля.<br>
     * Можно задать тип поля (файл или изображение) для конкрентых полей. Обязательно задавать если тип поля может менять в зависимости от каких-либо условий
     * @param array $fieldTypes - массив вида ['field' => TYPE]
     */
    public function saveFiles($fieldTypes = [])
    {
        foreach ($this->_filesInfo as $fieldName => $fileInfo) {
            $type = null;
            if (isset($fieldTypes[$fieldName])) {
                $type = $fieldTypes[$fieldName];
            }
            $this->saveFile($fieldName, $type);
        }
    }

    /**
     * Сохраняет изображение на диске и название изображения в базе<br>
     * Папка создаётся автоматически<br>
     * Старое изображение предварительно удаляется
     * @param string $field Название файлового поля для сохранения
     * @param integer $type Тип файла
     * @param UploadedFile $file Загруженный файл. Не обязателен. Используется в случае, когда файл загружается не по имени поля модели
     * @param boolean $deleteTempFile Удалять ли временный файл после сохранения
     */
    public function saveFile($field, $type = null, UploadedFile $file = null, $deleteTempFile = true)
    {
        if ($this->isNewRecord) {
            throw new \yii\web\HttpException(500, 'Невозможно сохранить изображение у не сохраненной модели (нужен заполненный primaryKey)');
        }
        if (is_null($file) === true) {
            $fieldNameReal = str_replace('__file', '', $field);
            if ($this->{$fieldNameReal} instanceof UploadedFile) {
                $file = $this->{$fieldNameReal};
            }
        }
        if ($file) {
            $fileInfo = $this->_filesInfo[$field];
            if (isset($this->_filesInfo[$field . '__file']) && $type == self::TYPE_FILE_FILE) {
                $fileInfo = $this->_filesInfo[$field . '__file'];
            }
            $this->{$field} = $this->generateFileName($file, $fileInfo['name'], $field);
            $path = $fileInfo['path'];
            $oldImagePath = $path . $this->{$field};
            if (!empty($this->{$field}) && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $this->save(false);
            $cropInfo = \Yii::$app->request->post(self::CROP_REQUEST_NAME, false);
            if ($type !== self::TYPE_FILE_FILE && $cropInfo !== false && empty($cropInfo) === false && isset($cropInfo[self::class]) && isset($cropInfo[self::class][$field]) && isset($cropInfo[self::class][$field]['check']) && ($cropInfo[self::class][$field]['width'] || $cropInfo[self::class][$field]['height'])) {
                $width = $cropInfo[self::class][$field]['width'] ?: null;
                $height = $cropInfo[self::class][$field]['height'] ?: null;

                $imagine = new \Imagine\Gd\Imagine();
                $image = $imagine->open($file->tempName);
                $currentSize = $image->getSize();
                $ratio = $currentSize->getWidth() / $currentSize->getHeight();

                if (is_null($height)) {
                    $ratio = $currentSize->getWidth() / $currentSize->getHeight();
                    $height = round($width / $ratio);
                }
                if (is_null($width)) {
                    $ratio = $currentSize->getHeight() / $currentSize->getWidth();
                    $width = round($height / $ratio);
                }
                $size = new \Imagine\Image\Box($width, $height);
                $image->resize($size)->save($path . $this->{$field});
            } else {
                $file->saveAs($path . $this->{$field}, $deleteTempFile);
            }
        } else {
            if (isset($this->oldAttributes[$field]) && empty($this->{$field})) {
                $this->{$field} = $this->oldAttributes[$field];
            }
        }
    }

    /**
     * Сохраняет изображение на диске для модели<br>
     * Папка создаётся автоматически<br>
     * Старое изображение предварительно удаляется
     * @param string $field Название файлового поля для сохранения
     * @param string $fromPath Полный путь до файла, который будет привязан к модели
     * @param integer $type Тип файла
     * @return bool
     * @throws \yii\web\HttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function saveFileFromPath($field, $fromPath, $type = null)
    {
        if ($this->isNewRecord === true) {
            throw new \yii\web\HttpException(500, 'Невозможно сохранить изображение у не сохраненной модели (нужен заполненный primaryKey)');
        }

        if (file_exists($fromPath) === false) {
            if (mb_strpos($fromPath, 'http', 0, 'utf-8') === 0) {
                $headers = get_headers($fromPath);
                $responseCode = (integer) substr($headers[0], 9, 3);
                if ($responseCode != 200) {
                    throw new \yii\web\ServerErrorHttpException("Файл по адресу '{$fromPath}' не доступен - код: {$responseCode}");
                }
            } else {
                throw new \yii\web\ServerErrorHttpException("Файл не найден '{$fromPath}'");
            }
        }

        $fileInfo = $this->_filesInfo[$field];
        if (isset($this->_filesInfo[$field . '__file']) && $type == self::TYPE_FILE_FILE) {
            $fileInfo = $this->_filesInfo[$field . '__file'];
        }

        $fileName = basename($fromPath);
        $fileExtension = preg_replace('/(^.*\.)/', '', $fileName);
        $this->{$field} = $this->_generateFileName($fileName, $fileExtension, $fileInfo['name'], $field);

        $path = $fileInfo['path'];
        $oldImagePath = $path . $this->{$field};
        if (!empty($this->{$field}) && file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
        if (file_exists($path) === false) {
            mkdir($path, 0777, true);
        }

        if (copy($fromPath, $path . $this->{$field}) === true) {
            return $this->save(false);
        }

        return false;
    }

    private function _generateFileName($fileName, $fileExtension, $prefix, $field)
    {
        if ($prefix === self::NAME_ORIGINAL) {
            return $fileName;
        }
        if ($prefix === self::NAME_RANDOM) {
            return md5($field . time() . __CLASS__ . $this->id) . '.' . $fileExtension;
        }
        if ($prefix === self::NAME_ORIGINAL_ID) {
            return $this->id . $this->filesNameSeparator . $fileName;
        }
        if ($prefix === self::NAME_TRANSLIT_ORIGINAL) {
            return IcmsHelper::transliterate($fileName, false);
        }
        if ($prefix === self::NAME_TRANSLIT_ORIGINAL_ID) {
            return $this->id . $this->filesNameSeparator . IcmsHelper::transliterate($fileName, false);
        }
        return $prefix . $this->filesNameSeparator . $this->id . '.' . $fileExtension;
    }

    /**
     * Генеринует имя файла
     * @param \yii\web\UploadedFile $file - файл изображения
     * @param string $prefix - префикс
     * @param string $field - название поля
     * @return string - имя изображения
     */
    public function generateFileName(\yii\web\UploadedFile $file, $prefix, $field)
    {
        return $this->_generateFileName($file->name, $file->extension, $prefix, $field);
    }

    /**
     * Получает web путь до файла
     * @param string $field - для какого поля следует получить путь
     * @param integer $type - тип файла (изображение или файл). Задаётся только для спорных полей
     * @return string - путь для веба
     */
    public function getPath($field, $type = null)
    {
        if ($this->isNewRecord) {
            return null;
        }
        $fileInfo = $this->_filesInfo[$field];
        if (isset($this->_filesInfo[$field . '__file']) && $type == self::TYPE_FILE_FILE) {
            $fileInfo = $this->_filesInfo[$field . '__file'];
        }
        if ($this->{$field}) {
            return $fileInfo['webPath'] . $this->{$field};
        } else {
            return null;
        }
    }

    /**
     * Получает путь до файла
     * @param string $field - для какого поля следует получить путь
     * @param integer $type - тип файла (изображение или файл). Задаётся только для спорных полей
     * @param boolean $addFileName - Добавлять или нет название файла к пути
     * @return string - путь до файла
     */
    public function getFullPath($field, $type = null, $addFileName = true)
    {
        $fileInfo = $this->_filesInfo[$field];
        if (isset($this->_filesInfo[$field . '__file']) && $type == self::TYPE_FILE_FILE) {
            $fileInfo = $this->_filesInfo[$field . '__file'];
        }
        return $fileInfo['path'] . ($addFileName ? $this->{$field} : '');
    }

    /**
     * Получает web путь до файла и применят к нему ресайз.<br>
     * <b>Только для изображений!!!</b>
     * @param string $field - для какого поля следует получить путь
     * @param string $width - ширина итогового
     * @param string $height - высока итогового изображения
     * @param string $type - тип ресайза<br>
     * <b>1</b> - точное значение высоты и ширины<br>
     * <b>2</b> - изменяем по большему размеру ресайза в зависимости от картинки<br>
     * <b>3</b> - тянем по ширине до заданного размера и режем по высоте<br>
     * <b>4</b> - тянем по высоте до заданного размера и режем по ширине
     * @param string $resizePath - путь до ресайза
     * @return string путь до картинки
     */
    public function getResizePath($field, $width = 100, $height = 100, $type = 1, $resizePath = '/resize/')
    {
        if ($this->{$field}) {
            return \app\components\IcmsHelper::getResizePath($this->getPath($field, self::TYPE_FILE_IMAGE), $width, $height, $type, $resizePath);
        } else {
            return null;
        }
    }

    /**
     * Получает web путь до файла и применят к нему ресайз и/или ватермарку.<br>
     * <b>Только для изображений!!!</b>
     * @param string $field - для какого поля следует получить путь
     * @param string $width - ширина итогового
     * @param string $height - высока итогового изображения
     * @param string $type - тип ресайза<br>
     * <b>1</b> - точное значение высоты и ширины<br>
     * <b>2</b> - изменяем по большему размеру ресайза в зависимости от картинки<br>
     * <b>3</b> - тянем по ширине до заданного размера и режем по высоте<br>
     * <b>4</b> - тянем по высоте до заданного размера и режем по ширине<br>
     * <b>5</b> - вписываем изображение в блок с заданными размерами и отображаем по центру блока
     * @param string $watermark - путь до ресайза. Параметры могут быть<br>
     * [x => 'координата x', y => 'координата y'] - знак по координатам<br>
     * ['center' => true] - знак по центру<br>
     * ['fill' => true, x => 'Отступ между водяными знаками по горизонтали', y => 'Отступ между водяными знаками по вертикали'] - заполнение водяными знаками
     * @return string путь до картинки в кеше
     */
    public function getResizeCache($field, $width = 100, $height = 100, $type = 1, $watermark = false)
    {
        if (empty($this->{$field}) === true) {
            return null;
        }
        $picPath = $this->getFullPath($field, self::TYPE_FILE_IMAGE);
        return \app\components\Resize::start($picPath, $width, $height, $type, $watermark);
    }

    public function beforeDelete()
    {
        foreach ($this->_filesInfo as $fieldName => $fileInfo) {
            $fieldName = str_replace('__file', '', $fieldName);
            if (!empty($this->{$fieldName}) && file_exists($fileInfo['path'] . $this->{$fieldName})) {
                unlink($fileInfo['path'] . $this->{$fieldName});
            }
        }
        return parent::beforeDelete();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->_filesInfo = [];
            $this->_initFileInfo();
        }
    }

    /**
     * Получает расширение файла из заданного поля
     * @param string $field название поля
     * @return null|string расширение или null, если файл не загружен
     */
    public function getFileExtention($field) {
        if (isset($this->_filesInfo[$field]) === false) {
            throw new \yii\web\ServerErrorHttpException(self::class . "::{$field} не является файловым полем");
        }

        $value = $this->{$field};
        if (empty($value) === true) {
            return null;
        }

        return preg_replace('/(^.*\.)/', '', $value);
    }

}
