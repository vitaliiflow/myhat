"use strict";

jQuery(document).ready(function ($) {
  $('.accordion .js-accordion-button').click(toggleAccordionItem);
  function toggleAccordionItem() {
    $(this).find('.js-accordion-button-icon').toggleClass('active');
    var bodyId = $(this).data('for');
    var body = $(".js-accordion-body[data-body=\"".concat(bodyId, "\"]"));
    body.slideToggle();
  }
});
"use strict";

jQuery(document).ready(function ($) {
  var w = $(window).width();
  $(window).on('load', function () {
    if (w >= 994) {
      $('.shopPage__filtersRow__item.sort').hover(function () {
        $('.shopPage__filtersRow__listWrapper').stop().slideToggle();
      }, function () {
        $('.shopPage__filtersRow__listWrapper').stop().slideToggle();
      });
      $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__list__apply').click(function () {
        $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__listWrapper').slideUp();
      });
    } else {
      $('.shopPage__filtersRow__item .mobile-toggle').click(function () {
        $(this).closest('.shopPage__filtersRow__item').find('.shopPage__filtersRow__listWrapper').toggleClass('active');
      });
    }
    $('.shopPage__filtersRow__listItem').click(function () {
      $(this).parent().find('.shopPage__filtersRow__listItem').removeClass('active');
      $(this).addClass('active');
    });
  });
  $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__list__apply').click(function () {
    var sortType = $('.shopPage__filtersRow__item.sort .active .shopPage__filtersRow__listItem__name').attr('data-slug'),
      paged = $('.shopPage__list').attr('data-paged');
    var order, orderby, metaKey;
    switch (sortType) {
      case 'popularity':
        orderby = 'popularity';
        order = 'ASC';
        break;
      case 'rating':
        orderby = 'meta_value_num';
        metaKey = '_wc_average_rating';
        order = 'ASC';
        break;
      case 'date':
        orderby = 'publish_date';
        order = 'DESC';
        break;
      case 'price':
        orderby = 'meta_value_num';
        metaKey = '_price';
        order = 'ASC';
        break;
      case 'price-desc':
        orderby = 'meta_value_num';
        metaKey = '_price';
        order = 'DESC';
        break;
    }
    $.ajax({
      url: codelibry.ajax_url,
      type: 'post',
      data: {
        action: 'products_sorting',
        paged: paged,
        order: order,
        orderby: orderby,
        metaKey: metaKey
      },
      success: function success(response) {
        $('.products').html(response);
      }
    });
    $('.shopPage__list').attr('data-order', order);
    $('.shopPage__list').attr('data-orderby', orderby);
    $('.shopPage__list').attr('data-metakey', metaKey);
  });
});
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
// console.log("Hello world!");
"use strict";
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

  /*search on brands-page*/
  $('#search-brands-form').on('submit', function (event) {
    event.preventDefault();
    var searchValue = $('#search-brands-form input[type="text"]').val();
    var brandsList = $('.brands-page__list');
    brandsList.html('<li class="searching-brands-text">' + codelibry.strings.searching + '</li>');
    $.ajax({
      url: codelibry.ajax_url,
      type: 'post',
      data: {
        action: 'search_brands',
        search_query: searchValue
      },
      success: function success(response) {
        brandsList.empty();
        if (response.success && response.data.length > 0) {
          response.data.forEach(function (brand) {
            var brandItem = $('<li>').addClass('col-4 col-sm-3 col-md-2 brands-page__item');
            var brandLink = $('<a>').attr('href', brand.url).addClass('brands-page__item-link');
            brandItem.append(brandLink);
            if (brand.logo) {
              var imgWrapper = $('<div>').addClass('img-wrapper d-flex');
              var image = $('<img>').attr('src', brand.logo).attr('alt', brand.name);
              imgWrapper.append(image);
              brandLink.append(imgWrapper);
            } else {
              brandLink.text(brand.name);
            }
            brandsList.append(brandItem);
          });
        } else {
          brandsList.html('<div>' + codelibry.strings.noBrandsFound + '</div>');
        }
      },
      error: function error(jqXHR, textStatus, errorThrown) {
        console.error('AJAX request failed:', textStatus, errorThrown);
        brandsList.html('<li>' + codelibry.strings.searchError + '</li>');
      }
    });
  });
  $('.clear-search-results-btn').hide();

  // Checking an input field for a value
  $('#search-brands-form input[type="text"]').on('input', function () {
    if ($(this).val().length > 0) {
      $('.clear-search-results-btn').show();
    } else {
      $('.clear-search-results-btn').hide();
    }
  });
  $('.clear-search-results-btn').on('click', function () {
    //trigger - fires an input event on the same text field.
    $('#search-brands-form input[type="text"]').val('').trigger('input');
    var brandsList = $('.brands-page__list');
    brandsList.html('<li class="searching-brands-text">' + codelibry.strings.searching + '</li>');
    $.ajax({
      url: codelibry.ajax_url,
      type: 'post',
      data: {
        action: 'search_brands',
        search_query: '' // An empty line means requesting all terms
      },

      success: function success(response) {
        brandsList.empty();
        if (response.success && response.data.length > 0) {
          response.data.forEach(function (brand) {
            var brandItem = $('<li>').addClass('col-4 col-sm-3 col-md-2 brands-page__item');
            var brandLink = $('<a>').attr('href', brand.url).addClass('brands-page__item-link');
            if (brand.logo) {
              var imgWrapper = $('<div>').addClass('img-wrapper d-flex');
              var image = $('<img>').attr('src', brand.logo).attr('alt', brand.name);
              imgWrapper.append(image);
              brandLink.append(imgWrapper);
            } else {
              brandLink.text(brand.name);
            }
            brandItem.append(brandLink);
            brandsList.append(brandItem);
          });
        } else {
          brandsList.html('<div>' + codelibry.strings.noBrandsFound + '</div>');
        }
      },
      error: function error(jqXHR, textStatus, errorThrown) {
        console.error('AJAX request failed:', textStatus, errorThrown);
        brandsList.html('<li>' + codelibry.strings.searchError + '</li>');
      }
    });
  });

  /*search on teams-page*/
  $('#search-teams-form').on('submit', function (event) {
    event.preventDefault();
    var searchValue = $('#search-teams-form input[type="text"]').val().trim();
    var teamsList = $('.tabs__item-list');
    var tabsContainer = $('.teams-page__tabs');
    var SearchResultContainer = $('.search-results');
    tabsContainer.hide();
    if (searchValue === '') {
      SearchResultContainer.hide();
      tabsContainer.show();
      tabsContainer.html('<li class="searching-brands-text">' + codelibry.strings.searching + '</li>');
      $.ajax({
        url: codelibry.ajax_url,
        type: 'post',
        data: {
          action: 'get_initial_teams_content'
        },
        success: function success(response) {
          if (response.success) {
            tabsContainer.html(response.data);
            initializeTabs();
          } else {
            tabsContainer.html('<div>' + codelibry.strings.resetError + '</div>');
          }
        },
        error: function error(jqXHR, textStatus, errorThrown) {
          tabsContainer.html('<div>' + codelibry.strings.ajaxError + '</div>');
          console.error('AJAX request failed:', textStatus, errorThrown);
        }
      });
    } else {
      tabsContainer.hide();
      SearchResultContainer.show();
      SearchResultContainer.html('<li class="searching-brands-text">' + codelibry.strings.searching + '</li>');
      $.ajax({
        url: codelibry.ajax_url,
        type: 'post',
        data: {
          action: 'search_teams',
          search_query: searchValue
        },
        success: function success(response) {
          SearchResultContainer.empty();
          // Checking if there is at least one object with a non-empty child_terms array
          var hasTeams = response.data.some(function (league) {
            return league.child_terms.length > 0;
          });
          if (response.success && hasTeams) {
            response.data.forEach(function (league) {
              if (league.child_terms.length > 0) {
                var leagueSection = $('<section class="mb-4">');
                var leagueName = $('<h6>').text(league.custom_name);
                var leagueList = $('<ul class="teams-page-result-list row">');
                league.child_terms.forEach(function (term) {
                  var termItem = $('<li class="col-4 col-md-3 col-lg-2 py-2 tabs__item-child-item">');
                  var termLink = $('<a class="d-block">').attr('href', term.url).text(term.name);
                  if (term.logo) {
                    // Term.logo is supposed to be a URL, if it is an ID it needs to be changed
                    var termImage = $('<img>').attr('src', term.logo).attr('alt', term.name);
                    termLink.prepend(termImage);
                  }
                  termItem.append(termLink);
                  leagueList.append(termItem);
                });
                leagueSection.append(leagueName).append(leagueList);
                SearchResultContainer.append(leagueSection);
              }
            });
          } else {
            SearchResultContainer.html('<div>' + codelibry.strings.noTeamsFound + '</div>');
          }
        },
        error: function error(jqXHR, textStatus, errorThrown) {
          SearchResultContainer.html('<div>' + codelibry.strings.ajaxError + '</div>');
          console.error('AJAX request failed:', textStatus, errorThrown);
        }
      });
    }
  });
  $('.clear-search-results-btn__teams').hide();

  // Checking an input field for a value
  $('#search-teams-form input[type="text"]').on('input', function () {
    if ($(this).val().length > 0) {
      $('.clear-search-results-btn__teams').show();
    } else {
      $('.clear-search-results-btn__teams').hide();
    }
  });
  $('.clear-search-results-btn__teams').on('click', function () {
    $('#search-teams-form input[type="text"]').val('').trigger('input');
    var tabsContainer = $('.teams-page__tabs');
    var SearchResultContainer = $('.search-results');
    SearchResultContainer.hide();
    tabsContainer.show();
    $.ajax({
      url: codelibry.ajax_url,
      type: 'post',
      data: {
        action: 'get_initial_teams_content'
      },
      success: function success(response) {
        if (response.success) {
          tabsContainer.html(response.data);
          initializeTabs();
        } else {
          tabsContainer.html('<div>' + codelibry.strings.resetError + '</div>');
        }
      },
      error: function error(jqXHR, textStatus, errorThrown) {
        tabsContainer.html('<div>' + codelibry.strings.ajaxError + '</div>');
        console.error('AJAX request failed:', textStatus, errorThrown);
      }
    });
  });
  function initializeTabs() {
    var tabsContainer = $('.tabs');
    if (tabsContainer.length) {
      console.log('tabs');
      tabsContainer.each(function () {
        var block = $(this);
        block.find('.js-tab-nav').off('click').on('click', function (e) {
          e.preventDefault();
          var id = $(this).attr('href');
          // console.log(id);
          block.find('.tabs__nav').removeClass('active');
          $(this).addClass('active');
          block.find('.tabs__item').removeClass('active').hide();
          $(id).fadeIn().addClass('active');
        });
      });
    }
  }
});
"use strict";

jQuery(document).ready(function ($) {
  //Product Slider
  $('.singleProduct__gallery .woocommerce-product-gallery__wrapper').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    dots: true
  });
  $('.singleProduct__sizeTitle').click(function () {
    $(this).parent().toggleClass('opened');
    $(this).parent().find('.singleProduct__sizeList').slideToggle();
  });
  $('.singleProduct__accordionItem__title').click(function () {
    $(this).toggleClass('opened');
    $(this).parent().find('.singleProduct__accordionItem__content').slideToggle();
  });
  $('.quantity-btn').click(function () {
    var item = $(this).parent().find('.qty'),
      number = parseInt(item.val());
    if ($(this).hasClass('increase')) {
      item.val(parseInt(item.val()) + 1);
      if (parseInt(item.val()) == $(this).attr('max')) {
        $('.increase').addClass('disabled');
      }
    }
    if ($(this).hasClass('decrease')) {
      item.val(parseInt(item.val()) - 1);
      if (parseInt(item.val()) <= 1) {
        $('.decrease').addClass('disabled');
      }
    }
    if (parseInt(item.val()) > 1 && parseInt(item.val()) != $(this).attr('max')) {
      $('.quantity-btn').removeClass('disabled');
    }
  });
  $('.attributes-picker-item').click(function () {
    var element = $(this),
      elementAttr = element.attr('data-attribute'),
      attr = element.closest('.attributes-picker-list').attr('data-attribute-name');
    $(this).closest('.attributes-picker-list').find('.attributes-picker-item').removeClass('active');
    element.addClass('active');
    $("#".concat(attr)).prop('selectedIndex', $("#".concat(attr, " option[value=\"").concat(elementAttr, "\"]")).index());
    $("#".concat(attr, " option[value=\"").concat(elementAttr, "\"]")).change();
    if (attr == 'pa_storlek') {
      $('.singleProduct__sizeWrapper').find('.singleProduct__sizeTitle').html(element.html());
    }
  });
  $('.singleProduct__sizeList__item').click(function () {
    $('.singleProduct__sizeList').slideUp();
  });
  $('.variations_form select').each(function () {
    $(this).find('option').each(function () {
      if (typeof $(this).attr('selected') !== 'undefined' && $(this).attr('selected') !== false) {
        var listitem = $("div[data-attribute-name=\"".concat($(this).parent().attr('id'), "\"] div[data-attribute=\"").concat($(this).attr('value'), "\"]"));
        listitem.addClass('active');
        if ($(this).parent().attr('name') == 'attribute_pa_storlek') {
          $('.singleProduct__sizeWrapper').find('.singleProduct__sizeTitle').html(listitem.html());
        }
      }
    });
  });
  var imageUrl = $('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href');
  $('.attributes-picker-item').click(function () {
    setTimeout(function () {
      if ($('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href') != imageUrl) {
        $('.woocommerce-product-gallery__image[data-slick-index="0"]').attr('data-thumb', $('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href'));
        $('.woocommerce-product-gallery__image[data-slick-index="0"] a').attr('href', $('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href'));
        $('.woocommerce-product-gallery__image[data-slick-index="0"] img').attr('srcset', $('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href'));
        imageUrl = $('.woocommerce-product-gallery__image[data-slick-index="-1"] a').attr('href');
      }
    }, 100);
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
  $('.shopPage__paginationButton').click(function () {
    var sort = $('.shopPage__list').attr('data-sort'),
      total = parseInt($('.shopPage__paginationPage .total').html());
    var paged = parseInt($('.shopPage__list').attr('data-paged')),
      orderby,
      order,
      metaKey = '',
      separator;
    if ($(this).hasClass('next')) {
      paged = ++paged;
    }
    if ($(this).hasClass('prev')) {
      paged = paged - 1;
    }
    switch (sort) {
      case 'popularity':
        orderby = 'popularity';
        order = 'ASC';
        break;
      case 'rating':
        orderby = 'meta_value_num';
        metaKey = '_wc_average_rating';
        order = 'ASC';
        break;
      case 'date':
        orderby = 'publish_date';
        order = 'DESC';
        break;
      case 'price':
        orderby = 'meta_value_num';
        metaKey = '_price';
        order = 'ASC';
        break;
      case 'price-desc':
        orderby = 'meta_value_num';
        metaKey = '_price';
        order = 'DESC';
        break;
    }
    $.ajax({
      url: codelibry.ajax_url,
      type: 'post',
      data: {
        action: 'products_pagination',
        paged: paged,
        order: order,
        orderby: orderby,
        metaKey: metaKey
      },
      success: function success(result) {
        $('.shopPage__list').html(result);
      }
    });
    $('.shopPage__list').attr('data-paged', paged);
    $('.shopPage__paginationPage .current').html(paged);

    //Link Change
    if (window.location['href'].split('?')[1] != undefined && window.location['href'].split('?')[1] != '') {
      separator = '&';
    } else {
      separator = '?';
    }
    if (window.location['href'].split('paged=')[1] != '' && window.location['href'].split('paged=')[1] != undefined) {
      if (window.location['href'].split('paged=')[1].split('&')[1] != '' && window.location['href'].split('paged=')[1].split('&')[1] != undefined) {
        window.history.pushState('', '', window.location['href'].split('paged=')[0] + "paged=".concat(paged) + '&' + window.location['href'].split('paged=')[1].split('&')[1]);
      } else {
        window.history.pushState('', '', window.location['href'].split('paged=')[0] + "paged=".concat(paged));
      }
    } else {
      window.history.pushState('', '', window.location + "".concat(separator, "paged=").concat(paged));
    }
    if (paged == 1) {
      $('.shopPage__paginationButton.prev').addClass('disabled');
    } else if (paged > 1 && $('.shopPage__paginationButton.prev').hasClass('disabled')) {
      $('.shopPage__paginationButton.prev').removeClass('disabled');
    }
    if (paged == total) {
      $('.shopPage__paginationButton.next').addClass('disabled');
    } else if (paged != total && $('.shopPage__paginationButton.next').hasClass('disabled')) {
      $('.shopPage__paginationButton.next').removeClass('disabled');
    }
  });
  $(document).ajaxSend(function (event, xhr, settings) {
    var action = settings.data ? settings.data.split('action=')[1].split('&paged')[0] : '';
    if (action && action === 'products_pagination') {
      $('.shopPage__list').addClass('loading');
    }
  });
  $(document).ajaxComplete(function () {
    $('.shopPage__list').removeClass('loading');
  });
});