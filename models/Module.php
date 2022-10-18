<?php

namespace app\models;

class Module extends \app\components\db\ActiveRecord
{

    const DEFAULT_MODULE = 0;

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario
        ];
    }

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите название', 'on' => self::SCENARIO_DEFAULT],
            ['route', 'required', 'message' => 'Введите роут', 'when' => function($model) {
                    return $model->name !== 'Ajax';
                }],
            [['tree_id', 'url', 'route', 'name'], 'safe']
        ];
    }

    public static function tableName()
    {
        return '{{%modules}}';
    }

    public function getPage()
    {
        return $this->hasOne(Tree::class, ['id' => 'tree_id']);
    }

    static function getNamesAsArray($fieldName = 'name')
    {
        $primaryKeys = self::primaryKey();
        $primaryKey = array_shift($primaryKeys);
        return [0 => 'Текстовая страница'] + \yii\helpers\ArrayHelper::map(self::find()->andWhere('route != ""')->orderBy(['name' => SORT_ASC])->all(), $primaryKey, $fieldName);
    }

    /**
     * Получение url'а по роуту из модулей
     * @param string $route роут
     * @param boolean $scheme абсолютный ли путь
     * @param array $parameters get параметры
     * @return string
     * @throws \yii\web\ServerErrorHttpException
     */
    static function getUrlByRoute($route, $scheme = false, $parameters = [])
    {
        $module = self::find()
                ->andWhere(['route' => $route])
                ->one();
        if (is_null($module) === true) {
            throw new \yii\web\ServerErrorHttpException('Не найден модуль по роуту ' . $route);
        }

        $url = '/' . preg_replace('/(\/$)/', '', $module->url);

        if (empty($parameters) === false) {
            $hash = \yii\helpers\ArrayHelper::remove($parameters, '#', false);

            if (empty($parameters) === false) {
                $url .= '?' . http_build_query($parameters);
            }

            if ($hash !== false) {
                $url .= '#' . $hash;
            }
        }
        return \yii\helpers\Url::to($url, $scheme);
    }

}
