<?php 
//Remove woocommerce styles
add_action('wp', 'remove_woocommerce_styles_filter');
function remove_woocommerce_styles_filter() {
    if (is_checkout()) {
        remove_filter('woocommerce_enqueue_styles', '__return_empty_array');
    }
}

add_action('wp', 'add_woocommerce_styles_filter');
function add_woocommerce_styles_filter() {
    if (!is_checkout()) {
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    }
}



add_action('wp_ajax_nopriv_ajax_search', 'ajax_search');
add_action('wp_ajax_ajax_search', 'ajax_search');

function ajax_search(){

    $searchText = $_POST['fieldData'];
    if($searchText !== ''){
        $args = array(
            'public'   => true,
            '_builtin' => false
        );
        $output = 'names';
        $operator = 'and';
        $post_types = get_post_types( $args, $output, $operator );
        if ( $post_types ) { ?> 
        <div class="search__resultsList__content">
            <?php
            foreach ( $post_types  as $post_type ) {
                $args = array(
                    'posts_per_page' => 5,
                    'specific_chars'     => $searchText,
                );
                $pt_name = get_post_type_object($post_type);
                ?>
                <?php $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) { ?>
                    <?php $i = 0;
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post(); ?>
                        <?php if(get_post_type(get_the_ID()) == $post_type):
                        $i++;
                        endif;
                    }
                    if($i > 0):
                    ?>
                    <div class="search_results__listWrapper">
                        <div class="pt_name"><?php echo $pt_name->label; ?></div>
                        <div class="search_results__list">
                        <?php
                        while ( $the_query->have_posts() ) {
                            $the_query->the_post(); ?>
                                <?php if(get_post_type(get_the_ID()) == $post_type): ?>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <?php endif; ?>
                            <?php } ?>
                        </div>
                    </div>
                    <?php endif; ?>

                <?php }

                else{ ?>

                <?php }

            }

            $args = array(
                'posts_per_page' => -1,
                'specific_chars'     => $searchText,
            );
            $the_query = new WP_Query($args);
            $num = 0;
            if($the_query->have_posts()):
                while($the_query->have_posts()): $the_query->the_post();
                    $num++;
                endwhile;
            endif;
            if($num > 5): ?>
                <div class="all-results"><a href="<?php echo get_home_url() . '/?s=' . $searchText . '&id=219'; ?>">see all results</a></div>
            <?php endif; ?>
            <?php if($num == 0): ?>
                <div class="is-ajax-search-no-result">
                    <?php echo 'Nothing was found'; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php }
    }
    die();
}

//Ajax Sorting
add_action('wp_ajax_nopriv_products_sorting', 'products_sorting');
add_action('wp_ajax_products_sorting', 'products_sorting');
function products_sorting() {
    $paged = $_POST['paged'];

    $order = $_POST['order'];
    $orderby = $_POST['orderby'];
    

    if(!empty($_POST['searchText'])):
        $search = $_POST['searchText'];
    endif;
    
    if(!empty($_POST['metaKey'])):
        $metaKey = $_POST['metaKey'];
    endif;

    if(!empty($_POST['varumarke'])):
        $varumarke = $_POST['varumarke'];
    endif;
    if(!empty($_POST['storek'])):
        $storek = $_POST['storek'];
    endif;
    if(!empty($_POST['taggar'])):
        $taggar = $_POST['taggar'];
    endif;
    if(!empty($_POST['color'])):
        $color = $_POST['color'];
    endif;
    if(!empty($_POST['team'])):
        $team = $_POST['team'];
    endif;
    if(!empty($_POST['kategori'])):
        $kategori = $_POST['kategori'];
    endif;


    $args = array(
        'post_type' => 'product',
        'post_status'    => array( 'publish' ),
        'posts_per_page' => 16,
        'paged' => $paged,
        'order' => $order,
        'orderby' => $orderby,
        'meta_query'     => array(
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
        ),
        'tax_query' => array(),
    );

    if(!empty($search)){
        $args['specific_chars'] = $search;
    }

    if(!empty($metaKey)){
        $args['meta_key'] = $metaKey;
    }

    if(!empty($varumarke)){
        $varumarke = explode(',', $varumarke);
        $varumarke_array = array(
            'taxonomy' => 'varumarke',
            'field' => 'slug',
            'terms' => $varumarke,
        );
        array_push($args["tax_query"], $varumarke_array);
    }
    if(!empty($storek)){
        $storek = explode(',', $storek);
        $storek_array = array(
            'taxonomy' => 'pa_storlek',
            'field' => 'slug',
            'terms' => $storek,
        );
        array_push($args["tax_query"], $storek_array);
    }
    if(!empty($taggar)){
        $taggar = explode(',', $taggar);
        $taggar_array = array(
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => $taggar,
        );
        array_push($args["tax_query"], $taggar_array);
    }
    if(!empty($color)){
        $color = explode(',', $color);
        $color_array = array(
            'taxonomy' => 'color',
            'field' => 'slug',
            'terms' => $color,
        );
        array_push($args["tax_query"], $color_array);
    }
    if(!empty($team)){
        $team = explode(',', $team);
        $team_array = array(
            'taxonomy' => 'team',
            'field' => 'slug',
            'terms' => $team,
        );
        array_push($args["tax_query"], $team_array);
    }
    if(!empty($kategori)){
        $kategori = explode(',', $kategori);
        $kategori_array = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $kategori,
        );
        array_push($args["tax_query"], $kategori_array);
    }

    $the_query = new WP_Query($args);
    if($the_query->have_posts()):
        while($the_query->have_posts()): $the_query->the_post(); ?>
            <?php global $product; ?>
            <div class="shopPage__listItem col-6 col-md-3 product-<?php echo get_the_ID() ?>">
                <?php wc_get_template_part( 'content', 'product' ); ?>
            </div>
        <?php endwhile;
    endif;
    die();
}


//Ajax Filter
add_action('wp_ajax_nopriv_products_filter', 'products_filter');
add_action('wp_ajax_products_filter', 'products_filter');
function products_filter() {
    $paged = $_POST['paged'];

    $order = $_POST['order'];
    $orderby = $_POST['orderby'];
    if(!empty($_POST['metaKey'])):
        $metaKey = $_POST['metaKey'];
    endif;

    if(!empty($_POST['varumarke'])):
        $varumarke = $_POST['varumarke'];
    endif;
    if(!empty($_POST['storek'])):
        $storek = $_POST['storek'];
    endif;
    if(!empty($_POST['taggar'])):
        $taggar = $_POST['taggar'];
    endif;
    if(!empty($_POST['color'])):
        $color = $_POST['color'];
    endif;
    if(!empty($_POST['team'])):
        $team = $_POST['team'];
    endif;
    if(!empty($_POST['kategori'])):
        $kategori = $_POST['kategori'];
    endif;

    $args = array(
        'post_type' => 'product',
        'post_status'    => array( 'publish' ),
        'posts_per_page' => 16,
        'paged' => 1,
        'order' => $order,
        'orderby' => $orderby,
        'meta_query'     => array(
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
        ),
        'tax_query' => array(),
    );

    if(!empty($_POST['searchText'])):
        $args['specific_chars'] = $_POST['searchText'];
    endif;

    if(!empty($metaKey)){
        $args['meta_key'] = $metaKey;
    }

    if(!empty($varumarke)){
        $varumarke_array = array(
            'taxonomy' => 'varumarke',
            'field' => 'slug',
            'terms' => $varumarke,
        );
        array_push($args["tax_query"], $varumarke_array);
    }
    if(!empty($storek)){
        $storek_array = array(
            'taxonomy' => 'pa_storlek',
            'field' => 'slug',
            'terms' => $storek,
        );
        array_push($args["tax_query"], $storek_array);
    }
    if(!empty($taggar)){
        $taggar_array = array(
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => $taggar,
        );
        array_push($args["tax_query"], $taggar_array);
    }
    if(!empty($color)){
        $color_array = array(
            'taxonomy' => 'color',
            'field' => 'slug',
            'terms' => $color,
        );
        array_push($args["tax_query"], $color_array);
    }
    if(!empty($team)){
        $team_array = array(
            'taxonomy' => 'team',
            'field' => 'slug',
            'terms' => $team,
        );
        array_push($args["tax_query"], $team_array);
    }
    if(!empty($kategori)){
        $kategori_array = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $kategori,
        );
        array_push($args["tax_query"], $kategori_array);
    }
    $the_query = new WP_Query($args);
    if($the_query->have_posts()): ?>
        <ul class="products columns-4">
        <?php
            while($the_query->have_posts()): $the_query->the_post(); ?>
                <?php global $product; ?>
                    <div class="shopPage__listItem col-6 col-md-3 product-<?php echo get_the_ID() ?>">
                        <?php wc_get_template_part( 'content', 'product' ); ?>
                    </div>
            <?php endwhile; ?>
        </ul>
        </div>
        <div class="shopPage__pagination">
            <a href="#" rel="previous" class="shopPage__paginationButton prev<?php if($paged == 1){ echo ' disabled';} ?>"><?php echo get_inline_svg('pagination-arrow-right.svg'); ?>Föregående</a>
            <div class="shopPage__paginationPage">
                <span class="current"><?php echo $paged; ?></span>
                <span>/</span>
                <span class="total"><?php echo $the_query->max_num_pages; ?></span>
            </div>
            <a href="#" rel="next" class="shopPage__paginationButton next<?php if($the_query->max_num_pages <= 1 || $paged == $the_query->max_num_pages){echo ' disabled';} ?>">Nästa<?php echo get_inline_svg('pagination-arrow-right.svg'); ?></a>
    <?php else: 
        echo '<h4>Inget hittades...</h4>';
    endif;
    die();
}

//Pagination
add_action('wp_ajax_nopriv_products_pagination', 'products_pagination');
add_action('wp_ajax_products_pagination', 'products_pagination');
function products_pagination() {
    $paged = $_POST['paged'];

    $order = $_POST['order'];
    $orderby = $_POST['orderby'];

    $searchText = '';

    if(!empty($_POST['searchText'])):
        $searchText = $_POST['searchText'];
    endif;

    if(!empty($_POST['metaKey'])):
        $metaKey = $_POST['metaKey'];
    endif;

    if(!empty($_POST['varumarke'])):
        $varumarke = $_POST['varumarke'];
    endif;
    if(!empty($_POST['storek'])):
        $storek = $_POST['storek'];
    endif;
    if(!empty($_POST['taggar'])):
        $taggar = $_POST['taggar'];
    endif;
    if(!empty($_POST['color'])):
        $color = $_POST['color'];
    endif;
    if(!empty($_POST['team'])):
        $team = $_POST['team'];
    endif;
    if(!empty($_POST['kategori'])):
        $kategori = $_POST['kategori'];
    endif;


    $args = array(
        'post_type' => 'product',
        'post_status'    => array( 'publish' ),
        'posts_per_page' => 16,
        'paged' => $paged,
        'meta_query'     => array(
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
        ),
        'tax_query' => array(),
    );
    if(!empty($order)): 
        $args['order'] = $order;
    endif;
    if(!empty($orderby)): 
        $args['orderby'] = $orderby;
    endif;
    if(!empty($metaKey)): 
        $args['meta_key'] = $metaKey;
    endif;

    if(!empty($searchText)): 
        $args['specific_chars'] = $searchText;
    endif;


    if(!empty($varumarke)){
        $varumarke = explode(',', $varumarke);
        $varumarke_array = array(
            'taxonomy' => 'varumarke',
            'field' => 'slug',
            'terms' => $varumarke,
        );
        array_push($args["tax_query"], $varumarke_array);
    }
    if(!empty($storek)){
        $storek = explode(',', $storek);
        $storek_array = array(
            'taxonomy' => 'pa_storlek',
            'field' => 'slug',
            'terms' => $storek,
        );
        array_push($args["tax_query"], $storek_array);
    }
    if(!empty($taggar)){
        $taggar = explode(',', $taggar);
        $taggar_array = array(
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => $taggar,
        );
        array_push($args["tax_query"], $taggar_array);
    }
    if(!empty($team)){
        $team = explode(',', $team);
        $team_array = array(
            'taxonomy' => 'team',
            'field' => 'slug',
            'terms' => $team,
        );
        array_push($args["tax_query"], $team_array);
    }
    if(!empty($color)){
        $color = explode(',', $color);
        $color_array = array(
            'taxonomy' => 'color',
            'field' => 'slug',
            'terms' => $color,
        );
        array_push($args["tax_query"], $color_array);
    }
    if(!empty($kategori)){
        $kategori = explode(',', $kategori);
        $kategori_array = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $kategori,
        );
        array_push($args["tax_query"], $kategori_array);
    }

    $the_query = new WP_Query($args);
    ?>
    <?php if($the_query->have_posts()):
        while($the_query->have_posts()): $the_query->the_post(); ?>
            <?php global $product; ?>
            <div class="shopPage__listItem col-6 col-md-3 product-<?php echo get_the_ID() ?>">
                <?php wc_get_template_part( 'content', 'product' ); ?>
            </div>
        <?php endwhile;
    endif; ?>
    
    <?php die();
}


//Filters Init
add_action('wp_ajax_nopriv_filters_init', 'filters_init');
add_action('wp_ajax_filters_init', 'filters_init');
function filters_init() {
    $varumarke = '';
    $storek = '';
    $taggar = '';
    $team = '';
    $color = '';
    $kategori = '';
    $clear = false;

    if(!empty($_POST['varumarke'])):
        $varumarke = explode(',', $_POST['varumarke']);
        $clear = true;
    endif;
    if(!empty($_POST['storek'])):
        $storek = explode(',', $_POST['storek']);
        $clear = true;
    endif;
    if(!empty($_POST['taggar'])):
        $taggar = explode(',', $_POST['taggar']);
        $clear = true;
    endif;
    if(!empty($_POST['team'])):
        $team = explode(',', $_POST['team']);
        $clear = true;
    endif;
    if(!empty($_POST['color'])):
        $color = explode(',', $_POST['color']);
        $clear = true;
    endif;
    if(!empty($_POST['kategori'])):
        $kategori = explode(',', $_POST['kategori']);
        $clear = true;
    endif;

    $args = array(
        'post_type' => 'product',
        'post_status'    => array( 'publish' ),
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
        ),
        'tax_query' => array(),
    );

    if(!empty($metaKey)){
        $args['meta_key'] = $metaKey;
    }

    if(!empty($varumarke)){
        $varumarke_array = array(
            'taxonomy' => 'varumarke',
            'field' => 'slug',
            'terms' => $varumarke,
        );
        array_push($args["tax_query"], $varumarke_array);
    }
    if(!empty($storek)){
        $storek_array = array(
            'taxonomy' => 'pa_storlek',
            'field' => 'slug',
            'terms' => $storek,
        );
        array_push($args["tax_query"], $storek_array);
    }
    if(!empty($taggar)){
        $taggar_array = array(
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => $taggar,
        );
        array_push($args["tax_query"], $taggar_array);
    }
    if(!empty($team)){
        $team_array = array(
            'taxonomy' => 'team',
            'field' => 'slug',
            'terms' => $team,
        );
        array_push($args["tax_query"], $team_array);
    }
    if(!empty($color)){
        $color_array = array(
            'taxonomy' => 'color',
            'field' => 'slug',
            'terms' => $color,
        );
        array_push($args["tax_query"], $color_array);
    }
    if(!empty($kategori)){
        $kategori_array = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $kategori,
        );
        array_push($args["tax_query"], $kategori_array);
    }

    if(!empty($_POST['searchText'])){
        $args['specific_chars'] = $_POST['searchText'];
    }

    $query = new WP_Query($args);
    
    $list_varumarke = array();
    $list_storek = array();
    $list_taggar = array();
    $list_team = array();
    $list_color = array();
    $list_categories = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
    
            $post_terms = wp_get_post_terms(get_the_ID(), 'varumarke'); 
            $product_attributes = wc_get_product_terms(get_the_ID(), 'pa_storlek');
            $product_taggar = wc_get_product_terms(get_the_ID(), 'product_tag');
            $product_team = wc_get_product_terms(get_the_ID(), 'team');
            $product_color = wc_get_product_terms(get_the_ID(), 'color');
            $product_cat = wc_get_product_terms(get_the_ID(), 'product_cat');



            foreach ($post_terms as $term) {
                if(is_array($varumarke)){
                    if(!in_array($term->slug, $varumarke)){
                        $list_varumarke[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else{
                    $list_varumarke[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
                
            }
            foreach ($product_attributes as $term) {
                if(is_array($storek)){
                    if(!in_array($term->slug, $storek)){
                        $list_storek[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else{
                    $list_storek[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
                
            }
            foreach ($product_taggar as $term) {
                if(is_array($taggar)){
                    if(!in_array($term->slug, $taggar)){
                        $list_taggar[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else {
                    $list_taggar[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
            }
            foreach ($product_team as $term) {
                if(is_array($team)){
                    if(!in_array($term->slug, $team)){
                        $list_team[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else {
                    $list_team[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
            }
            foreach ($product_color as $term) {
                if(is_array($color)){
                    if(!in_array($term->slug, $color)){
                        $list_color[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else {
                    $list_color[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
            }
            foreach ($product_cat as $term) {
                if(is_array($kategori)){
                    if(!in_array($term->slug, $kategori)){
                        $list_categories[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else{
                    $list_categories[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
            }
        }
        wp_reset_postdata();
    }

    ?>
    <div class="shopPage__filtersRow__listClose mobile-toggler refreshed"></div>
    <?php 
    if ( (!empty($list_varumarke) && !is_wp_error( $list_varumarke )) || !empty($varumarke) ):
    ?>
        <div class="shopPage__filtersRow__listItem <?php if(!empty($varumarke) && $varumarke != ''){ echo ' opened'; } ?>" data-attr-name="varumarke">
            <div class="shopPage__filtersRow__listItem__title">VARUMÄRKE</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($varumarke) && $varumarke != ''){ echo ' style="display:block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($varumarke)): ?>
                        <?php foreach($varumarke as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'varumarke');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_varumarke as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php 
    if ( (!empty($list_storek) && !is_wp_error( $list_storek )) || !empty($storek) ):
    ?>
        <div class="shopPage__filtersRow__listItem<?php if(!empty($storek) && $storek != ''){ echo ' opened'; } ?>" data-attr-name="storek">
            <div class="shopPage__filtersRow__listItem__title">STORLEK</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($storek) && $storek != ''){ echo ' style="display:block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($storek)): ?>
                        <?php foreach($storek as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'pa_storlek');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_storek as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php 
    if ( (!empty($list_taggar) && !is_wp_error( $list_taggar )) || !empty($taggar) ):
    ?>
        <div class="shopPage__filtersRow__listItem<?php if(!empty($taggar) && $taggar != ''){ echo ' opened'; } ?>" data-attr-name="taggar">
            <div class="shopPage__filtersRow__listItem__title">TAGGAR</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($taggar) && $taggar != ''){ echo ' style="display:block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($taggar)): ?>
                        <?php foreach($taggar as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'product_tag');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_taggar as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php 
    if ( (!empty($list_color) && !is_wp_error( $list_color )) || !empty($color) ):
    ?>
        <div class="shopPage__filtersRow__listItem<?php if(!empty($color) && $color != ''){ echo ' opened'; } ?>" data-attr-name="color">
            <div class="shopPage__filtersRow__listItem__title">FÄRG</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($color) && $color != ''){ echo ' style="display:block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($color)): ?>
                        <?php foreach($color as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'color');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_color as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php 
    if ( (!empty($list_team) && !is_wp_error( $list_team )) || !empty($team) ):
    ?>
        <div class="shopPage__filtersRow__listItem<?php if(!empty($team) && $team != ''){ echo ' opened'; } ?>" data-attr-name="team">
            <div class="shopPage__filtersRow__listItem__title">TEAM</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($team) && $team != ''){ echo ' style="display:block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($team)): ?>
                        <?php foreach($team as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'team');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_team as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php 
    if ( (!empty($list_categories) && !is_wp_error( $list_categories )) || !empty($kategori) ):
    ?>
        <div class="shopPage__filtersRow__listItem category__item<?php if(!empty($kategori) && $kategori[0] != ''){ echo ' opened'; } ?>" data-attr-name="kategori">
            <div class="shopPage__filtersRow__listItem__title">KATEGORI</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($kategori) && $kategori[0] != ''){ echo ' style="display:block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($kategori) && $kategori[0] != ''): ?>
                        <?php foreach($kategori as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'product_cat');
                            $parent_term_slug = '';
                            if ($full_term->parent != 0) {
                                $parent_term = get_term($full_term->parent, 'product_cat');
                                $parent_term_slug = $parent_term->slug;
                            }
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>"<?php if(!empty($parent_term_slug)): ?> data-parent="<?php echo $parent_term_slug; ?>"<?php endif; ?>>
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_categories as $term): ?>
                        <?php 
                        $full_term = get_term_by('slug', $term['slug'], 'product_cat');

                        $parent_term_slug = '';
                        if ($full_term->parent != 0) {
                            $parent_term = get_term($full_term->parent, 'product_cat');
                            $parent_term_slug = $parent_term->slug;
                        }  
                        ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>"<?php if(!empty($parent_term_slug)): ?> data-parent="<?php echo $parent_term_slug; ?>"<?php endif; ?>>
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="shopPage__filtersRow__list__apply">
        <div class="btn button--black">APPLY</div>
    </div>
    <div class="shopPage__filtersRow__list__clear<?php if($clear){ echo ' show'; } ?>">Rensa filter</div>
    
    <?php die();
}



//Change filters
add_action('wp_ajax_nopriv_changing_filters', 'changing_filters');
add_action('wp_ajax_changing_filters', 'changing_filters');
function changing_filters() {
    $varumarke = '';
    $storek = '';
    $taggar = '';
    $color = '';
    $team = '';
    $kategori = '';
    $openedItems = '';
    $clear = false;
    if(!empty($_POST['varumarke'])):
        $varumarke = $_POST['varumarke'];
        $clear = true;
    endif;
    if(!empty($_POST['storek'])):
        $storek = $_POST['storek'];
        $clear = true;
    endif;
    if(!empty($_POST['taggar'])):
        $taggar = $_POST['taggar'];
        $clear = true;
    endif;
    if(!empty($_POST['color'])):
        $color = $_POST['color'];
        $clear = true;
    endif;
    if(!empty($_POST['team'])):
        $team = $_POST['team'];
        $clear = true;
    endif;
    if(!empty($_POST['kategori'])):
        $kategori = $_POST['kategori'];
        $clear = true;
    endif;
    if(!empty($_POST['openedItems'])):
        $openedItems = $_POST['openedItems'];
    endif;

    $args = array(
        'post_type' => 'product',
        'post_status'    => array( 'publish' ),
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
        ),
        'tax_query' => array(),
    );

    if(!empty($metaKey)){
        $args['meta_key'] = $metaKey;
    }

    if(!empty($varumarke)){
        $varumarke_array = array(
            'taxonomy' => 'varumarke',
            'field' => 'slug',
            'terms' => $varumarke,
        );
        array_push($args["tax_query"], $varumarke_array);
    }
    if(!empty($storek)){
        $storek_array = array(
            'taxonomy' => 'pa_storlek',
            'field' => 'slug',
            'terms' => $storek,
        );
        array_push($args["tax_query"], $storek_array);
    }
    if(!empty($taggar)){
        $taggar_array = array(
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => $taggar,
        );
        array_push($args["tax_query"], $taggar_array);
    }
    if(!empty($color)){
        $color_array = array(
            'taxonomy' => 'color',
            'field' => 'slug',
            'terms' => $color,
        );
        array_push($args["tax_query"], $color_array);
    }
    if(!empty($team)){
        $team_array = array(
            'taxonomy' => 'team',
            'field' => 'slug',
            'terms' => $team,
        );
        array_push($args["tax_query"], $team_array);
    }
    if(!empty($kategori)){
        $kategori_array = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $kategori,
        );
        array_push($args["tax_query"], $kategori_array);
    }

    if(!empty($_POST['searchText'])){
        $args['specific_chars'] = $_POST['searchText'];
    }

    $query = new WP_Query($args);
    
    $list_varumarke = array();
    $list_storek = array();
    $list_taggar = array();
    $list_color = array();
    $list_team = array();
    $list_categories = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
    
            $post_terms = wp_get_post_terms(get_the_ID(), 'varumarke'); // Замініть 'your_taxonomy' на вашу таксономію
            $product_attributes = wc_get_product_terms(get_the_ID(), 'pa_storlek');
            $product_taggar = wc_get_product_terms(get_the_ID(), 'product_tag');
            $product_color = wc_get_product_terms(get_the_ID(), 'color');
            $product_team = wc_get_product_terms(get_the_ID(), 'team');
            $product_cat = wc_get_product_terms(get_the_ID(), 'product_cat');


            foreach ($post_terms as $term) {
                if(is_array($varumarke)){
                    if(!in_array($term->slug, $varumarke)){
                        $list_varumarke[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else{
                    $list_varumarke[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
                
            }
            foreach ($product_attributes as $term) {
                if(is_array($storek)){
                    if(!in_array($term->slug, $storek)){
                        $list_storek[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else{
                    $list_storek[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
                
            }
            foreach ($product_taggar as $term) {
                if(is_array($taggar)){
                    if(!in_array($term->slug, $taggar)){
                        $list_taggar[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else {
                    $list_taggar[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
            }
            foreach ($product_team as $term) {
                if(is_array($team)){
                    if(!in_array($term->slug, $team)){
                        $list_team[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else {
                    $list_team[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
            }
            foreach ($product_color as $term) {
                if(is_array($color)){
                    if(!in_array($term->slug, $color)){
                        $list_color[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else {
                    $list_color[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
            }
            foreach ($product_cat as $term) {
                if(is_array($kategori)){
                    if(!in_array($term->slug, $kategori)){
                        $list_categories[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                    }
                } else{
                    $list_categories[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                }
            }
        }
        wp_reset_postdata();
    }

    if(!empty($kategori) && $kategori[0] != ''): 
        $term = get_term_by('slug', $kategori[0], 'product_cat');
        $cat_link = get_term_link($term);
    endif; ?>
    <div class="shopPage__filtersRow__listClose mobile-toggler refreshed"<?php if(!empty($kategori) && $kategori[0] != ''): ?> data-cat-link="<?php echo $cat_link; ?>"<?php endif; ?>></div>
    <?php 
    if ( (!empty($list_varumarke) && !is_wp_error( $list_varumarke )) || !empty($varumarke) ):
    ?>
        <div class="shopPage__filtersRow__listItem<?php if(!empty($openedItems) && is_array($openedItems) && in_array('varumarke',$openedItems)){ echo ' opened'; } ?>" data-attr-name="varumarke">
            <div class="shopPage__filtersRow__listItem__title">VARUMÄRKE</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($openedItems) && is_array($openedItems) && in_array('varumarke',$openedItems)){ echo '  style="display: block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($varumarke)): ?>
                        <?php foreach($varumarke as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'varumarke');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_varumarke as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php 
    if ( (!empty($list_storek) && !is_wp_error( $list_storek )) || !empty($storek) ):
    ?>
        <div class="shopPage__filtersRow__listItem<?php if(!empty($openedItems) && is_array($openedItems) && in_array('storek',$openedItems)){ echo ' opened'; } ?>" data-attr-name="storek">
            <div class="shopPage__filtersRow__listItem__title">STORLEK</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($openedItems) && is_array($openedItems) && in_array('storek',$openedItems)){ echo '  style="display: block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($storek)): ?>
                        <?php foreach($storek as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'pa_storlek');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_storek as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php 
    if ( (!empty($list_taggar) && !is_wp_error( $list_taggar )) || !empty($taggar) ):
    ?>
        <div class="shopPage__filtersRow__listItem<?php if(!empty($openedItems) && is_array($openedItems) && in_array('taggar',$openedItems)){ echo ' opened'; } ?>" data-attr-name="taggar">
            <div class="shopPage__filtersRow__listItem__title">TAGGAR</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($openedItems) && is_array($openedItems) && in_array('taggar',$openedItems)){ echo '  style="display: block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($taggar)): ?>
                        <?php foreach($taggar as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'product_tag');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_taggar as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php 
    if ( (!empty($list_color) && !is_wp_error( $list_color )) || !empty($color) ):
    ?>
        <div class="shopPage__filtersRow__listItem<?php if(!empty($openedItems) && is_array($openedItems) && in_array('color',$openedItems)){ echo ' opened'; } ?>" data-attr-name="color">
            <div class="shopPage__filtersRow__listItem__title">FÄRG</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($openedItems) && is_array($openedItems) && in_array('color',$openedItems)){ echo ' style="display:block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($color)): ?>
                        <?php foreach($color as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'color');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_color as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php 
    if ( (!empty($list_team) && !is_wp_error( $list_team )) || !empty($team) ):
    ?>
        <div class="shopPage__filtersRow__listItem<?php if(!empty($openedItems) && is_array($openedItems) && in_array('team',$openedItems)){ echo ' opened'; } ?>" data-attr-name="team">
            <div class="shopPage__filtersRow__listItem__title">TEAM</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($openedItems) && is_array($openedItems) && in_array('team',$openedItems)){ echo ' style="display:block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($team)): ?>
                        <?php foreach($team as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'team');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_team as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php 
    if ( (!empty($list_categories) && !is_wp_error( $list_categories )) || !empty($kategori) ):
    ?>
        <div class="shopPage__filtersRow__listItem category__item<?php if(!empty($openedItems) && is_array($openedItems) && in_array('kategori',$openedItems)){ echo ' opened'; } ?>" data-attr-name="kategori">
            <div class="shopPage__filtersRow__listItem__title">KATEGORI</div>
            <div class="shopPage__filtersRow__listItem__sublist"<?php if(!empty($openedItems) && is_array($openedItems) && in_array('kategori',$openedItems)){ echo ' style="display:block;"'; } ?>>
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($kategori) && $kategori[0] != ''): ?>
                        <?php foreach($kategori as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'product_cat');

                            $parent_term_slug = '';
                            if ($full_term->parent != 0) {
                                $parent_term = get_term($full_term->parent, 'product_cat');
                                $parent_term_slug = $parent_term->slug;
                            }
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>"<?php if(!empty($parent_term_slug)): ?> data-parent="<?php echo $parent_term_slug; ?>"<?php endif; ?>>
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_categories as $term): ?>
                        <?php 
                        $full_term = get_term_by('slug', $term['slug'], 'product_cat');

                        $parent_term_slug = '';
                        if ($full_term->parent != 0) {
                            $parent_term = get_term($full_term->parent, 'product_cat');
                            $parent_term_slug = $parent_term->slug;
                        }
                        ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>"<?php if(!empty($parent_term_slug)): ?> data-parent="<?php echo $parent_term_slug; ?>"<?php endif; ?>>
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="shopPage__filtersRow__list__apply">
        <div class="btn button--black">APPLY</div>
    </div>
    <div class="shopPage__filtersRow__list__clear<?php if($clear){ echo ' show'; } ?>">Rensa filter</div>
    
    <?php die();
}


//Update Breadcrumbs 
add_action('wp_ajax_nopriv_breadcrumbs_changing', 'breadcrumbs_changing');
add_action('wp_ajax_breadcrumbs_changing', 'breadcrumbs_changing');
function breadcrumbs_changing() { ?>
    <div class="updated">

    <?php if(!empty($_POST['kategori'])){ 
        $taxonomy = 'product_cat'; 
        $categories = implode(',', $_POST['kategori']);
            
        $term_id = get_term_by( 'slug', $categories, $taxonomy );

        $term_parents = get_term_parents_list($term_id->term_id, $taxonomy);

        echo '<a href="' . get_home_url() . '">Hem</a> / ' . $term_parents;     
    }
    elseif(!empty($_POST['team'])){ 
        $taxonomy = 'team'; 
        $categories = implode(',', $_POST['team']);
            
        $term_id = get_term_by( 'slug', $categories, $taxonomy );

        $term_parents = get_term_parents_list($term_id->term_id, $taxonomy);

        echo '<a href="' . get_home_url() . '">Hem</a> / ' . $term_parents;     
    }
    elseif(!empty($_POST['farg'])){ 
        $taxonomy = 'color'; 
        $categories = implode(',', $_POST['farg']);
            
        $term_id = get_term_by( 'slug', $categories, $taxonomy );

        $term_parents = get_term_parents_list($term_id->term_id, $taxonomy);

        echo '<a href="' . get_home_url() . '">Hem</a> / ' . $term_parents;     
    }
    elseif(!empty($_POST['tag'])){ 
        $taxonomy = 'product_tag'; 
        $categories = implode(',', $_POST['tag']);
            
        $term_id = get_term_by( 'slug', $categories, $taxonomy );

        $term_parents = get_term_parents_list($term_id->term_id, $taxonomy);

        echo '<a href="' . get_home_url() . '">Hem</a> / ' . $term_parents;     
    }
    elseif(!empty($_POST['storlek'])){ 
        $taxonomy = 'pa_storlek'; 
        $categories = implode(',', $_POST['storlek']);
            
        $term_id = get_term_by( 'slug', $categories, $taxonomy );

        $term_parents = get_term_parents_list($term_id->term_id, $taxonomy);

        echo '<a href="' . get_home_url() . '">Hem</a> / ' . $term_parents;     
    }
    elseif(!empty($_POST['varumarke'])){ 
        $taxonomy = 'varumarke'; 
        $categories = implode(',', $_POST['varumarke']);
            
        $term_id = get_term_by( 'slug', $categories, $taxonomy );

        $term_parents = get_term_parents_list($term_id->term_id, $taxonomy);

        echo '<a href="' . get_home_url() . '">Hem</a> / ' . _e('Varumärken', 'custom_woocommerce_text') . ' / ' . $term_parents;     
    } else {


        echo '<a href="' . get_home_url() . '">Hem</a> / <a href="' . wc_get_page_permalink( 'shop' ) . '">Webbutik</a>'; 
    } ?>
    </div>
    <?php 
    die();
}

//Ajax Search Brands
add_action('wp_ajax_nopriv_search_brands', 'search_brands');
add_action('wp_ajax_search_brands', 'search_brands');

function search_brands(){

    $args = array(
        'taxonomy' => 'varumarke',
        'hide_empty' => true,
        'parent' => 0,
        'orderby' => 'name',
    );


    if(isset($_POST['search_query'])){
        $search_query = sanitize_text_field(trim($_POST['search_query']));

        // if search_query isn't empty, show taxonomies by this word
        if(!empty($search_query)){
            $args['name__like'] = $search_query;
        }
    }

    $terms = get_terms($args);

    if(!empty($terms) && !is_wp_error($terms)){
        $response = array();
        foreach ($terms as $term) {
            $term_link = get_term_link($term);
            $term_name = $term->name;
            $term_description = $term->description;
            $color = get_field('select_color', $term);
            $logo = get_field('logo', $term);

            $term_data = array(
                'name' => $term_name,
                'url' => esc_url($term_link),
                'description' => $term_description,
                'color' => $color,
                'logo' => $logo ? $logo['url'] : ''
            );

            array_push($response, $term_data);
        }

        wp_send_json_success($response);
    } else {
        wp_send_json_error('No brands found');
    }
    die();
}

//Ajax Search teams
add_action('wp_ajax_nopriv_search_teams', 'search_teams');
add_action('wp_ajax_search_teams', 'search_teams');

function search_teams() {
    $search_query = isset($_POST['search_query']) ? sanitize_text_field($_POST['search_query']) : '';
    $list = get_field('leagues-list', get_field('teams_page','option'));

    //    error_log('List: ' . print_r($list, true));

    $response = array();

    foreach ($list as $item) {
        $category = $item['category'];
        $terms_array = array();

        // Looking for child terms, if there is a search request
        if (!empty($search_query)) {
            $terms = get_terms(array(
                'taxonomy' => 'team',
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => true,
                'child_of' => $category->term_id,
                'name__like' => $search_query
            ));

            foreach ($terms as $term) {
                $terms_array[] = array(
                    'name' => $term->name,
                    'url' => get_term_link($term),
                    'logo' => get_field('taxonomy-image', $term)['url'],
                    // 'logo' => wp_get_attachment_url(get_term_meta($term->term_id, 'thumbnail_id', true))
                );
            }
        }

        $response[] = array(
            'custom_name' => $item['custom_name'],
            'child_terms' => $terms_array
        );
    }

    wp_send_json_success($response);
}

add_action('wp_ajax_nopriv_get_initial_teams_content', 'get_initial_teams_content');
add_action('wp_ajax_get_initial_teams_content', 'get_initial_teams_content');

function get_initial_teams_content() {
    ob_start();

    $list = get_field('leagues-list', 44997);

    if ($list) {
        echo '<ul class="row mt-3">';

        $i = 1;
        foreach ($list as $item) {
            $category = $item['category'];
            $name = $item['custom_name'] ? $item['custom_name'] : $category->name;
            $id = $category->term_id;

            echo '<li class="tabsNav__item col">';
            echo '<a class="tabs__nav text-uppercase js-tab-nav '. ($i == 1 ? 'active' : '') .'" href="#tab' . $i . '">';
            echo '<h6>' . esc_html($name) . '</h6>';
            echo '</a>';
            echo '</li>';

            $i++;
        }

        echo '</ul>';
        echo '<ul class="row">';

        $c = 1;
        foreach ($list as $item) {
            $category = $item['category'];

            $terms = get_terms(array(
                'taxonomy' => 'product_cat',
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => true,
                'child_of' => $category->term_id
            ));

            if (!empty($terms) && !is_wp_error($terms)) {
                echo '<li id="tab' . $c . '" class="tabs__item col '. ($c == 1 ? 'active' : '') .'">';
                echo '<div class="tabs__item-inner bg-color bg-color--white">';
                echo '<ul class="row mx-0 tabs__item-list">';
                foreach ($terms as $term) { 
                    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                    $image = wp_get_attachment_url($thumbnail_id);
                    $name = $term->name;

                    echo '<li class="col-4 col-md-3 col-lg-2 py-2 tabs__item-child-item">';
                    echo '<a href="'. esc_url(get_term_link($term)) .'" class="product-cat__item-link">';

                    if ($image) {
                        echo '<img class="teams-page__logo mb-3" src="'. $image .'" alt="'. esc_attr($name) . ' logo" />';
                    }

                    echo '<div>'. esc_html($name) .'</div>';
                    echo '</a>';
                    echo '</li>';
                }

                echo '</ul>';
                echo '</div>';
                echo '</li>';

                $c++;
            }
        }

        echo '</ul>';
    }

    $content = ob_get_clean();

    wp_send_json_success($content);

    wp_die();
}


add_action('wp_ajax_nopriv_topContentChange', 'topContentChange');
add_action('wp_ajax_topContentChange', 'topContentChange');

function topContentChange() {
    $category_slug = $_POST['topContentCategory'];
    if(empty($category_slug)) {
        $page_id = get_option( 'woocommerce_shop_page_id' ); ;
        $page_content = get_post_field( 'post_content', $page_id );
        $content = '<h2>' . get_post_field( 'post_title', $page_id ) . '</h2>' . $page_content;
    } else {
        $full_term = get_term_by('slug', $category_slug, 'product_cat');
        $content = wpautop($full_term->description);
    }


    
    echo $content;
    wp_die();
}
