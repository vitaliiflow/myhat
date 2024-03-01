jQuery(document).ready(function($){
    $('.shopPage__paginationButton').click(function(){
        const sort = $('.shopPage__list').attr('data-sort'),
              total = parseInt($('.shopPage__paginationPage .total').html());
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
                metaKey: metaKey
            },
            success: function success(result) {
                $('.shopPage__list').html(result);
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
    $(document).ajaxSend(function(event, xhr, settings) {
        const action = settings.data ? settings.data.split('action=')[1].split('&paged')[0] : '';
        if (action && action === 'products_pagination') {
            $('.shopPage__list').addClass('loading');
        }
    });
    $(document).ajaxComplete(function(){
        $('.shopPage__list').removeClass('loading');
    })
})
