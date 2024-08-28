<?php get_header();
$searchText=get_search_query();
?>
<div class="shopPage__top">
    <div class="container">
        <div class="shopPage__breadcrumbs"><?php do_action( 'woocommerce_before_main_content' ); ?></div></div>
    </div>
</div>
<?php get_template_part('template-parts/acf-blocks/block-seo-text'); ?>
<div class="shopPage">
    <div class="container">
        <div class="shopPage__content">
        <?php
            if(!empty($_GET['varumarke_cat'])):
                $varumarke = explode(',', $_GET['varumarke_cat']);
            else:
                $varumarke = array('');
            endif;
            if(!empty($_GET['storek'])):
                $storek = explode(',', $_GET['storek']);
            else:
                $storek = array('');
            endif;
            if(!empty($_GET['taggar'])):
                $taggar = explode(',', $_GET['taggar']);
            else:
                $taggar = array('');
            endif;
            if(!empty($_GET['team'])):
                $team = explode(',', $_GET['team']);
            else:
                $team = array('');
            endif;
            if(!empty($_GET['color'])):
                $color = explode(',', $_GET['color']);
            else:
                $color = array('');
            endif;
            if(!empty($_GET['kategori'])):
                $kategori = explode(',', $_GET['kategori']);
            else:
                $kategori = array('');
            endif;

            $current_term = get_queried_object();

            if(!empty($current_term->slug)):
                $term_id = $current_term->slug;
            endif;
            if(!empty($current_term->taxonomy)):
                $taxonomy_slug = $current_term->taxonomy;
            endif;



            $args = array(
                'post_type' => 'product',
                'post_status'    => array( 'publish' ),
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => '_stock_status',
                        'value'   => 'instock',
                        'compare' => '=',
                    ),
                ),
                'tax_query' => array(),
            );


            if(!empty($_GET['s'])){
                $args['specific_chars'] = $_GET['s'];
            }

            if(!empty($term_id) && !empty($taxonomy_slug)) {
                if(gettype($term_id) == 'string'){
                    $term_id = [$term_id];
                }

                if($taxonomy_slug == 'varumarke' && (sizeof($varumarke) > 1 || $varumarke[0] != '')){
                    $term_id = array_merge( $term_id, $varumarke );
                }
                if($taxonomy_slug == 'varumarke'){
                    $varumarke = $term_id;
                }

                if(get_locale() == "sv_SE"){
                    $size_name = "pa_storlek";
                } elseif(get_locale() == "nb_NO") {
                    $size_name = "pa_storrelse";
                } elseif(get_locale() == 'de_DE'){
                    $size_name = "pa_groesse";
                } elseif(get_locale() == 'da_DK'){
                    $size_name = "pa_stoerrelse";
                } elseif(get_locale() == 'fi'){
                    $size_name = "pa_koko";
                }

                if($taxonomy_slug == $size_name && (sizeof($storek) > 1 || $storek[0] != '')){
                    $term_id = array_merge( $term_id, $storek );
                }
                if($taxonomy_slug == $size_name){
                    $storek = $term_id;
                }

                if($taxonomy_slug == 'product_tag' && (sizeof($taggar) > 1 || $taggar[0] != '')){
                    $term_id = array_merge( $term_id, $taggar );
                }
                if($taxonomy_slug == 'product_tag'){
                    $taggar = $term_id;
                }
                
                if($taxonomy_slug == 'color' && (sizeof($color) > 1 || $color[0] != '')){
                    $term_id = array_merge( $term_id, $color );
                }
                if($taxonomy_slug == 'color'){
                    $color = $term_id;
                }

                if($taxonomy_slug == 'team' && (sizeof($team) > 1 || $team[0] != '')){
                    $term_id = array_merge( $term_id, $team );
                }
                if($taxonomy_slug == 'team'){
                    $team = $term_id;
                }

                if($taxonomy_slug == 'product_cat' && (sizeof($kategori) > 1 || $kategori[0] != '')){
                    $term_id = array_merge( $term_id, $kategori );
                }
                if($taxonomy_slug == 'product_cat'){
                    $kategori = $term_id;
                }


                $tax_array = array(
                    'taxonomy' => $taxonomy_slug, 
                    'field' => 'slug',
                    'terms' => $term_id 
                );


                array_push($args["tax_query"], $tax_array);
            }


            if((sizeof($varumarke) > 1 || $varumarke[0] != '') && $taxonomy_slug != 'varumarke'){
                $varumarke__arr = array(
                    'taxonomy' => 'varumarke', 
                    'field' => 'slug',
                    'terms' => $varumarke 
                );
                array_push($args["tax_query"], $varumarke__arr);
            }
            if((sizeof($storek) > 1 || $storek[0] != '') && $taxonomy_slug != $size_name){
                $storek__arr = array(
                    'taxonomy' => $size_name, 
                    'field' => 'slug',
                    'terms' => $storek 
                );
                array_push($args["tax_query"], $storek__arr);
            }
            if((sizeof($taggar) > 1 || $taggar[0] != '') && $taxonomy_slug != 'product_tag'){
                $taggar__arr = array(
                    'taxonomy' => 'product_tag', 
                    'field' => 'slug',
                    'terms' => $taggar 
                );
                array_push($args["tax_query"], $taggar__arr);
            }
            if((sizeof($color) > 1 || $color[0] != '') && $taxonomy_slug != 'color'){
                $color__arr = array(
                    'taxonomy' => 'color', 
                    'field' => 'slug',
                    'terms' => $color 
                );
                array_push($args["tax_query"], $color__arr);
            }
            if((sizeof($team) > 1 || $team[0] != '') && $taxonomy_slug != 'team'){
                $team__arr = array(
                    'taxonomy' => 'team', 
                    'field' => 'slug',
                    'terms' => $team 
                );
                array_push($args["tax_query"], $team__arr);
            }
            if((sizeof($kategori) > 1 || $kategori[0] != '') && $taxonomy_slug != 'product_cat'){
                $kategori_arr = array(
                    'taxonomy' => 'product_cat', 
                    'field' => 'slug',
                    'terms' => $kategori 
                );
                array_push($args["tax_query"], $kategori_arr);
            }
            $the_query = new WP_Query($args);
                
            $list_varumarke = array();
            $list_storek = array();
            $list_taggar = array();
            $list_color = array();
            $list_team = array();
            $list_categories = array();
                
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();

                    $post_terms = wp_get_post_terms(get_the_ID(), 'varumarke'); // Замініть 'your_taxonomy' на вашу таксономію
                    $product_attributes = wc_get_product_terms(get_the_ID(), $size_name);
                    $product_taggar = wc_get_product_terms(get_the_ID(), 'product_tag');
                    $product_color = wc_get_product_terms(get_the_ID(), 'color');
                    $product_team = wc_get_product_terms(get_the_ID(), 'team');
                    $product_cat = wc_get_product_terms(get_the_ID(), 'product_cat');

                    foreach ($post_terms as $term) {
                        if(!in_array($term->slug, $varumarke)){
                            $list_varumarke[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                        }
                    }
                    foreach ($product_attributes as $term) {
                        if(!in_array($term->slug, $storek)){
                            $list_storek[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                        }
                    }
                    foreach ($product_taggar as $term) {
                        if(!in_array($term->slug, $taggar)){
                            $list_taggar[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                        }
                    }
                    foreach ($product_color as $term) {
                        if(!in_array($term->slug, $color)){
                            $list_color[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                        }
                    }
                    foreach ($product_team as $term) {
                        if(!in_array($term->slug, $team)){
                            $list_team[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                        }
                    }
                    foreach ($product_cat as $term) {
                        if(!in_array($term->slug, $kategori)){
                            $list_categories[$term->term_id] = array('name' => $term->name, 'slug' => $term->slug, 'id' => $term->term_id);
                        }
                    }
                }
                wp_reset_postdata();
            }

            if(empty($_GET['orderby'])){
                $_GET['orderby'] = 'date';
            }

            ?>
            <div class="shopPage__filters sort-wrapper desktop-lg">
                <div class="shopPage__filtersRow">  
                    <div class="shopPage__filtersRow__pillsList">
                    </div>
                    <div class="shopPage__filtersRow__item sort">
                        <div class="shopPage__filtersRow__itemTitle mobile-toggler"><span>Sortering</span></div>
                        <div class="shopPage__filtersRow__itemOverlay mobile-toggler"></div>
                        <div class="shopPage__filtersRow__listWrapper">
                            <div class="shopPage__filtersRow__listClose mobile-toggler"></div>
                            <div class="shopPage__filtersRow__list">
                                <div class="shopPage__filtersRow__listItem<?php if(!isset($_GET['orderby']) || $_GET['orderby'] == 'date'){echo ' active';} ?>">
                                    <div class="shopPage__filtersRow__listItem__checkbox"></div>
                                    <div class="shopPage__filtersRow__listItem__name" data-slug="date"><?php _e('Sortera efter senast', 'woocommerce'); ?></div>
                                </div>
                                <div class="shopPage__filtersRow__listItem<?php if($_GET['orderby'] == 'popularity'){echo ' active';} ?>">
                                    <div class="shopPage__filtersRow__listItem__checkbox"></div>
                                    <div class="shopPage__filtersRow__listItem__name" data-slug="popularity"><?php _e('Sortera efter popularitet', 'woocommerce'); ?></div>
                                </div>
                                <div class="shopPage__filtersRow__listItem<?php if($_GET['orderby'] == 'rating'){echo ' active';} ?>">
                                    <div class="shopPage__filtersRow__listItem__checkbox"></div>
                                    <div class="shopPage__filtersRow__listItem__name" data-slug="rating"><?php _e('Sortera efter genomsnittligt betyg', 'woocommerce'); ?></div>
                                </div>
                                <div class="shopPage__filtersRow__listItem<?php if($_GET['orderby'] == 'price'){echo ' active';} ?>">
                                    <div class="shopPage__filtersRow__listItem__checkbox"></div>
                                    <div class="shopPage__filtersRow__listItem__name" data-slug="price"><?php _e('Sortera efter pris: lågt till högt', 'woocommerce'); ?></div>
                                </div>
                                <div class="shopPage__filtersRow__listItem<?php if($_GET['orderby'] == 'price-desc'){echo ' active';} ?>">
                                    <div class="shopPage__filtersRow__listItem__checkbox"></div>
                                    <div class="shopPage__filtersRow__listItem__name" data-slug="price-desc"><?php _e('Sortera efter pris: högt till lågt', 'woocommerce'); ?></div>
                                </div>
                            </div>
                            <div class="shopPage__filtersRow__list__apply button button--black mobile-toggler"><?php _e('APPLY', 'woocommerce'); ?></div>
                            <div class="shopPage__filtersRow__itemClose mobile-toggle"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shopPage__filters filters-wrapper desktop-lg">
                <div class="shopPage__filtersRow">
                    <div class="shopPage__filtersRow__item filter">
                        <div class="shopPage__filtersRow__itemTitle mobile-toggler">
                            <span><img src="<?php echo get_template_directory_uri(  ) ?>/assets/images/icons/settings.png" alt="">Filter</span>
                            <div class="shopPage__filtersRow__itemTitle__emptyContent">
                                <?php 
                                $page_id = get_option( 'woocommerce_shop_page_id' ); ;
                                $page_content = get_post_field( 'post_content', $page_id );
                                $content = '<h2>' . get_post_field( 'post_title', $page_id ) . '</h2>' . $page_content;
                                echo $content;
                                ?>
                            </div>
                        </div>
                        <div class="shopPage__filtersRow__itemOverlay mobile-toggler"></div>
                        <div class="shopPage__filtersRow__listWrapper">
                            <div class="shopPage__filtersRow__listClose mobile-toggler refreshed"></div>
                            <?php 
                            if ( (!empty($list_varumarke) && !is_wp_error( $list_varumarke )) || (sizeof($varumarke) > 1 || $varumarke[0] != '') ):
                            ?>
                                <div class="shopPage__filtersRow__listItem" data-attr-name="varumarke">
                                    <div class="shopPage__filtersRow__listItem__title">VARUMÄRKE</div>
                                    <div class="shopPage__filtersRow__listItem__sublist">
                                        <div class="shopPage__filtersRow__listItem__sublistItems">
                                            <?php if((sizeof($varumarke) > 1 || $varumarke[0] != '')): ?>
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
                            if ( (!empty($list_storek) && !is_wp_error( $list_storek )) || (sizeof($storek) > 1 || $storek[0] != '') ):
                            ?>
                                <div class="shopPage__filtersRow__listItem" data-attr-name="storek">
                                    <div class="shopPage__filtersRow__listItem__title">STORLEK</div>
                                    <div class="shopPage__filtersRow__listItem__sublist">
                                        <div class="shopPage__filtersRow__listItem__sublistItems">
                                            <?php if((sizeof($storek) > 1 || $storek[0] != '')): ?>
                                                <?php foreach($storek as $term): ?>
                                                    <?php 
                                                    $full_term = get_term_by('slug', $term, $size_name);
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
                            if ( (!empty($list_taggar) && !is_wp_error( $list_taggar )) || (sizeof($taggar) > 1 || $taggar[0] != '') ):
                            ?>
                                <div class="shopPage__filtersRow__listItem" data-attr-name="taggar">
                                    <div class="shopPage__filtersRow__listItem__title">TAGGAR</div>
                                    <div class="shopPage__filtersRow__listItem__sublist">
                                        <div class="shopPage__filtersRow__listItem__sublistItems">
                                            <?php if((sizeof($taggar) > 1 || $taggar[0] != '')): ?>
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
                            if ( (!empty($list_color) && !is_wp_error( $list_color )) || (sizeof($color) > 1 || $color[0] != '') ):
                            ?>
                                <div class="shopPage__filtersRow__listItem" data-attr-name="color">
                                    <div class="shopPage__filtersRow__listItem__title">FÄRG</div>
                                    <div class="shopPage__filtersRow__listItem__sublist">
                                        <div class="shopPage__filtersRow__listItem__sublistItems">
                                            <?php if((sizeof($color) > 1 || $color[0] != '')): ?>
                                                <?php foreach($color as $term): ?>
                                                    <?php 
                                                    $full_term = get_term_by('slug', $term, 'product_cat');
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
                            if ( (!empty($list_team) && !is_wp_error( $list_team )) || (sizeof($team) > 1 || $team[0] != '') ):
                            ?>
                                <div class="shopPage__filtersRow__listItem" data-attr-name="team">
                                    <div class="shopPage__filtersRow__listItem__title">TEAM</div>
                                    <div class="shopPage__filtersRow__listItem__sublist">
                                        <div class="shopPage__filtersRow__listItem__sublistItems">
                                            <?php if((sizeof($team) > 1 || $team[0] != '')): ?>
                                                <?php foreach($team as $term): ?>
                                                    <?php 
                                                    $full_term = get_term_by('slug', $term, 'product_cat');
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
                            if ( (!empty($list_categories) && !is_wp_error( $list_categories )) || (sizeof($kategori) > 1 || $kategori[0] != '') ):
                            ?>
                                <div class="shopPage__filtersRow__listItem" data-attr-name="kategori">
                                    <div class="shopPage__filtersRow__listItem__title"><?php _e('KATEGORI', 'woocommerce_custom_text'); ?></div>
                                    <div class="shopPage__filtersRow__listItem__sublist">
                                        <div class="shopPage__filtersRow__listItem__sublistItems">
                                            <?php if((sizeof($kategori) > 1 || $kategori[0] != '')): ?>
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
                            <div class="shopPage__filtersRow__list__clear">Rensa filter</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 

            if(!empty($_GET['s'])):
                $searchText = $_GET['s'];
            endif;
            if(!empty($_GET['varumarke_cat'])):
                $varumarke = explode(',', $_GET['varumarke_cat']);
            else:
                $varumarke = array('');
            endif;
            if(!empty($_GET['storek'])):
                $storek = explode(',', $_GET['storek']);
            else:
                $storek = array('');
            endif;
            if(!empty($_GET['taggar'])):
                $taggar = explode(',', $_GET['taggar']);
            else:
                $taggar = array('');
            endif;
            if(!empty($_GET['kategori'])):
                $kategori = explode(',', $_GET['kategori']);
            else:
                $kategori = array('');
            endif;

            $orderby = 'publish_date';
            $order = 'DESC';
            $settedOrder = 'date';

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            
            $args = array(
                'post_type' => 'product',
                'post_status'    => array( 'publish' ),
                'posts_per_page' => 16,
                'specific_chars'     => $searchText,
                'paged' => $paged,
                'orderby' => $orderby,
                'order' => $order,
                'meta_query' => array(
                    array(
                        'key'     => '_stock_status',
                        'value'   => 'instock',
                        'compare' => '=',
                    ),
                ),
            );


            $the_query = new WP_Query($args); ?>
            <?php if($the_query->have_posts()): ?>
                <div class="shopPage__list" data-paged="<?php echo $paged; ?>" data-sort="<?php echo $settedOrder; ?>"<?php if(sizeof($varumarke) > 1 || $varumarke[0] != ''): ?> data-varumarke="<?php echo implode(',', $varumarke); ?>"<?php endif; ?><?php if(sizeof($storek) > 1 || $storek[0] != ''): ?> data-storek="<?php echo implode(',', $storek); ?>"<?php endif; ?><?php if(sizeof($taggar) > 1 || $taggar[0] != ''): ?> data-taggar="<?php echo implode(',', $taggar); ?>"<?php endif; ?><?php if(sizeof($kategori) > 1 || $kategori[0] != ''): ?> data-kategori="<?php echo implode(',', $kategori); ?>"<?php endif; ?><?php if($searchText): ?> data-search="<?php echo $searchText; ?>"<?php endif; ?>>
                    <ul class="products column-4">
                        <?php while($the_query->have_posts()): $the_query->the_post(); ?>
                            <div class="shopPage__listItem col-6 col-md-3">
                                <?php wc_get_template_part( 'content', 'product' ); ?>
                            </div>
                        <?php endwhile;?> 
                    </ul>
                    <div class="shopPage__pagination">
                        <a href="#" rel="previous" class="shopPage__paginationButton prev<?php if($paged == 1){ echo ' disabled';} ?>"><?php echo get_inline_svg('pagination-arrow-right.svg'); ?>Föregående</a>
                        <div class="shopPage__paginationPage">
                            <span class="current"><?php echo $paged ?></span>
                            <span>/</span>
                            <span class="total"><?php echo $the_query->max_num_pages; ?></span>
                        </div>
                        <a href="#" rel="next" class="shopPage__paginationButton next<?php if($the_query->max_num_pages <= 1 || $paged == $the_query->max_num_pages){echo ' disabled';} ?>">Nästa<?php echo get_inline_svg('pagination-arrow-right.svg'); ?></a>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-products-message">
                    <h4>Nothing was found</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>



<?php get_footer(); ?>