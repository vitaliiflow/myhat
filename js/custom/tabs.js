jQuery(document).ready(function($){
    if ($('.tabs').length) {

    
        var sectionId = window.location.hash;
    
        if (sectionId) {
            $('.tabs__nav').removeClass('active');
            $('.tabs__nav[href="' + sectionId + '"]').addClass('active');
            $('.tabs__item').removeClass('active');
            $('.tabs__item' + sectionId).addClass('active');
        }
    
        $('.tabs').each(function(){
            let block = $(this);
            block.find('.js-tab-nav').on('click', function ( e ) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var id = $(this).attr('href');


                block.find('.tabs__nav').removeClass('active');
                block.find(this).addClass('active');
                block.find('.tabs__item').each(function(){
                    $(this).fadeOut();
                })
                block.find('.tabs__item.active').removeClass('active').fadeOut(function(){
                    block.find('.tabs__item' + id).fadeIn().addClass('active');
                });
            });
        });
    
        
    
        $('.tabs__item').each(function () {
            var block = $(this);
            var header = block.find('.tabs__item__header');
            var content = block.find('.tabs__item__content');
    
            header.on('click', function () {
                //block.toggleClass('tabs__item--active');
                //content.slideToggle();
            });
        });
    }
});