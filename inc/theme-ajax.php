<?php 
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
                    's'     => $searchText,
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
                's'     => $searchText,
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

    $search = $_POST['searchText'];
    
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
        $args['s'] = $search;
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
            <div class="shopPage__paginationButton prev<?php if($paged == 1){ echo ' disabled';} ?>"><?php echo get_inline_svg('pagination-arrow-right.svg'); ?>Föregående</div>
            <div class="shopPage__paginationPage">
                <span class="current"><?php echo $paged; ?></span>
                <span>/</span>
                <span class="total"><?php echo $the_query->max_num_pages; ?></span>
            </div>
            <div class="shopPage__paginationButton next<?php if($the_query->max_num_pages <= 1 || $paged == $the_query->max_num_pages){echo ' disabled';} ?>">Nästa<?php echo get_inline_svg('pagination-arrow-right.svg'); ?></div>
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

    $searchText = $_POST['searchText'];

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
        $args['s'] = $searchText;
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






//Change filters
add_action('wp_ajax_nopriv_changing_filters', 'changing_filters');
add_action('wp_ajax_changing_filters', 'changing_filters');
function changing_filters() {
    $varumarke = '';
    $storek = '';
    $taggar = '';
    $kategori = '';
    if(!empty($_POST['varumarke'])):
        $varumarke = $_POST['varumarke'];
    endif;
    if(!empty($_POST['storek'])):
        $storek = $_POST['storek'];
    endif;
    if(!empty($_POST['taggar'])):
        $taggar = $_POST['taggar'];
    endif;
    if(!empty($_POST['kategori'])):
        $kategori = $_POST['kategori'];
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
    if(!empty($kategori)){
        $kategori_array = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $kategori,
        );
        array_push($args["tax_query"], $kategori_array);
    }

    $query = new WP_Query($args);
    
    $list_varumarke = array();
    $list_storek = array();
    $list_taggar = array();
    $list_categories = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
    
            $post_terms = wp_get_post_terms(get_the_ID(), 'varumarke'); // Замініть 'your_taxonomy' на вашу таксономію
            $product_attributes = wc_get_product_terms(get_the_ID(), 'pa_storlek');
            $product_taggar = wc_get_product_terms(get_the_ID(), 'product_tag');
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
    <div class="shopPage__filtersRow__listClose mobile-toggler"></div>
    <?php 
    if ( (!empty($list_varumarke) && !is_wp_error( $list_varumarke )) || !empty($varumarke) ):
    ?>
        <div class="shopPage__filtersRow__listItem opened" data-attr-name="varumarke">
            <div class="shopPage__filtersRow__listItem__title">VARUMÄRKE</div>
            <div class="shopPage__filtersRow__listItem__sublist" style="display: block;">
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
        <div class="shopPage__filtersRow__listItem" data-attr-name="storek">
            <div class="shopPage__filtersRow__listItem__title">STORLEK</div>
            <div class="shopPage__filtersRow__listItem__sublist">
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
        <div class="shopPage__filtersRow__listItem" data-attr-name="taggar">
            <div class="shopPage__filtersRow__listItem__title">TAGGAR</div>
            <div class="shopPage__filtersRow__listItem__sublist">
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
    if ( !empty($list_categories) && !is_wp_error( $list_categories ) ):
    ?>
        <div class="shopPage__filtersRow__listItem" data-attr-name="kategori">
            <div class="shopPage__filtersRow__listItem__title">KATEGORI</div>
            <div class="shopPage__filtersRow__listItem__sublist">
                <div class="shopPage__filtersRow__listItem__sublistItems">
                    <?php if(!empty($kategori)): ?>
                        <?php foreach($kategori as $term): ?>
                            <?php 
                            $full_term = get_term_by('slug', $term, 'product_cat');
                            ?>
                            <div class="shopPage__filtersRow__listItem__sublistItem active" data-slug="<?php echo $term; ?>">
                                <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $full_term->name; ?></div>
                                <div class="shopPage__filtersRow__listItem__sublistItem__description"><?php echo category_description($full_term->term_id); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php foreach($list_categories as $term): ?>
                        <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term['slug']; ?>">
                            <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term['name']; ?></div>
                            <div class="shopPage__filtersRow__listItem__sublistItem__description"><?php echo category_description($term['id']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="shopPage__filtersRow__list__apply">
        <div class="btn button--black">APPLY</div>
    </div>

    
    <?php die();
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
                'taxonomy' => 'product_cat',
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
                    'logo' => wp_get_attachment_url(get_term_meta($term->term_id, 'thumbnail_id', true))
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
