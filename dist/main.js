"use strict";

jQuery(document).ready(function ($) {
  $(".header__menu .menu-item-has-children").not('.header__mobileMenu .menu-item-has-children').hover(function () {
    $(this).children('.sub-menu').stop().slideDown();
  }, function () {
    $(this).children('.sub-menu').stop().slideUp();
  });
  $('.header__mobileMenu .menu-item-has-children .menu-item__parent a').click(function (e) {
    e.preventDefault();
    $(this).closest('.menu-item-has-children').toggleClass('closed');
    $(this).closest('.menu-item-has-children').children('.sub-menu').stop().slideToggle();
  });
  $('.header__mobileMenu .menu-item-has-children .menu-item__parent a').click();
  $('.header--toggler').click(function () {
    $('.header__content').toggleClass('open');
  });
});
"use strict";

console.log("Hello world!");
"use strict";

jQuery(document).ready(function ($) {
  $('.header__searchIcon').click(function () {
    $(this).parent().find('.search__barWrapper').toggleClass('opened');
  });
  $('.search__barWrapper input.search-input').bind("change paste keyup", function () {
    var _this = this;
    setTimeout(function () {
      var fieldData = $(_this).val();
      $.ajax({
        url: codelibry.ajax_url,
        type: 'post',
        data: {
          action: 'ajax_search',
          fieldData: fieldData
        },
        success: function success(result) {
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
  $('.header__iconsList__item.search > img').click(function () {
    $(this).parent().find('.search__barWrapper').toggleClass('opened');
  });
  $(document).click(function (e) {
    if (!$('.header__iconsList__item.search').is(e.target) && $('.header__iconsList__item.search').has(e.target).length === 0) {
      $('.search__barWrapper').removeClass('opened');
    }
  });
});
"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
jQuery(document).ready(function ($) {
  $(".slider-full__list").slick({
    speed: 1000,
    infinite: true,
    arrows: false,
    // autoplay: true,
    // autoplaySpeed: 7500,
    dots: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    responsive: [{
      breakpoint: 768,
      settings: {
        adaptiveHeight: true
      }
    }]
  });
  $(".cards-list__list").each(function () {
    var _$$slick;
    $(this).slick((_$$slick = {
      dots: false,
      arrows: false,
      mobileFirst: true,
      speed: 1000,
      infinite: true
    }, _defineProperty(_$$slick, "arrows", false), _defineProperty(_$$slick, "slidesToShow", 1), _defineProperty(_$$slick, "slidesToScroll", 1), _defineProperty(_$$slick, "responsive", [{
      breakpoint: 565,
      settings: {
        speed: 1000,
        infinite: true,
        arrows: false,
        // autoplay: true,
        // autoplaySpeed: 7500,
        dots: false,
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }, {
      breakpoint: 991,
      settings: "unslick"
    }]), _$$slick));
  });
  $(".latest-products__list-slider").each(function () {
    var _$$slick2;
    $(this).slick((_$$slick2 = {
      dots: false,
      arrows: false,
      mobileFirst: true,
      speed: 1000,
      infinite: true
    }, _defineProperty(_$$slick2, "arrows", false), _defineProperty(_$$slick2, "slidesToShow", 2), _defineProperty(_$$slick2, "slidesToScroll", 1), _defineProperty(_$$slick2, "responsive", [{
      breakpoint: 565,
      settings: {
        speed: 1000,
        infinite: true,
        arrows: false,
        // autoplay: true,
        // autoplaySpeed: 7500,
        dots: false,
        slidesToShow: 3,
        slidesToScroll: 1
      }
    }, {
      breakpoint: 768,
      settings: {
        speed: 1000,
        infinite: true,
        arrows: false,
        // autoplay: true,
        // autoplaySpeed: 7500,
        dots: false,
        slidesToShow: 4,
        slidesToScroll: 1
      }
    }, {
      breakpoint: 991,
      settings: "unslick"
    }]), _$$slick2));
  });
  $(".product-cat--brand-slider").each(function () {
    var _$$slick3;
    $(this).slick((_$$slick3 = {
      dots: false,
      arrows: false,
      mobileFirst: true,
      speed: 1000,
      infinite: true
    }, _defineProperty(_$$slick3, "arrows", false), _defineProperty(_$$slick3, "slidesToShow", 1), _defineProperty(_$$slick3, "slidesToShow", 1), _defineProperty(_$$slick3, "centerMode", true), _defineProperty(_$$slick3, "variableWidth", true), _defineProperty(_$$slick3, "responsive", [{
      breakpoint: 991,
      settings: "unslick"
    }]), _$$slick3));
  });
  $(".tabs__labels-slider").each(function () {
    var _$$slick4;
    $(this).slick((_$$slick4 = {
      dots: false,
      arrows: false,
      mobileFirst: true,
      speed: 1000,
      infinite: true
    }, _defineProperty(_$$slick4, "arrows", false), _defineProperty(_$$slick4, "slidesToShow", 1), _defineProperty(_$$slick4, "slidesToShow", 1), _defineProperty(_$$slick4, "centerMode", true), _defineProperty(_$$slick4, "variableWidth", true), _defineProperty(_$$slick4, "responsive", [{
      breakpoint: 991,
      settings: "unslick"
    }]), _$$slick4));
  });
});
"use strict";

jQuery(document).ready(function ($) {
  $('.contact__accordionList__itemTitle').click(function () {
    var parent = $(this).parent();
    $('.contact__accordionList__item').not(parent).removeClass('opened');
    $('.contact__accordionList__itemContent').not(parent.find('.contact__accordionList__itemContent')).slideUp();
    parent.toggleClass('opened');
    parent.find('.contact__accordionList__itemContent').slideToggle();
  });
});
"use strict";

jQuery(document).ready(function ($) {
  if ($('.tabs').length) {
    console.log('tabs');
    var sectionId = window.location.hash;
    if (sectionId) {
      $('.tabs__nav').removeClass('active');
      $('.tabs__nav[href="' + sectionId + '"]').addClass('active');
      $('.tabs__item').removeClass('active');
      $('.tabs__item' + sectionId).addClass('active');
    }
    $('.tabs').each(function () {
      var block = $(this);
      block.find('.js-tab-nav').on('click', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var id = $(this).attr('href');
        console.log(id);
        block.find('.tabs__nav').removeClass('active');
        block.find(this).addClass('active');
        block.find('.tabs__item').each(function () {
          $(this).fadeOut();
        });
        block.find('.tabs__item.active').removeClass('active').fadeOut(function () {
          block.find('.tabs__item' + id).fadeIn().addClass('active');
          console.log('.tabs__item' + id);
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
"use strict";

jQuery(document).ready(function ($) {
  var w = $(window).width();
  $(window).on('load', function () {
    if (w > 993) {
      $('.shopPage__filtersRow__item.sort').hover(function () {
        $('.shopPage__filtersRow__listWrapper').stop().slideToggle();
      }, function () {
        $('.shopPage__filtersRow__listWrapper').stop().slideToggle();
      });
    } else {
      $('.shopPage__filtersRow__itemTitle').click(function () {
        $(this).parent().addClass('opened');
      });
    }
    $('.shopPage__filtersRow__listItem').click(function () {
      $(this).parent().find('.shopPage__filtersRow__listItem').removeClass('active');
      $(this).addClass('active');
    });
  });
  $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__list__apply').click(function () {
    var sortType = $('.shopPage__filtersRow__item.sort .active .shopPage__filtersRow__listItem__name').attr('data-slug');
    $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__listWrapper').slideUp();
    $.ajax({
      url: codelibry.ajax_url,
      type: 'post',
      data: {
        action: 'products_sorting',
        sortType: sortType
      },
      success: function success(response) {
        $('.products').html(response);
      }
    });
  });
});
"use strict";

jQuery(document).ready(function ($) {
  var seoText = $('.seo-text');
  var seoTextHeight = seoText.height();
  console.log(seoTextHeight);
  if (seoTextHeight > 300) {
    seoText.addClass('seo-text__content--long');
  }
  $(document).on('click', '.seo-text__opener', function () {
    $(seoText).toggleClass('seo-text__content--long-opened');
  });
});