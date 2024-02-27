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
    echo $current_page = max( 1, get_query_var( 'paged' ) );
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 16,
        'meta_query'     => array(
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
        ),
    );
    $sort = $_POST['sortType'];
    if(!empty($sort)):
        switch($sort):
            case 'popularity':
                $args['orderby'] = 'popularity';
                $args['order'] = 'DESC';
                break;
            case 'rating':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_wc_average_rating';
                $args['order'] = 'ASC';
                break;
            case 'date':
                $args['orderby'] = 'publish_date';
                $args['order'] = 'DESC';
                break;
            case 'price':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'ASC';
                break;
            case 'price-desc':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'DESC';
                break;
        endswitch;
    endif;
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


//Pagination
add_action('wp_ajax_nopriv_products_pagination', 'products_pagination');
add_action('wp_ajax_products_pagination', 'products_pagination');
function products_pagination() {
    $current_page = max( 1, get_query_var( 'paged' ) );
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 16,
        'paged' => $current_page + 1,
        'meta_query'     => array(
            array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            ),
        ),
    );
    $the_query = new WP_Query($args);
    echo $current_page;
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