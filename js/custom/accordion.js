jQuery(document).ready(function ($) {
    $('.contact__accordionList__itemTitle').click(function(){
        const parent = $(this).parent();

        $('.contact__accordionList__item').not(parent).removeClass('opened');
        $('.contact__accordionList__itemContent').not(parent.find('.contact__accordionList__itemContent')).slideUp();
        
        parent.toggleClass('opened');
        parent.find('.contact__accordionList__itemContent').slideToggle();
    });
});