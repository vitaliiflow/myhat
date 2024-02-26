jQuery(document).ready(function($){
    $('.singleProduct__gallery').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
    });
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
        $('.attributes-picker-item').removeClass('active');
        element.addClass('active');
        $(`#${attr}`).prop('selectedIndex', $(`#${attr} option[value="${elementAttr}"]`).index());
        $(`#${attr} option[value="${elementAttr}"]`).change();
        if(attr == 'pa_storlek'){
            $('.singleProduct__sizeWrapper').find('.singleProduct__sizeTitle').html(element.html());
        }
    });
    $('.variations_form select').each(function(){
        $(this).find('option').each(function(){
            if(typeof $(this).attr('selected') !== 'undefined' && $(this).attr('selected') !== false){
                console.log(123);
                const listitem = $(`div[data-attribute-name="${$(this).parent().attr('id')}"] div[data-attribute="${$(this).attr('value')}"]`);
                listitem.addClass('active');
                if($(this).parent().attr('name') == 'attribute_pa_storlek'){
                    $('.singleProduct__sizeWrapper').find('.singleProduct__sizeTitle').html(listitem.html());
                }
            }
        })
    })
})