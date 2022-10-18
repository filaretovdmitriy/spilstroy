<?php

use yii\widgets\ActiveForm;
use app\widgets\AjaxSubmitButton;
?>


<div id="<?= $popupId ?>" class="popup" style="display: none">
    <div class="popup-design">
        <h2>Заказ обратного звонка</h2>
        <?php $form = ActiveForm::begin() ?>
        <div class="popup-form">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Имя'])->label(false) ?>
            <?= $form->field($model, 'phone')->textInput(['placeholder' => 'Телефон'])->label(false) ?>

            <?=
            AjaxSubmitButton::widget([
                'useWithActiveForm' => $form->getId(),
                'label' => 'Отправить',
                'options' => ['class' => 'call-back-button-send'],
                'ajaxOptions' => [
                    'type' => 'POST',
                    'url' => '/ajax/call_back_send',
                    'dataType' => 'json',
                    'success' => new \yii\web\JsExpression("function(data){
                            if (data.success) {
                                $.growl({ title: 'Отправка сообщения', message: 'Ваше сообщение получено.<br>Мы свяжемся с Вами в ближайшее время.', time: 5000});
                                $('#popup-bg').trigger('click');
                                $('#{$popupId} input[type=\"text\"]').val('');
                                $('#{$form->id}').yiiActiveForm('resetForm');
                            } else {
                                if (data.error == 1) {
                                    $.growl.error({ title: 'Отправка сообщения', message: 'Ошибка. Заполните все поля',time: 5000});
                                }
                                if (data.error == 2) {
                                    $.growl.error({ title: 'Отправка сообщения', message: 'Ошибка. Попробуйте отправить сообщение позже',time: 5000});
                                }
                            }
                        }"),
                ],
            ])
            ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>