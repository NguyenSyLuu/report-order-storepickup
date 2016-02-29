define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'mage/url',
        'mage/translate',
        'mage/template',
        'jquery/ui'
    ],
    function ($, quote, url, $t, mageTemplate) {
        var liststoreJson = window.liststoreJson;

        function getSelectListStoreHtml() {
            var $wrapperelectHtml = $('<div class="list-store-to-pickup"><label>' + $t('Select Store:') + '</label></div>');
            var $selectStoreHtml = $('<select class="list-store-select disable-ul"></select>');
            $selectStoreHtml.append('<option class="show-tag-li store-item" value="-1">' + $t('Select a store to pickup') + '</option>');
            $.each(liststoreJson, function (index, el) {
                $selectStoreHtml.append('<option class="show-tag-li store-item" value="' + el.storepickup_id + '">' + el.store_name + '</option>');
            });


            $wrapperelectHtml.append($selectStoreHtml);

            return $wrapperelectHtml;
        }
        quote.paymentMethod.subscribe(function () {
            if (quote.shippingMethod().carrier_code == 'storepickup' && isDisplayPickuptime){
                var mapImage= '<br/><img src="http://maps.google.com/maps/api/staticmap?center=' + storePikcuplatitude + ',' + storePikcuplongitude + '&zoom=15&size=200x200&markers=color:red|label:S|' + storePikcuplatitude + ',' + storePikcuplongitude + '&sensor=false" />';
                $('.payment-method-billing-address').html("Pickup at Store: <br/>"+$('.info-store-checkout').html());
                $('.ship-to .shipping-information-content').html($('.info-store-checkout').html());
                storePickupreview= '<div class="storePickupreview">'+'Pickup Date:'+$('#shipping_date').val()+"<br/>"+ 'Pickup Time:'+$('#shipping_time').val()+'</div>';
                if(!($('.storePickupreview').length>0))$('.ship-via .shipping-information-content').append(storePickupreview+mapImage);
            }
        }, this);

        quote.shippingMethod.subscribe(function (value) {
            var storePickupInformation = "<div class ='storepickup-information'></div>";
            if((!$('.storepickup-information').length > 0)) $('#checkout-shipping-method-load').append(storePickupInformation);
            if (quote.shippingMethod().carrier_code == 'storepickup') {
                $('#shipping-method-buttons-container').hide();
                if (!($('.list-store-to-pickup').length > 0)) {
                    $('.storepickup-information').append(getSelectListStoreHtml());
                    $('.storepickup-information').append(select_store_by_map);
                    $('#select_store_by_map').click(function () {
                        $('#popup-mpdal').modal('openModal');
                    });
                    $('.list-store-select').change(function () {

                        $('#shipping-method-buttons-container').hide();
                        if ($('#shipping_date_div').length > 0)
                        {
                            $('#shipping_date_div').show();
                            $('.date-ajax-loading-wait').show();
                            $('#shipping_date').hide();
                            $('#shipping_time_div').hide();
                        } else if(isDisplayPickuptime)
                        {
                            $('.storepickup-information').append(storepickup_date_box);
                            $("#shipping_date").change(function()
                            {
                                $('#shipping-method-buttons-container').hide();
                                $('.time-ajax-loading-wait').show();
                                showTimeBox($('#shipping_date').val(),$('.list-store-select').val());
                            });
                        }
                        $.each(liststoreJson, function (index, el) {
                            if (el.storepickup_id == $('.list-store-select').val()) {
                                storePikcuplatitude= el.latitude;
                                storePikcuplongitude=el.longitude;
                                var store_information = '<h3>' + el.store_name + '</h3><br/>' + '<p>' + $t('Store address: ') + el.address + '</p>'+'<p>' + $t('Store Phone: ') + el.phone + '</p>';
                                if ($('.info-store-checkout').length > 0) {
                                    $('.info-store-checkout').html(store_information);
                                } else {
                                    var info_store = '<div class ="info-store-checkout">' + '<h3>'+ el.store_name + '</h3><br/>' + '<p>' + $t('Store address: ') + el.address + '</p>'+'<p>' + $t('Store Phone: ') + el.phone + '</p>' + '</div>';
                                    $('#select_store_by_map').after(info_store);
                                }
                                $.ajax(
                                    {
                                        url: url.build("storepickup/checkout/changestore"),
                                        type: "post",
                                        dateType: "text",
                                        data: {
                                            store_id: $('.list-store-select').val(),
                                            store_name: el.store_name,
                                            store_address: el.address,
                                            latitude: el.latitude,
                                            longitude: el.longitude
                                        },
                                        success: function (result) {
                                            storePikcupsession = $('.list-store-select').val();
                                            if(isDisplayPickuptime) showInputDateBox(); else $('#shipping-method-buttons-container').show();
                                        }
                                    });
                            }
                        });

                    });
                    $('.list-store-select').val(defaultStore).trigger('change');

                } else {
                    $('.storepickup-information').show();
                    $('#select_store_by_map').show();
                    $('.list-store-to-pickup').show();
                }


            } else {
                $('#shipping-method-buttons-container').show();
                $('.storepickup-information').hide();
            }
        });



        function showInputDateBox(){

            $.ajax(
                {
                    url: url.build("storepickup/checkout/disabledate"),
                    type: "post",
                    dateType: "json",
                    data: {
                        store_id: $('.list-store-select').val()
                    },
                    success: function (result) {
                        result = $.parseJSON(result);
                        $("#shipping_date").val("");
                        $("#shipping_date").datepicker("destroy");
                        $("#shipping_date").datepicker(
                            {
                                minDate: -0,
                                dateFormat: 'mm/dd/yy',
                                beforeShowDay: function(day) {
                                    var formatdate = $.datepicker.formatDate('mm/dd/yy', day);
                                    return [ ($.inArray(formatdate,result.holiday) == -1)&&($.inArray(day.getDay(),result.schedule) == -1) ];
                                }
                            });
                        $('#shipping_date').show();
                        $('.date-ajax-loading-wait').hide();
                    }
                });
        }
        function showTimeBox(shipping_date_val,store_id_val)
        {
            if (!($('#shipping_time_div').length > 0))
            {
                $('.storepickup-information').append(storepickup_time_box);
                $("#shipping_time").change(function()
                {
                    if(($("#shipping_time").val()!='-1')&&($('#shipping_date_div').length > 0))
                    {
                        $('.save-ajax-loading-wait').show();
                        $.ajax(
                            {
                                url: url.build("storepickup/checkout/changetime"),
                                type: "post",
                                dateType: "json",
                                data: {
                                    shipping_time: $("#shipping_time").val()
                                },
                                success: function (result) {
                                    $('#shipping-method-buttons-container').show();
                                    $('.save-ajax-loading-wait').hide();
                                }
                            });
                    } else $('#shipping-method-buttons-container').hide();
                });
            }
            $('#shipping_time_div').show();
            $('#shipping_time').hide();

            $.ajax(
                {
                    url: url.build("storepickup/checkout/changedate"),
                    type: "post",
                    dateType: "json",
                    data: {
                        shipping_date: shipping_date_val,
                        store_id:store_id_val
                    },
                    success: function (result) {
                        result = $.parseJSON(result);
                        $('#shipping_time').html("");
                        var selecttime='<option value="-1">Select time to pickup</option>';
                        if(!result.error)
                        {
                            $('#shipping_time').append(selecttime+result.html);
                            $('#shipping_time').show();
                            $('.time-ajax-loading-wait').hide();
                        } else alert(result.error);

                    }
                });



        }
    });
