<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\icms\widgets\CheckBoxSlide;
use app\modules\icms\assets\IcmsAsset;
use yii\helpers\Url;
?>
<?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'message', 'options' => ['title' => 'Восстановление пароля'], 'flash' => 'success']) ?>
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

            <div class='title'>Вход в систему</div>

            <?= $form->field($model, 'login')->label('Логин') ?>
            <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
            <?= $form->field($model, 'rememberMe')->widget(CheckBoxSlide::class, ['choiceLabel' => 'Запомнить меня'])->label(false) ?>

            <?= Html::submitButton('Войти', ['class' => 'button_submit', 'name' => 'login-button']) ?>
            <div class="center margin-top-10">
                <a href="<?= Url::to(['default/lost_password']) ?>">Забыли пароль?</a>
            </div>
            <?php ActiveForm::end(); ?>
            <div class='shadow'></div>
        </div>
    </div>
</div>