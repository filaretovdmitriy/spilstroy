<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class Tree extends \app\components\db\ActiveRecordFiles
{

    public static function tableName()
    {
        return '{{%tree}}';
    }

    public $imageFields = [
        'image' => ['tree', 'tree']
    ];

    public function rules()
    {
        return [
            ['pid', 'required', 'strict' => true, 'message' => 'Заполните поле Родительская страница'],
            ['name_menu', 'required', 'message' => 'Заполните поле Название страницы', 'except' => 'search'],
            ['auto_url', 'default', 'value' => 1, 'on' => 'add'],
            ['name', 'match', 'pattern' => '/[a-zA-Z0-9_-]+$/', 'message' => 'Только латинские буквы и цифры'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE, 'on' => 'add'],
            ['name', 'required', 'message' => 'Заполните поле URL', 'when' => function($model) {
                    return $model->url != '/';
                }, 'whenClient' => "function (attribute, value) {
                return $(attribute.input).is(':hidden') === false;
            }"],
            [
                '!image', 'file',
                'extensions' => ['png', 'jpg', 'gif'], 'wrongExtension' => 'Доступные форматы: {extensions}',
            ],
            [['content', 'h1_seo', 'title_seo', 'description_seo', 'keywords_seo', 'in_menu', 'in_menu_bottom', 'in_map', 'nofollow', 'sort', 'status', 'is_safe', 'auto_url'], 'safe', 'on' => 'default'],
            [['pid', 'name', 'name_menu', 'auto_url'], 'safe', 'on' => 'add'],
        ];
    }

    public function scenarios()
    {
        $defaultScenario = parent::scenarios()[self::SCENARIO_DEFAULT];
        return [
            self::SCENARIO_DEFAULT => $defaultScenario,
            'filter' => ['url', 'name_menu'],
            'add' => ['pid', 'name', 'name_menu', 'auto_url', 'status'],
        ];
    }

    public function getImages() {
        return $this->hasMany(TreeGallery::class, ['tree_id' => 'id']);
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getParent()
    {
        return $this->hasOne(Tree::class, ['id' => 'pid']);
    }

    public function getContent()
    {
        return \app\components\IcmsHelper::viewContent($this->content);
    }

    public function getModule()
    {
        return $this->hasOne(Module::class, ['tree_id' => 'id']);
    }

    public function getPages()
    {
        return $this->hasMany(Tree::class, ['pid' => 'id']);
    }

    /**
     * Обновляет уровень у всех страних
     * @param integer $pid - с какого родителя начинать
     * @param integer $level - уровень для отчета
     */
    public static function updateLevels($pid = 0, $level = 0)
    {
        $pages = self::findAll(['pid' => $pid]);
        if (empty($pages)) {
            return;
        }
        self::updateAll(['level' => $level], ['pid' => $pid]);
        foreach ($pages as $page) {
            self::updateLevels($page->id, $level + 1);
        }
    }

    /**
     * Получает дерево модели
     * @param boolean $addRoot - Добавлять ли нулевой элемент
     * @param array $array - массив
     * @param integer $startLevel - счетчик уровня
     * @param integer $pid - с какого элемента начинать
     * @param string $fieldSort - поле по которому сортируются данные
     * @param string $fieldPid - поле для связи
     * @param string $fieldName - поле имени
     * @param string $fieldPK - поле первичного ключа
     * @return array массив вида [id => $fieldName]
     */
    static function getNamedTreeAsArray($addRoot = false, $array = [], $startLevel = 0, $pid = 0, $fieldSort = 'sort', $fieldPid = 'pid', $fieldName = 'name_menu', $fieldPK = 'id')
    {
        if ($addRoot) {
            $array[0] = 'Корневой';
            $addRoot = false;
        }
        $models = self::find()->andWhere([$fieldPid => $pid])->orderBy($fieldSort)->all();
        if (count($models) > 0) {
            $startLevel++;
            foreach ($models as $value) {
                $array[$value->{$fieldPK}] = str_repeat('-', $value->level + 1) . " " . $value->{$fieldName} . ' (' . str_replace('/root', '/', $value->url) . ')';
                $array = self::getNamedTreeAsArray($addRoot, $array, $startLevel, $value->{$fieldPK}, $fieldSort, $fieldPid, $fieldName, $fieldPK);
            }
            return $array;
        } else {
            return $array;
        }
    }

    /**
     * Обновляет Url'ы у всех страниц и Url модулей
     */
    static function buildUrls()
    {
        $pages = self::find()->indexBy('id')->orderBy(['level' => SORT_ASC])->all();
        $modules = Module::find()->indexBy('tree_id')->andWhere(['IS NOT', 'tree_id', null])->all();
        foreach ($pages as $id => $page) {
            if ($page->url == '/') {
                continue;
            }

            $urlPieces = [$page->name];
            $parent = $pages[$page->pid];
            while ($parent->pid != 0) {
                $urlPieces[] = $parent->name;
                $parent = $pages[$parent->pid];
            }
            $newUrl = '/' . implode('/', array_reverse($urlPieces));
            if ($page->url === $newUrl) {
                continue;
            }
            $page->url = $newUrl;
            $page->save();
            if (isset($modules[$id])) {
                $modules[$id]->url = preg_replace('/(^\/)/', '', $page->url) . '/';
                $modules[$id]->save();
            }
        }
    }

    /**
     * Получает первого родителя (исключая главную страницу)
     * @param string $relation - название связи
     * @return self родитель
     */
    public function getFirstParent($relation = 'parent')
    {
        $parent = $this->{$relation};
        $result = $parent;
        if (is_null($parent) === true) {
            return null;
        }

        while (is_null($parent) === false && $parent->url !== '/') {
            $result = $parent;
            $parent = $parent->{$relation};
        }

        return $result;
    }

    public function beforeSave($insert)
    {

        if ($insert === true || $this->isAttributeChanged('pid') === true) {
            $parent = $this->getFirstParent();
            $this->main_id = is_null($parent) === true ? 0 : $parent->id;
        }
        
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        self::buildUrls();
        if ((isset($changedAttributes['pid']) && $changedAttributes['pid'] != $this->pid) || $insert) {
            self::updateLevels();
        }
    }

    public function beforeDelete()
    {

        if ($this->is_safe && !\Yii::$app->user->can('developer')) {
            return false;
        }

        $module = $this->module;
        if (!is_null($module)) {
            $module->tree_id = null;
            $module->url = null;
            $module->save();
        }

        foreach ($this->pages as $page) {
            $page->delete();
        }
        return parent::beforeDelete();
    }

    static function siteMap()
    {
        return [
            'title' => 'Структура',
            'url' => [
                'page' => 'url'
            ],
            'condition' => ['in_map' => 1, 'status' => self::STATUS_ACTIVE],
        ];
    }

}
