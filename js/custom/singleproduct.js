jQuery(document).ready(function($){
    if($('body').hasClass('single-product')){
        $(window).on('load', function(){
            $('body').addClass('loaded');
        })
    }

    const w = $(window).width();

    if(w > 769){
        $('.singleProduct__accordionItem').first().find('.singleProduct__accordionItem__title').addClass('opened');
        $('.singleProduct__accordionItem').first().find('.singleProduct__accordionItem__content').slideDown();

    }


    //Product Slider
    $('.singleProduct__gallery .woocommerce-product-gallery__wrapper').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
    });
    $('.singleProduct__gallerySlider .woocommerce-product-gallery__wrapper').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots: true,
    })





    $('.singleProduct__gallery .woocommerce-product-gallery__wrapper').on('click', '.slick-slide', function(event){
        event.preventDefault();
        $('.singleProduct__gallerySlider .woocommerce-product-gallery__wrapper').slick('slickGoTo', $('.singleProduct__gallery .woocommerce-product-gallery__wrapper .slick-slide.slick-active').attr('data-slick-index'));
        $('.singleProduct__gallerySlider').addClass('active');
    });







    $('.singleProduct__gallerySlider__close, .singleProduct__galleryOverlay').click(function(){
        $(this).parent().removeClass('active');
    })

    $('.singleProduct__sizeTitle').click(function(){
        $(this).parent().toggleClass('opened');
        $(this).parent().find('.singleProduct__sizeList').slideToggle();
    });


    $('.singleProduct__accordionItem__title').click(function(){
        $(this).toggleClass('opened');
        $(this).parent().find('.singleProduct__accordionItem__content').slideToggle();
    });


    $('.quantity-btn').click(function(){
        const item = $(this).parent().find('.qty'),
              number = parseInt(item.val());
        if($(this).hasClass('increase')){
            item.val(parseInt(item.val()) + 1);
            if(parseInt(item.val()) == $(this).attr('max')){
                $('.increase').addClass('disabled');
            }
        }
        if($(this).hasClass('decrease')){
            item.val(parseInt(item.val()) - 1);
            if(parseInt(item.val()) <= 1){
                $('.decrease').addClass('disabled');
            }
        }

        if(parseInt(item.val()) > 1 && parseInt(item.val()) != $(this).attr('max')){
            $('.quantity-btn').removeClass('disabled');
        }
    });


    $('.attributes-picker-item').click(function(){
        const element = $(this),
              elementAttr = element.attr('data-attribute'),
              attr = element.closest('.attributes-picker-list').attr('data-attribute-name');
        $(this).closest('.attributes-picker-list').find('.attributes-picker-item').removeClass('active');
        element.addClass('active');
        $(`#${attr}`).prop('selectedIndex', $(`#${attr} option[value="${elementAttr}"]`).index());
        $(`#${attr} option[value="${elementAttr}"]`).change();
        if(attr == 'pa_storlek'){
            $('.singleProduct__sizeWrapper').find('.singleProduct__sizeTitle').html(element.html());
        }
    });
    $('.singleProduct__sizeList__item').click(function(){
        $('.singleProduct__sizeList').slideUp();
    });


    $('.variations_form select').each(function(){
        $(this).find('option').each(function(){
            if(typeof $(this).attr('selected') !== 'undefined' && $(this).attr('selected') !== false){
                const listitem = $(`div[data-attribute-name="${$(this).parent().attr('id')}"] div[data-attribute="${$(this).attr('value')}"]`);
                listitem.addClass('active');
                if($(this).parent().attr('name') == 'attribute_pa_storlek'){
                    $('.singleProduct__sizeWrapper').find('.singleProduct__sizeTitle').html(listitem.html());
                }
            }
        })
    });


    let imageUrl = $('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href');
    $('.attributes-picker-item').click(function(){
        setTimeout(function(){
            if($('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href') != imageUrl) {
                $('.woocommerce-product-gallery__image[data-slick-index="0"]').attr('data-thumb', $('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href'));
                $('.woocommerce-product-gallery__image[data-slick-index="0"] a').attr('href', $('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href'));
                $('.woocommerce-product-gallery__image[data-slick-index="0"] img').attr('srcset', $('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href'));

                imageUrl = $('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href');
            }
        }, 100)
    });



})
