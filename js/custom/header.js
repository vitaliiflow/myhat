jQuery(document).ready(function($){
  $(".header__menu .menu-item-has-children").not('.header__mobileMenu .menu-item-has-children').hover(
    function () {
        $(this).children('.sub-menu').stop().slideDown();
    }, 
    function () {
        $(this).children('.sub-menu').stop().slideUp();
    }
  );
  $('.header__mobileMenu .menu-item-has-children .menu-item__parent a').click(function(e){
    e.preventDefault();
    $(this).closest('.menu-item-has-children').toggleClass('closed');
    $(this).closest('.menu-item-has-children').children('.sub-menu').stop().slideToggle();
  });

  $('.header__mobileMenu .menu-item-has-children').addClass('closed');
  $('.header__mobileMenu .menu-item-has-children .sub-menu').slideUp();

  $('.header--toggler').click(function(){
    $('.header__content').toggleClass('open');
  });
  $( window ).on( "resize", function() {
    if($(window).width() < 994){
      $('.header__mobileMenu .menu-item-has-children').addClass('closed');
      $('.header__mobileMenu .menu-item-has-children .sub-menu').slideUp();
    }
  });

  const w = $(window).width();
  if(w < 992){
    $('.search-toggle').click(function(){
      $(this).parent().addClass('visible');
    });
    $('body').click(function(e){
      if(!$(e.target).closest('.header__iconsList__item.search').length) {
        $('.header__iconsList__item.search').removeClass('visible');
      }
    })
  }
})