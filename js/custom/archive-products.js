jQuery(document).ready(function($){
    $('.shopPage__paginationButton').click(function(){
        const order = $('.shopPage__list').attr('data-order'),
              orderby = $('.shopPage__list').attr('data-orderby'),
              metaKey = $('.shopPage__list').attr('data-metakey');
        let paged = parseInt($('.shopPage__list').attr('data-paged')),
            separator;

        if($(this).hasClass('next')){
            paged = ++paged;
        }
        if($(this).hasClass('prev')){
            paged = paged - 1;
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

        //Link Chnage
        if(window.location['href'].split('?')[1] != undefined && window.location['href'].split('?')[1] != ''){
            separator = '&';
        } else{
            separator = '?';
        }

        if(window.location.split('paged=')[1] != '' && window.location.split('paged=')[1] != undefined) {
            http://localhost:8888/myHat/butik/?paged=2&paged=3&paged=4
            if(window.location.split('paged=')[1].split('&')[1] != '' && window.location.split('paged=')[1].split('&')[1] != undefined){
                window.history.pushState('', '', window.location.split('paged=')[0] + `paged=${paged}` + window.location.split('paged=')[1].split('&')[1]);
            } else{
                window.history.pushState('', '', window.location.split('paged=')[0] + `${separator}paged=${paged}`);
            }
        } else{
            window.history.pushState('', '', window.location + `${separator}paged=${paged}`);
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
