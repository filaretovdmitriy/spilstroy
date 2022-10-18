<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\icms\assets\IcmsAsset;
?>
<div class="login_page">
    <div class='container'>
        <div class='form_outer'>
            <div class="center">
                <a class='logo' href='/icms'>
                    <img src="<?= IcmsAsset::path('img/logo.png') ?>">
                </a>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'login'],
                'fieldConfig' => [
                    'template' => "<fieldset class=\"first\">{label}<div class=\"message-login\">{error}</div><div class=\"col-lg-3\">{input}</div>\n</fieldset>",
                ],
            ]); ?>

            <div class='title'>Восстановление пароля</div>
            <?= $form->field($model, 'email')->label('Ваш email') ?>
            <?= Html::submitButton('Оправить', ['class' => 'button_submit', 'name' => 'login-button']) ?>
            <?php ActiveForm::end(); ?>
            <div class='shadow'></div>
        </div>
    </div>
</div>