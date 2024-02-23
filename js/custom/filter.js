jQuery(document).ready(function($){
    const w = $(window).width();
    $(window).on('load', function(){
        if(w > 993){
            $('.shopPage__filtersRow__item.sort').hover(
                function(){
                    $('.shopPage__filtersRow__listWrapper').stop().slideToggle();
                },
                function(){
                    $('.shopPage__filtersRow__listWrapper').stop().slideToggle();
                }
            )
        }
        $('.shopPage__filtersRow__listItem').click(function(){
            $(this).parent().find('.shopPage__filtersRow__listItem').removeClass('active');
            $(this).addClass('active');
        });
    });
    $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__list__apply').click(function(){
        const sortType = $('.shopPage__filtersRow__item.sort .active .shopPage__filtersRow__listItem__name').attr('data-slug');
        $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__listWrapper').slideUp();
        console.log(sortType);
        $.ajax({
            url: codelibry.ajax_url,
            type: 'post',
            data: {
                action: 'products_sorting',
                sortType: sortType,
            },
            success: function(response){
                $('.products').html(response);
            }
        });
    })
});