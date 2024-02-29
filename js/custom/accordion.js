jQuery(document).ready(function ($) {
    $('.accordion .js-accordion-button').click(toggleAccordionItem);

    function toggleAccordionItem(){
        $(this).find('.js-accordion-button-icon').toggleClass('active');

        const bodyId = $(this).data('for');
        const body = $(`.js-accordion-body[data-body="${bodyId}"]`);

        body.slideToggle();
    }
});