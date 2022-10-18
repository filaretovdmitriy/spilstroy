<?php
/* @var $this app\components\View */

use yii\helpers\Html;
use app\components\IcmsHelper;
use yii\widgets\ActiveForm;
use app\widgets\RadioList;
?>
<div class="container">
    <div class="subtitle">
        <div>
            <span><?= $this->h1 ?></span>
        </div>
    </div>
    <div id="baskert-wraper">
        <?php if (count($goods) > 0) { ?>
            <?php
            $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'shopping-cart'
                        ]
            ]);
            ?>

            <div class="basket-wrapper">
                
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
                        <div class="button-change-count">-</div>
                        <input type='text' class="form-control input-quantity update-order-count" data-id="<?= $orderGoodId ?>" value="<?= $good->quant ?>" id='quantity-field-<?= $orderGoodId ?>'/>
                        <div class="button-change-count">+</div>
                    </div>
                    <div id="product-summ-<?= $orderGoodId ?>" class="basket-item-sum"><?= number_format($good->summ, 2, '.', ' ') ?></div>
                    <div class="basket-item-delete"><button type="button" class="close basket-delete-good" data-id="<?= $orderGoodId ?>"><span class="sr-only">Удалить</span></button></div>
                </div>
                    <?}?>
                
                    
            </div>

            <div class="rect-nohover">
                <div class="inside">
                    <table class="table-shop" id="basket-table">
                        <thead>
                            <tr>
                                <th colspan="3">Товар</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>Стоимость</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($goods as $orderGoodId => $good) { ?>
                                <tr id="order-good-<?= $orderGoodId ?>">
                                    <td class="product-in-table image-table center">
                                        <?php
                                        if (!empty($good->image)) {
                                            echo Html::a(
                                                    Html::img(IcmsHelper::getResizePath($good->image, 170, 170), ['class' => 'img-responsive', 'alt' => $good->name]), $good->url
                                            );
                                        }
                                        ?>
                                    </td>
                                    <td class="product-in-table">
                                        <h3><a href="<?= $good->url ?>"><?= $good->name ?></a></h3>
                                        <?php if (!empty($good->article)) { ?>
                                            <span><?= $good->article ?></span>
                                        <?php } ?>
                                    </td>
                                    <td class="product-in-table-sku">
                                        <?php foreach ($good->sku as $skuId => $skuProps) { ?>
                                            <?= $skuProps['name'] ?>: <?= $skuProps['value'] ?><br>
                                        <?php } ?>
                                    </td>
                                    <td id="product-price-<?= $orderGoodId ?>"><?= number_format($good->price, 2, '.', ' ') ?></td>
                                    <td class="center">
                                        <input type='text' class="form-control input-quantity update-order-count" data-id="<?= $orderGoodId ?>" value="<?= $good->quant ?>" id='quantity-field-<?= $orderGoodId ?>'/>
                                    </td>
                                    <td id="product-summ-<?= $orderGoodId ?>" class="shop-red"><?= number_format($good->summ, 2, '.', ' ') ?></td>
                                    <td class="center">
                                        <button type="button" class="close basket-delete-good" data-id="<?= $orderGoodId ?>"><span>&times;</span><span class="sr-only">Удалить</span></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="divider divider-sm">
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="rect-nohover">
                        <h3>Способ получения товара</h3>
                        <div class="col col-sm-12 col-md-6" id="basket-delivery-selector">
                            <?=
                            $form->field($order, 'catalog_delivery_id')->widget(RadioList::class, [
                                'items' => IcmsHelper::map($deliverys, 'id', 'name'),
                                'itemsOptions' => IcmsHelper::modelAttributesToData($deliverys, 'id', ['have_address']),
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col col-sm-12 col-md-6">
                            <?php foreach ($deliverys as $delivery) { ?>
                                <div id="basket-delivery-desctiption-<?= $delivery->id ?>" class="basket-delivery-desctiption" style="<?= $order->catalog_delivery_id != $delivery->id ? 'display: none' : '' ?>">
                                    <?= $delivery->content ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col col-md-12" id="basket-delivery-address" style="<?= $order->delivery->have_address != 1?'display: none':'' ?>">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?= $form->field($order, 'user_city')->textInput([
                                        'placeholder' => 'Город',
                                        'class' => 'form-control required',
                                        'value' => !Yii::$app->user->isGuest?Yii::$app->user->identity->city:''
                                    ])->label(false) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($order, 'user_street')->textInput([
                                        'placeholder' => 'Улица/Шоссе',
                                        'class' => 'form-control',
                                        'value' => !Yii::$app->user->isGuest?Yii::$app->user->identity->street:''
                                    ])->label(false) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= $form->field($order, 'user_home')->textInput([
                                        'placeholder' => 'Дом',
                                        'class' => 'form-control',
                                        'value' => !Yii::$app->user->isGuest?Yii::$app->user->identity->home:''
                                    ])->label(false) ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 ">
                    <div class="rect-nohover">
                        <h3>Способ оплаты</h3>
                        <div class="col col-sm-12 col-md-6" id="basket-pay-selector">
                            <?=
                            $form->field($order, 'catalog_pay_id')->widget(RadioList::class, [
                                'items' => IcmsHelper::map($pays, 'id', 'name'),
                                'itemsOptions' => IcmsHelper::modelAttributesToData($pays, 'id', ['id']),
                            ])->label(false)
                            ?>
                        </div>
                        <div class="col col-sm-12 col-md-6">
                            <?php foreach ($pays as $pay) { ?>
                                <div id="basket-pay-desctiption-<?= $pay->id ?>" class="basket-pay-desctiption" style="<?= $order->catalog_pay_id != $pay->id ? 'display: none' : '' ?>">
                                    <?= $pay->content ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-8">
                    <div class="rect-nohover">
                        <h3>Контактная информация</h3>
                        <div class="row">
                            <div class="col-sm-12">
                                <?=
                                $form->field($order, 'user_name')->textInput([
                                    'placeholder' => 'Обращение',
                                    'class' => 'form-control',
                                    'value' => !Yii::$app->user->isGuest ? Yii::$app->user->identity->name : ''
                                ])->label(false)
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?=
                                $form->field($order, 'user_email')->textInput([
                                    'placeholder' => 'Email',
                                    'class' => 'form-control',
                                    'value' => !Yii::$app->user->isGuest ? Yii::$app->user->identity->email : ''
                                ])->label(false)
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?=
                                $form->field($order, 'user_phone')->textInput([
                                    'placeholder' => 'Телефон',
                                    'class' => 'form-control',
                                    'value' => !Yii::$app->user->isGuest ? Yii::$app->user->identity->phone : ''
                                ])->label(false)
                                ?>
                            </div>
                            <div class="col-sm-12">
                                <?=
                                $form->field($order, 'comment')->textarea([
                                    'placeholder' => 'Комментарий',
                                    'class' => 'form-control'
                                ])->label(false)
                                ?>
                            </div>
                            <?php if (Yii::$app->user->isGuest === false) { ?>
                                <div class="col-sm-12">
                                    <label class="checkbox text-left">
                                        <input name="save_user_info" type="checkbox" checked>
                                        <i></i>
                                        Сохранить для следующих заказов
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 ">
                    <div class="rect-nohover">
                        <table class="table table-total">
                            <tr>
                                <th class="text-right">
                                    Сумма
                                </th>
                                <th class="td-divider">
                                </th>
                                <th>
                                    <span id="basket-good-summ"><?= number_format($order->total_price - $order->delivery_price, 2, '.', ' ') ?></span>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-right">
                                    Доставка
                                </th>
                                <th class="td-divider">
                                </th>
                                <th>
                                    <span class="text-right" id="basket-delivery-price"><?= $order->delivery_price != 0 ? number_format($order->delivery_price, 2, '.', ' ') : '- - - -' ?></span>
                                </th>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    <h2>Итого</h2>
                                </td>
                                <th class="td-divider">
                                </th>
                                <td>
                                    <h2><span id="basket-total-price"><?= number_format($order->total_price, 2, '.', ' ') ?></span></h2>
                                </td>
                            </tr>
                        </table>
                        <div class="text-center">
                            <button class="btn btn-cool btn-md invert-color" type="submit">Оформить заказ</button>
                            <div class="divider divider-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $form->end() ?>
    <?php } ?>
</div>

<?php if (Yii::$app->session->getFlash('ORDER_SEND', false) === false) { ?>
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
            <a href="/" class="btn btn-cool btn-lg">Вернуться на главную страницу </a>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-success fade in">
        <strong>Заказ оформлен!</strong> Обо всех изменениях статусов заказа Вам придёт уведомление на почтовый ящик, указанный при оформлении заказа
    </div>
<?php } ?>