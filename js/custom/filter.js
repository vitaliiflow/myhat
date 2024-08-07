jQuery(document).ready(function($){
    const w = $(window).width();
    let varumarke = $('.shopPage__list').attr('data-varumarke'),
        storek = $('.shopPage__list').attr('data-storek'),
        taggar = $('.shopPage__list').attr('data-taggar'),
        color = $('.shopPage__list').attr('data-color'),
        team = $('.shopPage__list').attr('data-team'),
        kategori = $('.shopPage__list').attr('data-kategori'),
        searchText = $('.shopPage__list').attr('data-search');

    function updateBreadcrumbs(arr, team, farg, tag, storlek, varumarke){
        $.ajax({
            url: codelibry.ajax_url,
            type: 'post',
            data: {
                action: 'breadcrumbs_changing',
                kategori: arr,
                team: team,
                farg: farg,
                tag: tag,
                storlek: storlek,
                varumarke: varumarke,
            },
            success: function(response) {
                $('#main .woocommerce-breadcrumb').html(response);
            }
        })
    }


    $.ajax({
        url: codelibry.ajax_url,
        type: 'post',
        data: {
            action: 'filters_init',
            varumarke: varumarke,
            storek: storek,
            taggar: taggar,
            team: team,
            color: color,
            kategori: kategori,
            searchText: searchText,
        },
        success: function(response){
            $('.filter .shopPage__filtersRow__listWrapper').html(response);
        }
    });
    $('.shopPage__filtersRow__listWrapper').addClass('loading');

    $(window).on('load', function(){
        if(w >= 994){
            $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__itemTitle').click(function(){
                $(this).parent().find('.shopPage__filtersRow__listWrapper').stop().slideToggle();
            });
            $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__list__apply').click(function(){
                $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__listWrapper').slideUp();
            });

        } else{
            $('.sort-wrapper .shopPage__filtersRow__item .mobile-toggler').click(function(){
                $(this).closest('.shopPage__filtersRow__item').toggleClass('opened');
            })
        }


        
        


        $('.shopPage__filtersRow__listItem').click(function(){
            $(this).parent().find('.shopPage__filtersRow__listItem').removeClass('active');
            $(this).addClass('active');
        });
    });


    //Products Sorting
    $('.shopPage__filtersRow__item.sort .shopPage__filtersRow__list__apply').click(function(){
        const sortType = $(this).closest('.shopPage__filtersRow__item.sort').find('.active .shopPage__filtersRow__listItem__name').attr('data-slug'),
              paged = $('.shopPage__list').attr('data-paged'),
              varumarke = $('.shopPage__list').attr('data-varumarke'),
              storek = $('.shopPage__list').attr('data-storek'),
              taggar = $('.shopPage__list').attr('data-taggar'),
              color = $('.shopPage__list').attr('data-color'),
              team = $('.shopPage__list').attr('data-team'),
              kategori = $('.shopPage__list').attr('data-kategori'),
              searchText = $('.shopPage__list').attr('data-search');

        let order, orderby, separator,
            metaKey = '';


        switch(sortType){
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
                metaKey: metaKey,
                varumarke: varumarke,
                storek: storek,
                taggar: taggar,
                team: team,
                color: color,
                kategori: kategori,
                searchText: searchText,
            },
            success: function(response){
                $('.products').html(response);
            }
        });
        $('.shopPage__list').attr('data-sort', sortType);


        //Link Change
        if(window.location['href'].split('?')[1] != undefined && window.location['href'].split('?')[1] != ''){
            separator = '&';
        } else{
            separator = '?';
        }

        if(window.location['href'].split('orderby=')[1] != '' && window.location['href'].split('orderby=')[1] != undefined) {
            if(window.location['href'].split('orderby=')[1].split('&')[1] != '' && window.location['href'].split('orderby=')[1].split('&')[1] != undefined){
                window.history.pushState('', '', window.location['href'].split('orderby=')[0] + `orderby=${sortType}` + '&' + window.location['href'].split('orderby=')[1].split('&')[1]);
            } else{
                window.history.pushState('', '', window.location['href'].split('orderby=')[0] + `orderby=${sortType}`);
            }
        } else{
            window.history.pushState('', '', window.location + `${separator}orderby=${sortType}`);
        }
    })

    //Product Attributes Load
    
    if(varumarke != '' && varumarke != undefined){
        varumarke = varumarke.split(',');
        varumarke.forEach(function(i){
            $(`.shopPage__filtersRow__listItem[data-attr-name="varumarke"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"]`).addClass('active').css('order', -1);
            $(`<div class="shopPage__filtersRow__pillsList__item" data-term="${i}"><div class="shopPage__filtersRow__pillsList__itemRemove"></div><div class="shopPage__filtersRow__pillsList__itemLabel">${$(`.shopPage__filtersRow__listItem[data-attr-name="varumarke"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"] .shopPage__filtersRow__listItem__sublistItem__name`).html()}</div></div>`).appendTo('.shopPage__filtersRow__pillsList');
        })
    }
    if(storek != '' && storek != undefined){
        storek = storek.split(',');
        storek.forEach(function(i){
            $(`.shopPage__filtersRow__listItem[data-attr-name="storek"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"]`).addClass('active').css('order', -1);
            $(`<div class="shopPage__filtersRow__pillsList__item" data-term="${i}"><div class="shopPage__filtersRow__pillsList__itemRemove"></div><div class="shopPage__filtersRow__pillsList__itemLabel">${$(`.shopPage__filtersRow__listItem[data-attr-name="storek"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"] .shopPage__filtersRow__listItem__sublistItem__name`).html()}</div></div>`).appendTo('.shopPage__filtersRow__pillsList');
        })
    }
    if(taggar != '' && taggar != undefined){
        taggar = taggar.split(',');
        taggar.forEach(function(i){
            $(`.shopPage__filtersRow__listItem[data-attr-name="taggar"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"]`).addClass('active').css('order', -1);
            $(`<div class="shopPage__filtersRow__pillsList__item" data-term="${i}"><div class="shopPage__filtersRow__pillsList__itemRemove"></div><div class="shopPage__filtersRow__pillsList__itemLabel">${$(`.shopPage__filtersRow__listItem[data-attr-name="taggar"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"] .shopPage__filtersRow__listItem__sublistItem__name`).html()}</div></div>`).appendTo('.shopPage__filtersRow__pillsList');
        })
    }
    if(color != '' && color != undefined){
        color = color.split(',');
        color.forEach(function(i){
            $(`.shopPage__filtersRow__listItem[data-attr-name="color"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"]`).addClass('active').css('order', -1);
            $(`<div class="shopPage__filtersRow__pillsList__item" data-term="${i}"><div class="shopPage__filtersRow__pillsList__itemRemove"></div><div class="shopPage__filtersRow__pillsList__itemLabel">${$(`.shopPage__filtersRow__listItem[data-attr-name="color"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"] .shopPage__filtersRow__listItem__sublistItem__name`).html()}</div></div>`).appendTo('.shopPage__filtersRow__pillsList');
        })
    }
    if(team != '' && team != undefined){
        team = team.split(',');
        team.forEach(function(i){
            $(`.shopPage__filtersRow__listItem[data-attr-name="team"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"]`).addClass('active').css('order', -1);
            $(`<div class="shopPage__filtersRow__pillsList__item" data-term="${i}"><div class="shopPage__filtersRow__pillsList__itemRemove"></div><div class="shopPage__filtersRow__pillsList__itemLabel">${$(`.shopPage__filtersRow__listItem[data-attr-name="team"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"] .shopPage__filtersRow__listItem__sublistItem__name`).html()}</div></div>`).appendTo('.shopPage__filtersRow__pillsList');
        })
    }
    if(kategori != '' && kategori != undefined){
        kategori = kategori.split(',');
        kategori.forEach(function(i){
            $(`.shopPage__filtersRow__listItem[data-attr-name="kategori"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"]`).addClass('active').css('order', -1);
            $(`<div class="shopPage__filtersRow__pillsList__item category" data-term="${i}"><div class="shopPage__filtersRow__pillsList__itemRemove"></div><div class="shopPage__filtersRow__pillsList__itemLabel">${$(`.shopPage__filtersRow__listItem[data-attr-name="kategori"] .shopPage__filtersRow__listItem__sublistItem[data-slug="${i}"] .shopPage__filtersRow__listItem__sublistItem__name`).html()}</div></div>`).appendTo('.shopPage__filtersRow__pillsList');
        })
    }
    if(searchText != '' && searchText != undefined){
        $(`<div class="shopPage__filtersRow__pillsList__item"><div class="shopPage__filtersRow__pillsList__itemRemove search-remove"></div><div class="shopPage__filtersRow__pillsList__itemLabel">Sök: ${searchText}</div></div>`).appendTo('.shopPage__filtersRow__pillsList');
    }
    

    if(w < 994 ){
        $('.shopPage').css('padding-top', $('.shopPage__filtersRow__pillsList').outerHeight() + 10);
    }

    $('.filters-wrapper .shopPage__filtersRow__item .mobile-toggler').click(function(){
        $(this).closest('.shopPage__filtersRow__item').toggleClass('opened');
    })

    //Product Filters 
    function filters(){

        $('.filters-wrapper .shopPage__filtersRow__item .mobile-toggler.refreshed').click(function(){
            $(this).closest('.shopPage__filtersRow__item').toggleClass('opened');
        })

        $('.shopPage__filtersRow__listItem__title').click(function(){
            const item = $(this).parent(),
                  sublist = $(this).parent().find('.shopPage__filtersRow__listItem__sublist');
            // $(this).closest('.shopPage__filtersRow__listWrapper').find('.shopPage__filtersRow__listItem__sublist').not(sublist).stop().slideUp();
            // $(this).closest('.shopPage__filtersRow__listWrapper').find('.shopPage__filtersRow__listItem').not(item).removeClass('opened');
            
            item.toggleClass('opened');
            sublist.stop().slideToggle();
        });

        $('.shopPage__filtersRow__listItem__sublistItem').click(function(){
            if($(this).closest('.shopPage__filtersRow__listItem').attr('data-attr-name') == 'kategori'){
                const topContentCategory = $(this).attr('data-slug');
                $(this).closest('.shopPage__filtersRow__listItem').find('.shopPage__filtersRow__listItem__sublistItem').not($(this)).removeClass('active');
                //Change Top Content
                $.ajax({
                    url: codelibry.ajax_url,
                    type: 'post',
                    data: {
                        action: 'topContentChange',
                        topContentCategory: topContentCategory,
                    },
                    success: function(response){
                        $('.seo-text__content').html(response);
                        $('.seo-text').removeClass('seo-text__content--long-opened');
                        if($('.seo-text').prop('scrollHeight') <= Math.ceil($('.seo-text').outerHeight())){
                            $('.seo-text').removeClass('seo-text__content--long');
                        } else {
                            $('.seo-text').addClass('seo-text__content--long');
                        }
                    }
                })
            }
            $(this).toggleClass('active');
        })

        //Restore Filters 
        $('.shopPage__filtersRow__list__clear').click(function(){
            $('.shopPage__filtersRow__listItem__sublistItem').removeClass('active');
            $('.filters-wrapper .shopPage__filtersRow__list__apply .btn').click();
            $.ajax({
                url: codelibry.ajax_url,
                type: 'post',
                data: {
                    action: 'topContentChange',
                    topContentCategory: '',
                },
                success: function(response){
                    $('.seo-text__content').html(response);
                    $('.seo-text').removeClass('seo-text__content--long-opened');
                    if($('.seo-text').prop('scrollHeight') <= Math.ceil($('.seo-text').outerHeight())){
                        $('.seo-text').removeClass('seo-text__content--long');
                    } else {
                        $('.seo-text').addClass('seo-text__content--long');
                    }
                }
            })
        })


        $('.shopPage__filtersRow__listItem__sublistItem, .shopPage__filtersRow__list__apply .btn').click(function(){
            let varumarke_list = [],
                storek_list = [],
                taggar_list = [],
                color_list = [],
                team_list = [],
                kategori_list = [],
                openedItems = [],
                order = '',
                orderby = '',
                metaKey = '';
            
            const paged = 1,
                  sortType = $('.shopPage__list').attr('data-sort'),
                  searchText = $('.shopPage__list').attr('data-search');
    
            switch(sortType){
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
    
            $('.shopPage__filtersRow__pillsList').html('');
    
            $('.shopPage__filtersRow__listItem__sublist').each(function(){
                const attrName = $(this).closest('.shopPage__filtersRow__listItem').attr('data-attr-name');
                $(this).find('.shopPage__filtersRow__listItem__sublistItem').each(function(){
                    if($(this).hasClass('active')){
                        let itemClass = '';
                        if(attrName == 'kategori'){
                            itemClass = ' category';
                        }
                        $(`<div class="shopPage__filtersRow__pillsList__item${itemClass}" data-term="${$(this).attr('data-slug')}"><div class="shopPage__filtersRow__pillsList__itemRemove"></div><div class="shopPage__filtersRow__pillsList__itemLabel">${$(this).find('.shopPage__filtersRow__listItem__sublistItem__name').html()}</div></div>`).appendTo('.shopPage__filtersRow__pillsList');
                        switch(attrName){
                            case 'varumarke': 
                                varumarke_list.push($(this).attr('data-slug'));
                                break;
                            case 'storek': 
                                storek_list.push($(this).attr('data-slug'));
                                break;
                            case 'taggar': 
                                taggar_list.push($(this).attr('data-slug'));
                                break;
                            case 'team': 
                                team_list.push($(this).attr('data-slug'));
                                break;
                            case 'color': 
                                color_list.push($(this).attr('data-slug'));
                                break;
                            case 'kategori': 
                                kategori_list.push($(this).attr('data-slug'));
                                break;
                        }
                    }
                });
            });
            if(searchText != '' && searchText != undefined){
                $(`<div class="shopPage__filtersRow__pillsList__item"><div class="shopPage__filtersRow__pillsList__itemRemove search-remove"></div><div class="shopPage__filtersRow__pillsList__itemLabel">Sök: ${searchText}</div></div>`).appendTo('.shopPage__filtersRow__pillsList');
            }
            if(w < 994 ){
                $('.shopPage').css('padding-top', $('.shopPage__filtersRow__pillsList').outerHeight() + 10);
            }
            if(varumarke_list.length > 0 || storek_list.length > 0 || taggar_list.length > 0 || kategori_list.length > 0){
                $('.shopPage__filtersRow__list__clear').addClass('show');
            } else {
                $('.shopPage__filtersRow__list__clear').removeClass('show');
            }
    
            
            
            $.ajax({
                url: codelibry.ajax_url,
                type: 'post',
                data: {
                    action: 'products_filter',
                    paged: paged,
                    order: order,
                    orderby: orderby,
                    metaKey: metaKey,
                    varumarke: varumarke_list,
                    storek: storek_list,
                    taggar: taggar_list,
                    team: team_list,
                    color: color_list,
                    kategori: kategori_list,
                    searchText: searchText,
                },
                success: function(response){
                    $('.shopPage__list').html(response);
                }
            });
            $('.shopPage__list').attr('data-varumarke', varumarke_list);
            $('.shopPage__list').attr('data-storek', storek_list);
            $('.shopPage__list').attr('data-taggar', taggar_list);
            $('.shopPage__list').attr('data-team', team_list);
            $('.shopPage__list').attr('data-color', color_list);
            $('.shopPage__list').attr('data-kategori', kategori_list);

    
    
            
            
            if(w < 993){
                $('.shopPage__filtersRow__item.filter').removeClass('opened');
            }
    
            
    
            
            

            $('.shopPage__filtersRow__listItem').each(function(){
                if($(this).hasClass('opened')){
                    openedItems.push($(this).attr('data-attr-name'));
                }
                
            })
            $.ajax({
                url: codelibry.ajax_url,
                type: 'post',
                data: {
                    action: 'changing_filters',
                    varumarke: varumarke_list,
                    storek: storek_list,
                    taggar: taggar_list,
                    team: team_list,
                    color: color_list,
                    kategori: kategori_list,
                    openedItems: openedItems,
                    searchText: searchText,
                },
                success: function(response){
                    $('.filter .shopPage__filtersRow__listWrapper').html(response);
                }
            })
            $(document).ajaxComplete(function(event, xhr, settings){
                if(settings.data !== undefined){
                    if(settings.data.includes('action')){
                        const action = settings.data ? settings.data.split('action=')[1].split('&')[0] : '';
                        if(action && (action === 'changing_filters')){
                            let pageLink;
                            if($('.filter .shopPage__filtersRow__listClose').attr('data-cat-link') != '' && $('.filter .shopPage__filtersRow__listClose').attr('data-cat-link') != undefined){
                                pageLink = $('.filter .shopPage__filtersRow__listClose').attr('data-cat-link') + `?paged=${paged}&orderby=${sortType}`;
                            } else{
                                pageLink = window.location['origin'] + `/butik/?paged=${paged}&orderby=${sortType}`;
                            }
                            if(searchText != '' && searchText != undefined){
                                let searchArr = [searchText];
                                pageLink = updateLink(searchArr, 's=', pageLink);
                            }
                            pageLink = updateLink(varumarke_list, 'varumarke_cat=', pageLink);
                            pageLink =  updateLink(storek_list, 'storek=', pageLink);
                            pageLink =  updateLink(taggar_list, 'taggar=', pageLink);
                            pageLink =  updateLink(color_list, 'colors=', pageLink);
                            pageLink =  updateLink(team_list, 'teams=', pageLink);
                        
                                
                            window.history.pushState('', '', pageLink);
                        }
                    }
                }
            })
        


            updateBreadcrumbs(kategori_list, team_list, color_list, taggar_list, storek_list, varumarke_list);
        })
    }

    
    updateBreadcrumbs(kategori, team, color, taggar, storek, varumarke);
    filters();





    $(document).ajaxComplete(function(event, xhr, settings){
        if(settings.data !== undefined){
            if(settings.data.includes('action')){
                const action = settings.data ? settings.data.split('action=')[1].split('&')[0] : '';
                if (action === 'changing_filters' || action === 'filters_init') {
                    filters();
                }
                if (action === 'filters_init') {
                    $('.shopPage__filtersRow__listWrapper').removeClass('loading');
                }
            }
        }
    })

   

    
});

function updateLink(arr, tax, link){
    if(arr.length > 0){
        link = link + `&${tax}${arr}`
    }
    return link;
}
