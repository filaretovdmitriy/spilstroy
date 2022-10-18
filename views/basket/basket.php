<?php
/* @var $this app\components\View */

use yii\helpers\Html;
use app\components\IcmsHelper;
use yii\widgets\ActiveForm;
use app\widgets\RadioList;
use app\assets\AppAsset;
?>

    <h1><?= $this->h1 ?></h1>
    <div class="basket-wraper">
        <?php if (count($goods) > 0) { ?>
            <?php
            $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'shopping-cart'
                        ]
            ]);
            ?>

                <div class="basket-items-wrapper">
                    
                    <?php foreach ($goods as $orderGoodId => $good) { ?>
                        <div class="basket-catalog-item"  id="order-good-<?= $orderGoodId ?>">                        
                                <div class="backet-image">
                            <?php
                                if (!empty($good->image)) {
                                    echo Html::a(
                                            Html::img(IcmsHelper::getResizePath($good->image, 170, 170), ['class' => 'img-responsive', 'alt' => $good->name]), $good->url
                                    );
                                }
                            ?>
                            </div>
                            <div class="backet-item-name">
                                <a href="<?= $good->url ?>"><?= $good->name ?></a>
                                <?php if (!empty($good->article)) { ?>
                                    <span><?= $good->article ?></span>
                                <?php } ?>
                            </div>
                            <div class="basket-item-price"  id="product-price-<?= $orderGoodId ?>"><?= number_format($good->price, 2, '.', ' ') ?></div>
                            <div class="basket-item-count">
                                <a href="#" class="button-change-count button-minus">-</a>
                                <input type='text' class="form-control input-quantity update-order-count" data-id="<?= $orderGoodId ?>" value="<?= $good->quant ?>" id='quantity-field-<?= $orderGoodId ?>'/>
                                <a href="#" class="button-change-count button-plus">+</a>
                            </div>
                            <div id="product-summ-<?= $orderGoodId ?>" class="basket-item-sum"><?= number_format($good->summ, 2, '.', ' ') ?></div>
                            <div class="basket-item-delete"><button type="button" class="close basket-delete-good" data-id="<?= $orderGoodId ?>"><span class="sr-only">Удалить</span></button></div>
                        </div>
                    <?}?>
                </div>

                <div class="basket-order-wrapper">
                    <div class="basket-form-wrapper">
                        <div class="basket-form-payment-delivery">
                            <div class="basket-form-payment-wrapper">
                                <div class="basket-form-title">
                                    <img src="<?= AppAsset::path('images/delivery-icon.svg') ?>">
                                    <span>Способы доставки</span>
                                    
                                </div>
                                <div class="basket-form-payment">
                                        <?=$form->field($order, 'catalog_delivery_id')->widget(RadioList::class, [
                                            'items' => IcmsHelper::map($deliverys, 'id', 'name'),
                                            'itemsOptions' => IcmsHelper::modelAttributesToData($deliverys, 'id', ['have_address']),
                                        ])->label(false);
                                        ?>
                                        <?php foreach ($deliverys as $delivery) { ?>
                                            <div id="basket-delivery-desctiption-<?= $delivery->id ?>" class="basket-delivery-desctiption" style="<?= $order->catalog_delivery_id != $delivery->id ? 'display: none' : '' ?>">
                                                <?= $delivery->content ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                            </div>
                            <div class="basket-form-delivery-wrapper">
                                <div class="basket-form-title">
                                    <img src="<?= AppAsset::path('images/payment-icon.svg') ?>">
                                    <span>Способы оплаты</span>
                                </div>
                                <div class="basket-form-delivery">
                                    <?=
                                    $form->field($order, 'catalog_pay_id')->widget(RadioList::class, [
                                        'items' => IcmsHelper::map($pays, 'id', 'name'),
                                        'itemsOptions' => IcmsHelper::modelAttributesToData($pays, 'id', ['id']),
                                    ])->label(false)
                                    ?>
                                    <?php foreach ($pays as $pay) { ?>
                                        <div id="basket-pay-desctiption-<?= $pay->id ?>" class="basket-pay-desctiption" style="<?= $order->catalog_pay_id != $pay->id ? 'display: none' : '' ?>">
                                            <?= $pay->content ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="basket-form-user-wrapper">
                            <div class="basket-form-title">
                            <img src="<?= AppAsset::path('images/profile-icon.svg') ?>">
                                <span>Покупатель</span>
                            </div>
                            <div class="basket-form-user">
                                
                            <?=
                                $form->field($order, 'user_name')->textInput([
                                    'placeholder' => 'Обращение',
                                    'class' => 'form-control',
                                    'value' => !Yii::$app->user->isGuest ? Yii::$app->user->identity->name : ''
                                ])->label('Ваше имя');
                                ?>
                            
                                <?=
                                $form->field($order, 'user_email')->textInput([
                                    'placeholder' => 'Email',
                                    'class' => 'form-control',
                                    'value' => !Yii::$app->user->isGuest ? Yii::$app->user->identity->email : ''
                                ])->label('E-mail');
                                ?>
                            
                                <?=
                                $form->field($order, 'user_phone')->textInput([
                                    'placeholder' => 'Телефон',
                                    'class' => 'form-control',
                                    'value' => !Yii::$app->user->isGuest ? Yii::$app->user->identity->phone : ''
                                ])->label('Телефон');
                                ?>
                            
                                <?=
                                $form->field($order, 'comment')->textarea([
                                    'placeholder' => 'Комментарий',
                                    'class' => 'form-control'
                                ])->label('Комментарий к заказу');
                                ?>

                                <div id="basket-delivery-address" style="<?= $order->delivery->have_address != 1?'display: none':'' ?>">
                                    <?= $form->field($order, 'user_city')->textInput([
                                        'placeholder' => 'Город',
                                        'class' => 'form-control required',
                                        'value' => !Yii::$app->user->isGuest?Yii::$app->user->identity->city:''
                                    ])->label('Населенный пункт'); ?>
                            
                                    <?= $form->field($order, 'user_street')->textInput([
                                        'placeholder' => 'Улица/Шоссе',
                                        'class' => 'form-control',
                                        'value' => !Yii::$app->user->isGuest?Yii::$app->user->identity->street:''
                                    ])->label('Улица'); ?>
                                
                                    <?= $form->field($order, 'user_home')->textInput([
                                        'placeholder' => 'Дом',
                                        'class' => 'form-control',
                                        'value' => !Yii::$app->user->isGuest?Yii::$app->user->identity->home:''
                                    ])->label('Дом, корпус, квартира'); ?>
                                </div>
                                    
                            
                            <?php if (Yii::$app->user->isGuest === false) { ?>
                               
                                    <label class="checkbox text-left">
                                        <input name="save_user_info" type="checkbox" checked>
                                        <i></i>
                                        Сохранить для следующих заказов
                                    </label>
                               
                            <?php } ?>
                      
                            </div>
                        </div>
                        
                    </div>
                    <div class="basket-total">
                        <div class="basket-total-tab">
                            <span class="basket-total-title">Сумма</span>
                            <span id="basket-good-summ"><?= number_format($order->total_price - $order->delivery_price, 2, '.', ' ') ?></span>
                        </div>

                        <div class="basket-total-tab">
                            <span class="basket-total-title">Доставка</span>
                            <span id="basket-delivery-price"><?= $order->delivery_price != 0 ? number_format($order->delivery_price, 2, '.', ' ') : '- - - -' ?></span>
                        </div>

                        <div class="basket-total-tab">
                            <span class="basket-total-title">Итого</span>
                            <span id="basket-total-price"><?= number_format($order->total_price, 2, '.', ' ') ?></span>
                        </div>
                        
                        <button class="btn" type="submit">Оформить заказ</button>
                    </div>
                </div>

            
            
       

        <?php $form->end() ?>
    <?php } ?>
</div>

<?php  if (Yii::$app->session->getFlash('ORDER_SEND', false) === false) { ?>
    <div style="<?= count($goods) > 0 ? 'display: none' : '' ?>" id="basket-empty-message">
        <div class="divider-lg"></div>
        <div class="divider-lg"></div>
        <div class="text-center">
            <div class="big-icon">
                <span class="icon flaticon-shopping185"></span>
            </div>
            <h1>Ваша корзина пуста! <span class="sub-header">Добавьте один или несколько товаров в корзину из <a href="<?= \yii\helpers\Url::to(['site/catalog']) ?>">каталога</span></h1>
            <div class="divider-lg">
            </div>
            <a href="/">Вернуться на главную страницу </a>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-success fade in">
        <strong>Заказ оформлен!</strong> Обо всех изменениях статусов заказа Вам придёт уведомление на почтовый ящик, указанный при оформлении заказа
    </div>
<?php } ?>