jQuery(document).ready(function ($) {
    let seoText = $('.seo-text');
    let seoTextHeight = seoText.prop('scrollHeight');
    console.log(seoTextHeight);

    if (seoTextHeight > 300 && !$('body').hasClass('archive')) {
        seoText.addClass('seo-text__content--long');
    }

    if (seoTextHeight > Math.ceil(seoText.outerHeight()) && $('body').hasClass('archive')) {
        seoText.addClass('seo-text__content--long');
    }

    $(document).on('click', '.seo-text__opener', function() {

        $(seoText).toggleClass('seo-text__content--long-opened');
        
    });

});