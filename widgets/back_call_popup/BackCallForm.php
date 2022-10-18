<?php

namespace app\widgets\back_call_popup;

use yii\base\Model;

class BackCallForm extends Model
{

    public $name;
    public $phone;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Введите ФИО'],
            ['name', 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }],
            ['phone', 'required', 'message' => 'Введите номер телефона'],
            ['phone', 'filter', 'filter' => function($value) {
                return trim(strip_tags($value));
            }],
        ];
    }

}
