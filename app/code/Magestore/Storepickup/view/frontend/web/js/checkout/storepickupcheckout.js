define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'mage/url',
        'mage/translate',
        'mage/template'
    ],
    function ($, quote, url, $t, mageTemplate) {
        var liststoreJson = window.liststoreJson;

        function getSelectListStoreHtml() {
            var $wrapperelectHtml = $('<div class="list-store"><label>' + $t('Select Store:') + '</label></div>');
            var $selectStoreHtml = $('<select onchange="" class="list-store-container disable-ul"></select>');
            $selectStoreHtml.append('<option class="show-tag-li store-item" value="0">' + $t('Select a store to pickup') + '</option>');
            $.each(liststoreJson, function (index, el) {
                $selectStoreHtml.append('<option class="show-tag-li store-item" value="' + el.storepickup_id + '">' + el.store_name + '</option>');
            });


            $wrapperelectHtml.append($selectStoreHtml);

            return $wrapperelectHtml;
        }

        quote.shippingMethod.subscribe(function (value) {
            if (quote.shippingMethod().carrier_code == 'storepickup') {
                if ($('#shipping_date_div').attr('hasappendStorePickup') != 'yes') {
                    $('#checkout-shipping-method-load').append(getSelectListStoreHtml());
                    $('#checkout-shipping-method-load').append(select_store_by_map);
                    $('#checkout-shipping-method-load').append(shipping_date_div);
                    $('#select_store_by_map').click(function () {
                        $('#popup-mpdal').modal('openModal');
                    });

                } else {
                    if ($('#shipping_date_div') && isDisplayPickuptime) $('#shipping_date_div').show();
                    if ($('#select_store_by_map')) $('#select_store_by_map').show();
                    if ($('.list-store')) $('.list-store').show();
                }
                require(['jquery', 'mage/calendar'], function ($) {
                    $('#shipping_date').calendar(calendar_pickup);
                });
                $('#shipping_date_div').attr('hasappendStorePickup', 'yes');
                $('.list-store-container').change(function () {
                    $.each(liststoreJson, function (index, el) {
                        if (el.storepickup_id == $('.list-store-container').val()) {
                            var $store_information = $('<label class= "title-store">' + $t('Store name: ') + el.store_name + '</label><br/>' + '<p>' + $t('Store address: ') + el.address + '</p>');
                            if ($('.info-store-checkout').length > 0) {
                                $('.info-store-checkout').html($store_information);
                            } else {
                                var $info_store = $('<div class ="info-store-checkout">' + '<label class= "title-store">' + $t('Store name: ') + el.store_name + '</label><br/>' + '<p>' + $t('Store address: ') + el.address + '</p>' + '</div>');
                                $('#select_store_by_map').after($info_store);
                            }
                            $.ajax(
                                {
                                    url: url.build("storepickup/checkout/changestore"),
                                    type: "post",
                                    dateType: "text",
                                    data: {
                                        store_id: $('.list-store-container').val(),
                                        store_name: el.store_name,
                                        store_address: el.address
                                    },
                                    success: function (result) {
                                        window.console.log(result);
                                    }
                                });
                            $.ajax(
                                {
                                    url: url.build("storepickup/checkout/disabledate"),
                                    type: "post",
                                    dateType: "text",
                                    data: {
                                        store_id: $('.list-store-container').val()
                                    },
                                    success: function (result) {
                                        window.console.log(result);
                                    }
                                });
                        }
                    });

                });

            } else {
                $('#shipping_date_div').hide();
                $('#select_store_by_map').hide();
                $('.list-store').hide();
                if ($('.info-store-checkout').length > 0)  $('.info-store-checkout').hide();

            }
        });
    });
