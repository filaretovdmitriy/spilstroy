/**
 * Форматирует число по заданному формату
 * @param {Numeric} number число для форматирования
 * @param {Integer} decimals количество цифер после запятой
 * @param {String} decPoint разделитель дроброй части
 * @param {String} thousandsSep разделитель целой части
 * @returns {String} форматированное число
 */
function number_format(number, decimals, decPoint, thousandsSep) {
    decimals = decimals || 0;
    number = parseFloat(number);

    if (!decPoint || !thousandsSep) {
        decPoint = '.';
        thousandsSep = ',';
    }

    var roundedNumber = Math.round(Math.abs(number) * ('1e' + decimals)) + '';
    var numbersString = decimals ? roundedNumber.slice(0, decimals * -1) : roundedNumber;
    var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
    var formattedNumber = "";

    while (numbersString.length > 3) {
        formattedNumber += thousandsSep + numbersString.slice(-3);
        numbersString = numbersString.slice(0, -3);
    }
    
    return (number < 0 ? '-' : '') + numbersString + formattedNumber + (decimalsString ? (decPoint + decimalsString) : '');
}

/**
 * Возвращает необходимую форму слова из массива forms в зависимости от count
 * @param {Integer} count количество
 * @param {Array} forms массив форм вида ['товар', 'товара', 'товаров']
 * @returns {String} форма слова из forms
 */
function plural_form(count, forms) {
    return count % 10 == 1 && count % 100 != 11 ? forms[0] : (count % 10 >= 2 && count % 10 <= 4 && (count % 100 < 10 || count % 100 >= 20) ? forms[1] : forms[2]);
}

/**
 * Проверяет выбнанны ли все параметры sku
 * @param {Boolean} showGrowl Показывать или нет сообщение с ошибкой
 * @returns {Boolean}
 */
function checkSkuProps(showGrowl) {
    if (showGrowl === undefined) {
        showGrowl = false;
    }

    flag = true;
    $('.product-prop').each(function () {
        elem = $(this);
        if (elem.find('input[type="radio"]:checked').length <= 0) {
            if (showGrowl) {
                $.growl({title: 'Внимание!', message: 'Пожалуйста, выберите "' + elem.prev().text() + '"'});
            }

            flag = false;
        }
    });
    return flag;
}

function updateOrdercount(id, count)
    {
        if (isNaN(count) || count < 1) {
            count = 1;
        }

        $.post('/basket/edit_good_count', {
            orderGoodId: id,
            newCount: count
        }, function (data) {
            if (data.success) {
                $('#product-price-' + id).html(number_format(data.good_price, 2, '.', ' '));
                $('#product-summ-' + id).html(number_format(data.good_summ, 2, '.', ' '));
                $('#basket-total-price').html(number_format(data.total_price, 2, '.', ' '));
                $('.mini-basket-count').html(data.total_count);
                $('.mini-basket-price').html(number_format(data.total_price, 0, '.', ' '));
                $('#basket-good-summ').html(number_format(data.total_price - data.delivery_price, 2, '.', ' '));
                if(data.total_count==0) $('.mini-basket').addClass('empty');
                $.growl({title: 'Корзина', message: 'Товар изменен'});
            }
        }, 'json');
    }

$(function($) {
    // *** BASKET ***

    $(".button-plus").click(function( event ) {
        event.preventDefault();
        val = parseInt($(this).prev().val())+1;
        $(this).prev().val(val);
        
        id = $(this).prev().data('id');
        count = parseInt($(this).prev().val());
        updateOrdercount(id, count);
        
    });

    $(".button-minus").click(function( event ) {
        event.preventDefault();
        val = parseInt($(this).next().val())-1;
        if(val<0) val=0;
        $(this).next().val(val);
        id = $(this).next().data('id');
        count = parseInt($(this).next().val());
        updateOrdercount(id, count);
    });

    $(".button-plus-item").click(function( event ) {
        event.preventDefault();
        val = parseInt($(this).prev().val())+1;
        $(this).prev().val(val);
    });

    $(".button-minus-item").click(function( event ) {
        event.preventDefault();
        val = parseInt($(this).next().val())-1;
        if(val<0) val=0;
        $(this).next().val(val);
    });

    $(document).on('click', '.show-filter-result', function () {
        $('#main-filter-form').submit();
    });
    
    /**
    * Удаление товара из заказа
    */
   $('#basket-table').on('click', '.basket-delete-good', function() {
       id = $(this).data('id');

       $.post('/ajax/basket/delete_good', {orderGoodId: id}, function(data) {
           if (data.success) {
               $('#order-good-' + id).remove();
               $('#basket-total-price').html(number_format(data.total_price, 2, '.', ' '));
               $('.mini-basket-count').html(data.total_count);
               $('.mini-basket-price').html(number_format(data.total_price, 0, '.', ' '));
               $('#basket-good-summ').html(number_format(data.total_price - data.delivery_price, 2, '.', ' '));
               if (data.total_count < 1) { 
                   $('#basket-empty-message').show();
                   $('#baskert-wraper').hide('fast', function(){
                       $(this).remove();
                   });
               }
               $.growl({title: 'Корзина', message: 'Товар удален'});
               data.delete_good = id;
           }
       }, 'json');
   });
   
   /**
    * Изменение выбранного SKU + поиск с сохранение SKU по выбранным свойствам
    */
   $(document).on('change', '.get-sku', function () {
        var elemId = $(this).data('catalog-id');
        var listSkuProps = {};
        $('.get-sku:checked').each(function () {
            elem = $(this);
            listSkuProps[elem.data('prop')] = elem.val();
        });
        if (checkSkuProps()) {
            //Получить ID SKU
            $.post('/ajax/basket/get_sku_id',
                    {props: listSkuProps, elemId: elemId}, function (data) {
                if (data.success) {
                    if (data.elemIdSku != 0) {
                        $('#product-sku-id-' + elemId).val(data.elemIdSku);
                    } else {
                        $('#product-sku-id-' + elemId).val('');
                    }
                }

            }, "json");
        }
    });
    
    /**
     * Добавление товара в корзину
     */
    $(document).on('click', '.basket-add-item', function () {
        var btn = $(this);
        var elemId = $(this).data('id');
        var elemSkuId = undefined;
        
        if ($('#product-sku-id-' + elemId).length > 0) {
            if ($('#product-sku-id-' + elemId).val() !== '') {
                elemSkuId = $('#product-sku-id-' + elemId).val();
            } else {
                $.growl({title: 'Корзина', message: 'Товара в данной комплектации нет'});
                return false;
            }
        }
        var elemQuant = $('#quantity-field-' + elemId).val();
        if (elemQuant <= 0) {
            elemQuant = 1;
        }

        if (!checkSkuProps(true)) {
            return false;
        }
        $.post('/basket/add_to_cart', {
            'elemId': elemId,
            'elemSkuId': elemSkuId,
            'elemQuant': elemQuant
        }, function (data) {
            if (data.success) {
                $('.mini-basket-count').html(data.total_count);
                $('.mini-basket-price').html(number_format(data.total_price, 0, '.', ' '));
                $('.mini-basket').removeClass('empty');
                $.growl({title: 'Корзина', message: 'Товар добавлен'});
            }
        }, 'json');
    });
    
    /**
     * Обновление количества товара в корзине
     */

    

    $(document).on('change', '.update-order-count', function () {
        id = $(this).data('id');
        count = parseInt($(this).val());
        updateOrdercount(id, count);
    });
    
    /**
     * Изменение способа доствки
     */
    $('#basket-delivery-selector').on('change', 'input[type="radio"]', function() {
        daliveryId = $(this).val();
        have_address = $(this).data('have_address');
        
        $.post('/ajax/basket/change_delivery', {deliveryId: daliveryId}, function(data) {
            if (data.success) {
                $('#basket-total-price').html(number_format(data.total_price, 2, '.', ' '));
                if (data.delivery_price > 0) {
                    $('#basket-delivery-price').html(number_format(data.delivery_price, 2, '.', ' '));
                } else {
                    $('#basket-delivery-price').html('- - - -');
                }
                $('#basket-good-summ').html(number_format(data.total_price - data.delivery_price, 2, '.', ' '));
                $('.mini-basket-price').html(number_format(data.total_price, 0, '.', ' '));
                $.growl({title: 'Корзина', message: 'Способ получения товара сохранен'});
            }
        }, 'json');
        
        $('.basket-delivery-desctiption').hide();
        $('#basket-delivery-desctiption-' + daliveryId).show();
        
        if (have_address == 1) {
            $('#basket-delivery-address').show();
        } else {
            $('#basket-delivery-address').hide();
        }
    });
    
    /**
     * Изменение способа оплаты
     */
    $('#basket-pay-selector').on('change', 'input[type="radio"]', function() {
        payId = $(this).val();
        
        $.post('/ajax/basket/change_pay', {payId: payId}, function(data) {
            if (data.success) {
                $.growl({title: 'Корзина', message: 'Способ оплаты сохранен'});
            }
        }, 'json');
        
        $('.basket-pay-desctiption').hide();
        $('#basket-pay-desctiption-' + payId).show();
        
    });
    
    // *** BASKET END ***
    
    // *** USER ACCOUNT ***
    
    $('.cancel-order').on('click', function() {
        var elem = $(this);
        var orderId = elem.data('id');
        if (!confirm('Вы действительно хотите отменить заказ №' + orderId + ' ?')) {
            return false;
        }
        
        $.post('/ajax/cancel_order', {orderId: orderId}, function(data) {
            if (data.success) {
                $('#order-' + orderId + ' .order-status').html(data.status);
                elem.remove();
                $.growl({title: 'Отмена заказа', message: 'Заказ успешно отменен'});
            }
        }, 'json');
    });
    
    // *** USER ACCOUNT END *** 
    
    // *** WISHLIST ***
    
    $('#wishlist-wraper').on('click', '.wishlist-delete-good', function() {
        id = $(this).data('id');
        
        $.post('/ajax/edit_washlist', {goodId: id, 'delete': true}, function(data) {
            if (data.success) {
                $('#wishlist-good-' + id).remove();
                if ($('#wishlist-table tbody tr').length === 0) {
                    $('#wishlist-wraper').remove();
                    $('#wishlist-empty-message').show();
                }
            }
        }, 'json');
    });
    
    $(document).on('click', '.wishlist-add-good', function() {
        elem = $(this);
        id = elem.data('id');
        
        $.post('/ajax/edit_washlist', {goodId: id}, function(data) {
            if (data.success) {
                if (data.delete) {
                    elem.find('.fa-heart').removeClass('fa-heart').addClass('fa-heart-o');
                    if (elem.find('.tooltips').length > 0) {
                        elem.find('.tooltips').attr('data-original-title', 'В список желаний');
                    } else {
                        elem.find('a').html('В список желаний');
                    }
                    $.growl({title: 'Список желаний', message: 'Товар удален'});
                } else {
                    if (data.delete !== undefined) {
                        elem.find('.fa-heart-o').removeClass('fa-heart-o').addClass('fa-heart');
                        if (elem.find('.tooltips').length > 0) {
                            elem.find('.tooltips').attr('data-original-title', 'Убрать из списка желаний');
                        } else {
                            elem.find('a').html('Убрать из списка желаний');
                        }
                        $.growl({title: 'Список желаний', message: 'Товар добавлен'});
                    }
                }
            }
        }, 'json');
        
        return false;
    });
    
    // *** WISHLIST END ***
});
