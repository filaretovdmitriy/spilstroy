<?php
/* @var $this app\components\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>


<div class="row">
    <!--Left Sidebar-->
    <div class="col-md-3 md-margin-bottom-40">
        <ul class="list-group sidebar-nav-v1 margin-bottom-40" id="sidebar-nav-1">
            <li class="list-group-item active">
                <a href="<?= Url::to(['account/account']) ?>"><i class="fa fa-bar-chart-o"></i> Аккаунт</a>
            </li>
            <li class="list-group-item">
                <a href="<?= Url::to(['account/account_history']) ?>"><i class="fa fa-history"></i> История заказов</a>
            </li>
        </ul>


    </div>
    <!--End Left Sidebar-->

    <!-- Profile Content -->
    <div class="col-md-9">
        <div class="profile-body margin-bottom-20 log-reg-v3">
            <h2>Данные вашего аккаунта</h2>
            <?php
            $form = ActiveForm::begin(['id' => 'form-signup',
                'options' => ['class' => 'log-reg-block sky-form'],
                'fieldConfig' => array(
                    'template' => "<div>{label}</div>\n<div>{input}</div><div class=\"error\">{error}</div>",
                )]); ?>
            <section>
            <?php
            echo $form->field($model, 'name')->textInput(['class' => 'form-control'])->label('Имя'); ?>
            </section>
            <section>
            <?= $form->field($model, 'login')->textInput(['class' => 'form-control'])->label('Логин') ?>
            </section>
            <section>
            <?= $form->field($model, 'email')->textInput(['class' => 'form-control'])->label('E-mail') ?>
            </section>
            <section>
            <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control'])->label('Пароль') ?>
            </section>
            <section>
            <?= $form->field($model, 'password_repeat')->passwordInput(['class' => 'form-control'])->label('Повторите пароль') ?>
            </section>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-cool', 'name' => 'signup-button']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <!-- End Profile Content -->
</div>

