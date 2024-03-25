jQuery(document).ready(function ($) {
    $('.popup-block').each(function(){
        var id = $(this).attr('id');
        var closeBtn = $(this).find('.popup-block__close');
        var popup = $(this);
        $('[href="#'+id+'"]').click(function(e){
        e.preventDefault();
        popup.fadeIn();
        });
        closeBtn.click(function(e){
        e.preventDefault();
        popup.fadeOut();
        });

        // Click outside the popup
        popup.click(function() {
        popup.fadeOut();
        });
        
        popup.find('.popup-block__inner').click(function(event){
            event.stopPropagation();
        });

    });

    function checkWidthAndScroll() {
        var windowWidth = $(window).width();
        console.log(windowWidth);
        if (windowWidth < 768) {
          $('html, body').animate({scrollTop: $('header').offset().top}, 'slow');
          console.log('scrolled');
        }
      }

    $(".product-customizer__trigger").on("click", function(){
        $(".product-customizer__wrapper").toggleClass("active");
        checkWidthAndScroll();
        // $(".fpd-add-image").remove();
        // $("fpd-module-images").remove();
        if ($(this).text() === 'Gallery') {
            $(this).text('Customize');
        } else {
            $(this).text('Gallery');
        }
    });
    

    if ($('.fpd-product-designer-wrapper').length) {
        $('.product-customizer__trigger-wrapper').show();
    } else {
        $('.product-customizer__trigger-wrapper').hide();
    }
});