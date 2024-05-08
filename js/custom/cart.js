jQuery(document).ready(function($){

    function cartActions(){
        $('#shipping_method input:checked').parent().addClass('checked');

        $('.cart__itemRemove').click(function(){
            let item = $(this).closest('.cart__item');
            item.find('.qty').val(0);
            item.find('.qty').trigger( "submit" );
            $('.cart__hiddenContent button[name="update_cart"]').click();
            item.find('.qty').focus().submit();
        })

        $('#shipping_method > li').click(function(){
            $('#shipping_method > li').removeClass('checked');
            $(this).addClass('checked');
        })
    }


    $('.cart__couponToggler').click(function(){
        $(this).parent().find('.actions').stop().slideToggle();
    });
    cartActions();


    $(document).ajaxSend(function(event, xhr, settings) {
        console.log(settings.data);
        if(settings.data !== undefined){
            cartActions();
            console.log(settings.data);
            if(settings.data.includes('update_cart')){
                $(document).ajaxSend(function(event, xhr, settings) {
                    if(settings.data !== undefined){
                        if(settings.data.includes('time')){
                            $('.cart__couponToggler').click(function(){
                                $(this).parent().find('.actions').stop().slideToggle();
                            });
                        }
                    }
                });
            }
        }
    });



})

