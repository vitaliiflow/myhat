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
        });

        $('.wac-btn-sub').click(function(){
            const item = $(this).closest('.cart__item').find('.qty');

            if(parseInt(item.val()) == 0) {
                item.trigger( "submit" );
                $('.cart__hiddenContent button[name="update_cart"]').click();
                item.focus().submit();
            }
        })
    }


    $('.cart__couponToggler').click(function(){
        $(this).parent().find('.actions').stop().slideToggle();
    });
    cartActions();


    $(document).ajaxComplete(function(event, xhr, settings) {
        if(settings.data !== undefined){
            cartActions();
            if(settings.data.includes('update_cart')){
                $('.cart__couponToggler').click(function(){
                    $(this).parent().find('.actions').stop().slideToggle();
                });
            }
        }
    });


    $('.cartAdditionalProduicts__list').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        dots: true,
        arrows: false,
        responsive: [
          {
            breakpoint: 991,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1
            }
          },
          {
            breakpoint: 770,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1
            }
          }
        ]
    })
})

