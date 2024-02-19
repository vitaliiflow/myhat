jQuery(document).ready(function($){
    $('.header__searchIcon').click(function(){
        $(this).parent().find('.search__barWrapper').toggleClass('opened');
    });
    $('.search__barWrapper input.search-input').bind("change paste keyup", function () {
        setTimeout(() => {
            var fieldData = $(this).val();
            $.ajax({
                url: codelibry.ajax_url,
                type: 'post',
                data: {
                    action: 'ajax_search',
                    fieldData: fieldData,
                },
                success: function (result) {
                    $('.search__resultsList').html(result);
                }
            });
        }, 1500);
        if ($(this).val().length === 0) {
            $('.search__resultsList__content').remove();
        }
    });
    $('.search__barWrapper input.search-input').on('focus', function () {
        $('.search__resultsList').addClass('show');
        $(this).focusout(function () {
            if (!$('.search__resultsList').is(":hover")) {
                $('.search__resultsList').removeClass('show');
            }
        });
    });
    $('.header__iconsList__item.search > img').click(function(){
        $(this).parent().find('.search__barWrapper').toggleClass('opened');
    })
    $(document).click(function(e){
        if(!$('.header__iconsList__item.search').is(e.target) && $('.header__iconsList__item.search').has(e.target).length === 0){
            $('.search__barWrapper').removeClass('opened');
        }
    })
})
    