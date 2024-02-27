jQuery(document).ready(function($){

    $('.header__searchIcon').click(function(){
        $(this).parent().find('.search__barWrapper').toggleClass('opened');
    });
    $('.search__barWrapper input.search-input').bind("change paste keyup", function () {
        setTimeout(() => {
            var fieldData = $(this).val();
            $.ajax({
                url: codelibry.ajax_url,
                type: 'post',
                data: {
                    action: 'ajax_search',
                    fieldData: fieldData,
                },
                success: function (result) {
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
    $('.header__iconsList__item.search > img').click(function(){
        $(this).parent().find('.search__barWrapper').toggleClass('opened');
    })
    $(document).click(function(e){
        if(!$('.header__iconsList__item.search').is(e.target) && $('.header__iconsList__item.search').has(e.target).length === 0){
            $('.search__barWrapper').removeClass('opened');
        }
    })

    /*search on brands-page*/
    $('#search-brands-form').on('submit', function(event) {
        event.preventDefault();
        var searchValue = $('#search-brands-form input[type="text"]').val();
        var brandsList = $('.brands-page__list');

        brandsList.html('<li class="searching-brands-text">Search...</li>');

        $.ajax({
            url: codelibry.ajax_url,
            type: 'post',
            data: {
                action: 'search_brands',
                search_query: searchValue
            },
            success: function(response) {
                brandsList.empty();

                if (response.success && response.data.length > 0) {
                    response.data.forEach(function(brand) {
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
                    brandsList.html('<li>No brands found.</li>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX request failed:', textStatus, errorThrown);
                brandsList.html('<li>Error during search. Please try again.</li>');
            }
        });
    });

    $('.clear-search-results-btn').hide();

    // Checking an input field for a value
    $('#search-brands-form input[type="text"]').on('input', function() {
        if ($(this).val().length > 0) {
            $('.clear-search-results-btn').show();
        } else {
            $('.clear-search-results-btn').hide();
        }
    });

    $('.clear-search-results-btn').on('click', function() {
        //trigger - fires an input event on the same text field.
        $('#search-brands-form input[type="text"]').val('').trigger('input');

        var brandsList = $('.brands-page__list');
        brandsList.html('<li class="searching-brands-text">Search...</li>');

        $.ajax({
            url: codelibry.ajax_url,
            type: 'post',
            data: {
                action: 'search_brands',
                search_query: '' // An empty line means requesting all terms
            },
            success: function(response) {
                brandsList.empty();

                if (response.success && response.data.length > 0) {
                    response.data.forEach(function(brand) {
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
                    brandsList.html('<li>No brands found.</li>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX request failed:', textStatus, errorThrown);
                brandsList.html('<li>Error during search. Please try again.</li>');
            }
        });
    });

    /*search on teams-page*/
    $('#search-teams-form').on('submit', function(event) {
        event.preventDefault();
        var searchValue = $('#search-teams-form input[type="text"]').val().trim();
        var teamsList = $('.tabs__item-list');
        var tabsContainer = $('.search-results');

        tabsContainer.html('<li class="searching-brands-text">Search...</li>');

        if (searchValue === '') {
            $.ajax({
                url: codelibry.ajax_url,
                type: 'post',
                data: {
                    action: 'get_initial_teams_content'
                },
                success: function(response) {
                    if (response.success) {
                        tabsContainer.html(response.data);
                        initializeTabs();
                    } else {
                        tabsContainer.html('<div>Unable to reset. Please try again.</div>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    tabsContainer.html('<div>Error during reset. Please try again.</div>');
                    console.error('AJAX request failed:', textStatus, errorThrown);
                }
            });
        } else {
            $.ajax({
                url: codelibry.ajax_url,
                type: 'post',
                data: {
                    action: 'search_teams',
                    search_query: searchValue
                },
                success: function(response) {
                    tabsContainer.empty();
                    console.log(response.data);
                    // Проверка, есть ли хотя бы один объект с непустым массивом child_terms
                    var hasTeams = response.data.some(function(league) {
                        return league.child_terms.length > 0;
                    });

                    if (response.success && hasTeams) {
                        response.data.forEach(function(league) {
                            if (league.child_terms.length > 0) {
                                var leagueSection = $('<section>');
                                var leagueName = $('<h6>').text(league.custom_name);
                                var leagueList = $('<ul class="teams-page-result-list row">');

                                league.child_terms.forEach(function (term) {
                                    var termItem = $('<li class="col-4 col-md-3 col-lg-2 py-2 tabs__item-child-item">');
                                    var termLink = $('<a class="d-block">').attr('href', term.url).text(term.name);

                                    if (term.logo) {
                                        // Предполагается, что term.logo - это URL, если это ID, необходимо изменить
                                        var termImage = $('<img>').attr('src', term.logo).attr('alt', term.name);
                                        termLink.prepend(termImage);
                                    }
                                    termItem.append(termLink);
                                    leagueList.append(termItem);
                                });

                                leagueSection.append(leagueName).append(leagueList);
                                tabsContainer.append(leagueSection);
                            }
                        });

                    } else {
                        tabsContainer.html('<div>No teams found.</div>');
                        console.log('nothing...');

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    tabsContainer.html('<div>Error during search. Please try again.</div>');
                    console.error('AJAX request failed:', textStatus, errorThrown);
                }
            });

        }
    });

    $('.clear-search-results-btn__teams').hide();

    // Checking an input field for a value
    $('#search-teams-form input[type="text"]').on('input', function() {
        if ($(this).val().length > 0) {
            $('.clear-search-results-btn__teams').show();
        } else {
            $('.clear-search-results-btn__teams').hide();
        }
    });

    $('.clear-search-results-btn__teams').on('click', function() {
        $('#search-teams-form input[type="text"]').val('').trigger('input');

        var tabsContainer = $('.teams-page__tabs');
        tabsContainer.empty();

        $.ajax({
            url: codelibry.ajax_url,
            type: 'post',
            data: {
                action: 'get_initial_teams_content'
            },
            success: function(response) {
                if (response.success) {
                    tabsContainer.html(response.data);
                    initializeTabs();
                } else {
                    tabsContainer.html('<div>Unable to reset. Please try again.</div>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                tabsContainer.html('<div>Error during reset. Please try again.</div>');
                console.error('AJAX request failed:', textStatus, errorThrown);
            }
        });
    });

    function initializeTabs() {
        var tabsContainer = $('.tabs');

        if (tabsContainer.length) {
            console.log('tabs');

            tabsContainer.each(function() {
                var block = $(this);
                block.find('.js-tab-nav').off('click').on('click', function(e) {
                    e.preventDefault();
                    var id = $(this).attr('href');
                    console.log(id);
                    block.find('.tabs__nav').removeClass('active');
                    $(this).addClass('active');
                    block.find('.tabs__item').removeClass('active').hide();
                    $(id).fadeIn().addClass('active');
                });
            });
        }
    }

})
    