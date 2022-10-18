<?php

namespace app\modules\icms\widgets;

use yii\widgets\ActiveForm;

class ActiveFormIcms extends ActiveForm
{

    public $options = [
        'class' => 'forms forms-columnar',
        'enctype' => 'multipart/form-data'
    ];
    public $fieldConfig = [
        'template' => "<fieldset>{label}\n<div>{input}</div><div class=\"error\">{error}</div></fieldset>",
        'labelOptions' => ['class' => '']
    ];

}
