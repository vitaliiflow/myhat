jQuery(document).ready(function($){
    const w = $(window).width();
    $(window).on('load', function(){
        if(w >= 994){
            $('.shopPage__filtersRow__item.sort').hover(
                function(){
                    $('.shopPage__filtersRow__listWrapper').stop().slideToggle();
                },
                function(){
                    $('.shopPage__filtersRow__listWrapper').stop().slideToggle();
                }
            );
            $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__list__apply').click(function(){
                $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__listWrapper').slideUp();
            })
        } else{
            $('.shopPage__filtersRow__item .mobile-toggle').click(function(){
                $(this).closest('.shopPage__filtersRow__item').find('.shopPage__filtersRow__listWrapper').toggleClass('active');
            })
        }
        $('.shopPage__filtersRow__listItem').click(function(){
            $(this).parent().find('.shopPage__filtersRow__listItem').removeClass('active');
            $(this).addClass('active');
        });
    });
    $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__list__apply').click(function(){
        const sortType = $('.shopPage__filtersRow__item.sort .active .shopPage__filtersRow__listItem__name').attr('data-slug'),
              paged = $('.shopPage__list').attr('data-paged');
        let order, orderby, metaKey;
        switch(sortType){
            case 'popularity':
                orderby = 'popularity';
                order = 'ASC';
                break;
            case 'rating':
                orderby = 'meta_value_num';
                metaKey = '_wc_average_rating';
                order = 'ASC';
                break;
            case 'date':
                orderby = 'publish_date';
                order = 'DESC';
                break;
            case 'price':
                orderby = 'meta_value_num';
                metaKey = '_price';
                order = 'ASC';
                break;
            case 'price-desc':
                orderby = 'meta_value_num';
                metaKey = '_price';
                order = 'DESC';
                break;
        }
        $.ajax({
            url: codelibry.ajax_url,
            type: 'post',
            data: {
                action: 'products_sorting',
                paged: paged,
                order: order,
                orderby: orderby,
                metaKey: metaKey
            },
            success: function(response){
                $('.products').html(response);
            }
        });
        $('.shopPage__list').attr('data-order', order);
        $('.shopPage__list').attr('data-orderby', orderby);
        $('.shopPage__list').attr('data-metakey', metaKey);
    })

});