jQuery(document).ready(function ($) {
    let seoText = $('.seo-text');
    let seoTextHeight = seoText.height();
    console.log(seoTextHeight);

    if (seoTextHeight > 300) {
        seoText.addClass('seo-text__content--long');
    }


    $(document).on('click', '.seo-text__opener', function() {

        $(seoText).toggleClass('seo-text__content--long-opened');
        
    });

});