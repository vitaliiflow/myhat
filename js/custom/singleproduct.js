jQuery(document).ready(function($){
        let sizeLabel;
        if($('html').attr('lang') == "sv-SE"){
            sizeLabel = 'pa_storlek';
        } else if($('html').attr('lang') == "nb-NO"){
            sizeLabel = 'pa_storrelse';
        }
        setTimeout(function(){
            if($(`.variations #${sizeLabel} option`).length > 0){
                let itemsContent = '', i = 0;
                $(`.variations #${sizeLabel} option`).each(function(){
                    if(i > 0){
                        const name = $(this).html();
                        const slug = $(this).attr('value');
                        itemsContent += `<div class="singleProduct__sizeList__item attributes-picker-item" data-attribute="${slug}">${name}</div>`;
                    }
                    i++;
                });
                $('.singleProduct__sizeList.attributes-picker-list').html(itemsContent);
            } else {
                $('.singleProduct__sizeWrapper').addClass('hide');
            }

            $('.singleProduct__sizeTitle').click(function(){
                $(this).parent().toggleClass('opened');
                $(this).parent().find('.singleProduct__sizeList').slideToggle();
            });
        
        
            $('.singleProduct__accordionItem__title').click(function(){
                $(this).toggleClass('opened');
                $(this).parent().find('.singleProduct__accordionItem__content').slideToggle();
            });


            $('.attributes-picker-item').click(function(){
                const element = $(this),
                      elementAttr = element.attr('data-attribute'),
                      attr = element.closest('.attributes-picker-list').attr('data-attribute-name');
                $(this).closest('.attributes-picker-list').find('.attributes-picker-item').removeClass('active');
                element.addClass('active');
                $(`#${attr}`).prop('selectedIndex', $(`#${attr} option[value="${elementAttr}"]`).index());
                $(`#${attr} option[value="${elementAttr}"]`).change();
                if(attr == sizeLabel){
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
                        if($(this).parent().attr('name') == `attribute_${sizeLabel}`){
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
        }, 300);

    

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



})
