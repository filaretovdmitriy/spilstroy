<?php
/* @var $this app\components\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>

<div class="row">
    <div class="col-md-7 md-margin-bottom-50 log-reg-v3">
        <div class="rect-nohover rect-equal-height">
            <?= $this->tree->getContent() ?>
        </div>
    </div>

    <div class="col-md-5 log-reg-v3">
        <div class="rect-nohover rect-equal-height">
        <h2>Вход в аккаунт</h2>
        <div class="divider-xs"></div>
        <div class="divider-sm"></div>
        <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'log-reg-block sky-form'],
                'fieldConfig' => [
                    'template' => "<p><div>{label}</div>\n<div>{input}</div><div class=\"error\">{error}</div></p>",
                    'labelOptions' => ['class' => 'control-label'],
                ],
            ]); ?>
            <div class="form-group">
                <?= $form->field($model, 'login')->textInput(['class'=>'form-control', 'placeholder' => 'Логин'])->label(false) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control', 'placeholder' => 'Пароль'])->label(false) ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                        <?= $form->field($model, 'rememberMe', [
                            'template' => "{input} &nbsp; {label}\n<div class=\"col-lg-8\">{error}</div>",
                            'labelOptions' => ['class' => 'control-label'],
                        ])->checkbox([],false)->label('Запомнить меня') ?>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <p><a href="<?= Url::to(['account/lost_password']) ?>">Забыли пароль?</a></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?= Html::submitButton('Вход', ['class' => 'btn btn-cool btn-md-sm', 'name' => 'login-button']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
    </div>
    
    
</div>