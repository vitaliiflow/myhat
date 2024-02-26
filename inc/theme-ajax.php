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
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 16,
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
                $args['order'] = 'DESC';
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
            <div class="shopPage__listItem col-6 col-md-3">
                <?php wc_get_template_part( 'content', 'product' ); ?>
            </div>
        <?php endwhile;
    endif;
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
    $list = get_field('leagues-list', 44997);

    //    error_log('List: ' . print_r($list, true));

    $response = array();

    foreach ($list as $item) {
        $category = $item['category'];
        $terms_array = array();

        // Ищем дочерние термины только если есть поисковый запрос
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
                    'logo' => get_term_meta($term->term_id, 'thumbnail_id', true)
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
                        echo '<img class="teams-page__logo mb-3" src="'. esc_url($image) .'" alt="'. esc_attr($name) . ' logo" />';
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
