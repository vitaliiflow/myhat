jQuery(document).ready(function($){
    const w = $(window).width();
    $(window).on('load', function(){
        if(w >= 994){
            $('.shopPage__filtersRow__item.sort').click(function(){
                $(this).find('.shopPage__filtersRow__listWrapper').stop().slideToggle();
            });
            $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__list__apply').click(function(){
                $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__listWrapper').slideUp();
            });


            $('.shopPage__filtersRow__listItem__title').click(function(){
                const item = $(this).parent(),
                      sublist = $(this).parent().find('.shopPage__filtersRow__listItem__sublist');
                $(this).closest('.shopPage__filtersRow__listWrapper').find('.shopPage__filtersRow__listItem__sublist').not(sublist).stop().slideUp();
                $(this).closest('.shopPage__filtersRow__listWrapper').find('.shopPage__filtersRow__listItem').not(item).removeClass('opened');

                item.toggleClass('opened');
                sublist.stop().slideToggle();
            });

            $('.shopPage__filtersRow__listItem__sublistItem').click(function(){
                $(this).toggleClass('active');
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
        let order, orderby, separator,
            metaKey = '';
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
        $('.shopPage__list').attr('data-sort', sortType);


        //Link Change
        if(window.location['href'].split('?')[1] != undefined && window.location['href'].split('?')[1] != ''){
            separator = '&';
        } else{
            separator = '?';
        }

        if(window.location['href'].split('orderby=')[1] != '' && window.location['href'].split('orderby=')[1] != undefined) {
            if(window.location['href'].split('orderby=')[1].split('&')[1] != '' && window.location['href'].split('orderby=')[1].split('&')[1] != undefined){
                window.history.pushState('', '', window.location['href'].split('orderby=')[0] + `orderby=${sortType}` + '&' + window.location['href'].split('orderby=')[1].split('&')[1]);
                console.log(123);
            } else{
                window.history.pushState('', '', window.location['href'].split('orderby=')[0] + `orderby=${sortType}`);
                console.log(312);
            }
        } else{
            window.history.pushState('', '', window.location + `${separator}orderby=${sortType}`);
        }
    })


    $('.shopPage__filtersRow__list__apply .btn').click(function(){
        let varumarke_list = [],
            storek_list = [],
            taggar_list = [],
            kategori_list = [],
            order = '',
            orderby = '',
            metaKey = '';

        const paged = $('.shopPage__list').attr('data-paged'),
              sortType = $('.shopPage__list').attr('data-sort');

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
        $('.shopPage__filtersRow__listItem__sublist').each(function(){
            const attrName = $(this).closest('.shopPage__filtersRow__listItem').attr('data-attr-name');
            $(this).find('.shopPage__filtersRow__listItem__sublistItem').each(function(){
                if($(this).hasClass('active')){
                    switch(attrName){
                        case 'varumarke': 
                            varumarke_list.push($(this).attr('data-slug'));
                            break;
                        case 'storek': 
                            storek_list.push($(this).attr('data-slug'));
                            break;
                        case 'taggar': 
                            taggar_list.push($(this).attr('data-slug'));
                            break;
                        case 'kategori': 
                            kategori_list.push($(this).attr('data-slug'));
                            break;
                    }
                }
            });
        });


        $.ajax({
            url: codelibry.ajax_url,
            type: 'post',
            data: {
                action: 'products_filter',
                paged: paged,
                order: order,
                orderby: orderby,
                metaKey: metaKey,
                varumarke: varumarke_list,
                storek: storek_list,
                taggar: taggar_list,
                kategori: kategori_list,
            },
            success: function(response){
                $('.shopPage__list').html(response);
            }
        });
        $('.shopPage__list').attr('data-varumarke', varumarke_list);
        $('.shopPage__list').attr('data-storek', storek_list);
        $('.shopPage__list').attr('data-taggar', taggar_list);
        $('.shopPage__list').attr('data-kategori', kategori_list);


    })
});