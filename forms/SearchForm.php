<?php

namespace app\forms;

use yii\base\Model;

class SearchForm extends Model
{

    public $searchText;

    public function rules()
    {
        return [
            [['searchText'], 'required', 'message' => 'Введите запрос для поиска'],
            ['searchText', 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }],
            [['searchText'], 'safe'],
        ];
    }

}
