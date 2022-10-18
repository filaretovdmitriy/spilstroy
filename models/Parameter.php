<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use app\components\db\ActiveRecordFiles;

class Parameter extends ActiveRecordFiles
{

    const CACHE_NAME = 'parameters';

    public $imageFields = [
        'value' => ['parameters', 'image']
    ];
    public $fileFields = [
        'value' => ['parameters', 'file']
    ];

    const TYPE_TEXT = 1;
    const TYPE_HTML = 2;
    const TYPE_IMAGE = 3;
    const TYPE_FILE = 4;
    const TYPE_MULTI = 5;
    const TYPE_BOOLEAN = 6;

    public function rules()
    {
        $safe = ['name', 'type'];
        if (in_array($this->type, [self::TYPE_HTML, self::TYPE_TEXT, self::TYPE_BOOLEAN]) === true) {
            $safe[] = 'value';
        }

        $rules = [
            ['name', 'required', 'message' => 'Введите название параметра', 'on' => ['default']],
            ['type', 'in', 'range' => array_keys(self::getTypes()), 'message' => 'Выберите правильное значение',],
            ['type', 'default', 'value' => self::TYPE_TEXT],
            [$safe, 'safe']
        ];

        return $rules;
    }

    public function getValues()
    {
        return $this->hasMany(ParameterValue::class, ['parameter_id' => 'id']);
    }

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['name'],
        ];
    }

    /**
     * Получает значение параметра по его идентификатору
     * @param integer $parameterId - идентификатор параметра
     * @param boolean $usePath - использовать ли getPath() для получения web-пути картинки или файла
     * @param boolean $useCache - кешировать ли значение параметра
     * @return string|array|null значение или null, если такой параметр не найден
     */
    public static function getValue($parameterId, $usePath = false, $useCache = false)
    {
        if ($useCache && \Yii::$app->cache->exists(self::CACHE_NAME)) {
            $cacheParameters = \Yii::$app->cache->get(self::CACHE_NAME);
            if (isset($cacheParameters[$parameterId])) {
                return $cacheParameters[$parameterId];
            }
        }
        $parameter = self::findOne($parameterId);
        if (is_null($parameter)) {
            \Yii::trace("Попытка получения несущетвующего параметра (ID::{$parameterId})");
            return null;
        }
        $value = $parameter->value;
        if ($usePath && $parameter->type == self::TYPE_IMAGE) {
            $value = $parameter->getPath('value');
        } elseif ($usePath && $parameter->type == self::TYPE_FILE) {
            $value = $parameter->getPath('value', ActiveRecordFiles::TYPE_FILE_FILE);
        } elseif ($parameter->type == self::TYPE_MULTI) {
            $values = $parameter->getValues()->orderBy(['sort' => SORT_ASC])->all();
            $value = \yii\helpers\ArrayHelper::map($values, 'id', 'value');
        } elseif($parameter->type == self::TYPE_BOOLEAN) {
            $value = $value == 1;
        } else {
            $value = \app\components\IcmsHelper::viewContent($value);
        }
        if ($useCache) {
            $cacheParameters = \Yii::$app->cache->exists(self::CACHE_NAME) ? \Yii::$app->cache->get(self::CACHE_NAME) : [];
            $cacheParameters[$parameterId] = $value;
            \Yii::$app->cache->set(self::CACHE_NAME, $cacheParameters);
        }
        return $value;
    }

    static function renderView($key)
    {
        $parameter = self::findOne($key);
        if (is_null($parameter) === true) {
            return '';
        }

        if ($parameter->type == self::TYPE_FILE || $parameter->type == self::TYPE_IMAGE) {
            return self::getValue($key, true);
        }
        $value = self::getValue($key);
        if ($parameter->type == self::TYPE_MULTI) {
            $value = implode(', ', $value);
        }
        return $value;
    }

    public static function getTypes($typeId = null)
    {
        $types = [
            self::TYPE_TEXT => 'Текст',
            self::TYPE_HTML => 'Текст с разметкой',
            self::TYPE_IMAGE => 'Изображение',
            self::TYPE_FILE => 'Файл',
            self::TYPE_MULTI => 'Множественное',
            self::TYPE_BOOLEAN => 'Логическое',
        ];
        if (is_null($typeId) === true) {
            return $types;
        }
        return $types[$typeId];
    }

    public static function tableName()
    {
        return '{{%parameters}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {

        if ($this->isAttributeChanged('type') === true && $changedAttributes['type'] == self::TYPE_IMAGE) {
            $oldFile = \Yii::getAlias('@upload/images/') . $this->imageFields['value'][0] . '/' . $changedAttributes['value'];
            if (file_exists($oldFile) === true) {
                unlink($oldFile);
            }
        }

        if ($this->isAttributeChanged('type') === true && $changedAttributes['type'] == self::TYPE_FILE) {
            $oldFile = \Yii::getAlias('@upload/files/') . $this->fileFields['value'][0] . '/' . $changedAttributes['value'];
            if (file_exists($oldFile) === true) {
                unlink($oldFile);
            }
        }

        if ($this->isAttributeChanged('type') === true && $changedAttributes['type'] == self::TYPE_MULTI) {
            ParameterValue::deleteAll(['parameter_id' => $this->id]);
        }

        \Yii::$app->cache->delete(self::CACHE_NAME);
        parent::afterSave($insert, $changedAttributes);
    }

}
