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
            <h2>Восстановить пароль</h2>
            <div class="divider-xs"></div>
            <div class="divider-sm"></div>
            <?php $form = ActiveForm::begin([
                'id' => 'lost-password-form',
                'options' => ['class' => 'log-reg-block sky-form'],
                'fieldConfig' => [
                    'template' => "<p><div>{label}</div>\n<div>{input}</div><div class=\"error\">{error}</div></p>",
                    'labelOptions' => ['class' => 'control-label'],
                ],
            ]); ?>
                <div class="form-group">
                    <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'placeholder' => 'E-mail'])->label(false) ?>
                </div>
                <div class="row">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-cool btn-md-sm', 'name' => 'pass-button']) ?>
                </div>
                <?php if (Yii::$app->session->hasFlash('complite')) { ?>
                    <div class="alert alert-success fade in">
                       <?=Yii::$app->session->getFlash('complite')?>
                    </div>
                <?php } ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>