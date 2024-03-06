jQuery(document).ready(function($){
    function paginationActionUpdate(){
        $('.shopPage__paginationButton').click(function(){
            const sort = $('.shopPage__list').attr('data-sort'),
                  total = parseInt($('.shopPage__paginationPage .total').html()),
                  varumarke = $('.shopPage__list').attr('data-varumarke'),
                  storek = $('.shopPage__list').attr('data-storek'),
                  taggar = $('.shopPage__list').attr('data-taggar'),
                  kategori = $('.shopPage__list').attr('data-kategori');
            let paged = parseInt($('.shopPage__list').attr('data-paged')),
                orderby, order,
                metaKey = '',
                separator;
    
            if($(this).hasClass('next')){
                paged = ++paged;
            }
            if($(this).hasClass('prev')){
                paged = paged - 1;
            }
    
            switch(sort){
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
                    action: 'products_pagination',
                    paged: paged,
                    order: order,
                    orderby: orderby,
                    metaKey: metaKey,
                    varumarke: varumarke,
                    storek: storek,
                    taggar: taggar,
                    kategori: kategori,
                },
                success: function success(result) {
                    $('.shopPage__list .products').html(result);
                }
            });
    
    
            $('.shopPage__list').attr('data-paged', paged);
            $('.shopPage__paginationPage .current').html(paged);
    
            //Link Change
            if(window.location['href'].split('?')[1] != undefined && window.location['href'].split('?')[1] != ''){
                separator = '&';
            } else{
                separator = '?';
            }
    
            if(window.location['href'].split('paged=')[1] != '' && window.location['href'].split('paged=')[1] != undefined) {
                if(window.location['href'].split('paged=')[1].split('&')[1] != '' && window.location['href'].split('paged=')[1].split('&')[1] != undefined){
                    window.history.pushState('', '', window.location['href'].split('paged=')[0] + `paged=${paged}` + '&' + window.location['href'].split('paged=')[1].split('&')[1]);
                } else{
                    window.history.pushState('', '', window.location['href'].split('paged=')[0] + `paged=${paged}`);
                }
            } else{
                window.history.pushState('', '', window.location + `${separator}paged=${paged}`);
            }
    
    
            if(paged == 1){
                $('.shopPage__paginationButton.prev').addClass('disabled');
            } else if(paged > 1 && $('.shopPage__paginationButton.prev').hasClass('disabled')){
                $('.shopPage__paginationButton.prev').removeClass('disabled');
            }
    
            if(paged == total){
                $('.shopPage__paginationButton.next').addClass('disabled');
            } else if(paged != total && $('.shopPage__paginationButton.next').hasClass('disabled')){
                $('.shopPage__paginationButton.next').removeClass('disabled');
            }
        });
    }
    paginationActionUpdate();
    $(document).ajaxSend(function(event, xhr, settings) {
        if(settings.data.includes('action')){
            const action = settings.data ? settings.data.split('action=')[1].split('&paged')[0] : '';
            if (action && (action === 'products_pagination' || action === 'products_filter' || action === 'products_sorting')) {
                $('.shopPage__list').addClass('loading');
            }
        }
    });
    $(document).ajaxComplete(function(){
        $('.shopPage__list').removeClass('loading');
    })



    $(document).ajaxComplete(function(event, xhr, settings){
        if(settings.data.includes('action')){
            const action = settings.data ? settings.data.split('action=')[1].split('&')[0] : '';
            if (action === 'products_filter') {
                paginationActionUpdate();
            }
        }
    })
})