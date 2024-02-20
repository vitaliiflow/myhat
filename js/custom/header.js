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
  $('.header__mobileMenu .menu-item-has-children .menu-item__parent a').click();
  $('.header--toggler').click(function(){
    $('.header__content').toggleClass('open');
  });
})