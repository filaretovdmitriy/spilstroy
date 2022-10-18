<?php
/* @var $this app\components\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="row">
    <div class="col-md-7 md-margin-bottom-50 log-reg-v3">
        <div class="rect-nohover rect-equal-height">
            <?= $this->tree->getContent() ?>
        </div>
    </div>

    <div class="col-md-5 log-reg-v3">
        <div class="rect-nohover rect-equal-height">
            <h2>Создайте новый аккаунт</h2>
            <div class="divider-xs"></div>
            <div class="divider-sm"></div>
            <?php $form = ActiveForm::begin(['id' => 'form-signup',
                'options' => ['class' => 'log-reg-block sky-form'],
                'fieldConfig' => [
                    'template' => "<p><div>{label}</div>\n<div>{input}</div><div class=\"error\">{error}</div></p>",
                ]]); ?>
                <div class="form-group">
                    <?= $form->field($model, 'login')->textInput(['class'=>'form-control', 'placeholder' => 'Логин'])->label(false) ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'email')->textInput(['class'=>'form-control', 'placeholder' => 'E-mail'])->label(false) ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control', 'placeholder' => 'Пароль'])->label(false) ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'repassword')->passwordInput(['class'=>'form-control', 'placeholder' => 'Повторите пароль'])->label(false) ?>
                </div>
                <p>У Вас уже есть аккаунт? <a href="<?= yii\helpers\Url::to(['account/login']) ?>">Войдите</a></p>
                <div class="row">
                    <?= Html::submitButton('Создать', ['class' => 'btn btn-cool btn-md-sm', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>