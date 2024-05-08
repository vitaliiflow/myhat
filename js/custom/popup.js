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

    $(".product-customizer__trigger").on("click", function(){
        $(".product-customizer__wrapper").toggleClass("active");
        if ($(this).text() === 'Hide Customizer') {
            $(this).text('Show Customizer');
        } else {
            $(this).text('Hide Customizer');
        }
    });
});