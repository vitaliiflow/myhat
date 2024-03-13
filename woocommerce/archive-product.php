<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */

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
            <!-- <div class="shopPage__top">
                <div class="shopPage__breadcrumbs"><?php do_action( 'woocommerce_before_main_content' ); ?></div></div>
                <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                    <h1 class="shopPage__title"><?php woocommerce_page_title(); ?></h1>
                <?php endif; ?>
                <?php 
                    $page_id = get_option( 'woocommerce_shop_page_id' ); ;
                    $page_content = get_post_field( 'post_content', $page_id );
                ?>
                <div class="shopPage__text"><?php echo wpautop($page_content); ?></div>
            </div> -->
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

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $current_term = get_queried_object();

            if(!empty($current_term->slug)):
                $term_id = $current_term->slug;
            endif;
            if(!empty($current_term->taxonomy)):
                $taxonomy_slug = $current_term->taxonomy;
            endif;
            if(isset($_GET['orderby'])):
                switch($_GET['orderby']):
                    case 'popularity':
                        $orderby = 'popularity';
                        $order = 'ASC';
                        $metaKey = '';
                        break;
                    case 'rating':
                        $orderby = 'meta_value_num';
                        $metaKey = '_wc_average_rating';
                        $order = 'ASC';
                        break;
                    case 'date':
                        $orderby = 'publish_date';
                        $order = 'DESC';
                        $metaKey = '';
                        break;
                    case 'price':
                        $orderby = 'meta_value_num';
                        $metaKey = '_price';
                        $order = 'ASC';
                        break;
                    case 'price-desc':
                        $orderby = 'meta_value_num';
                        $metaKey = '_price';
                        $order = 'DESC';
                        break;
                endswitch;
                $settedOrder = $_GET['orderby'];
            else:
                $orderby = 'publish_date';
                $metaKey = '';
                $order = 'DESC';
                $settedOrder = 'date';
            endif;


            $args = array(
                'post_type' => 'product',
                'post_status'    => array( 'publish' ),
                'posts_per_page' => 16,
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
                'tax_query' => array(),
            );

            if(!empty($searchText)){
                $args['s'] = $searchText;
            }

            if(!empty($metaKey)){
                $args['meta_key'] = $metaKey;
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

                if($taxonomy_slug == 'pa_storlek' && (sizeof($storek) > 1 || $storek[0] != '')){
                    $term_id = array_merge( $term_id, $storek );
                }
                if($taxonomy_slug == 'pa_storlek'){
                    $storek = $term_id;
                }

                if($taxonomy_slug == 'product_tag' && (sizeof($taggar) > 1 || $taggar[0] != '')){
                    $term_id = array_merge( $term_id, $taggar );
                }
                if($taxonomy_slug == 'product_tag'){
                    $taggar = $term_id;
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
            if((sizeof($storek) > 1 || $storek[0] != '') && $taxonomy_slug != 'pa_storlek'){
                $storek__arr = array(
                    'taxonomy' => 'pa_storlek', 
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
            if((sizeof($kategori) > 1 || $kategori[0] != '') && $taxonomy_slug != 'product_cat'){
                $kategori_arr = array(
                    'taxonomy' => 'product_cat', 
                    'field' => 'slug',
                    'terms' => $kategori 
                );
                array_push($args["tax_query"], $kategori_arr);
            }
            $the_query = new WP_Query($args);
            if ( $the_query->have_posts() ) { ?>
                <?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
                <?php do_action( 'woocommerce_before_shop_loop' ); ?>
                <div class="shopPage__list" data-paged="<?php echo $paged; ?>" data-sort="<?php echo $settedOrder; ?>"<?php if(sizeof($varumarke) > 1 || $varumarke[0] != ''): ?> data-varumarke="<?php echo implode(',', $varumarke); ?>"<?php endif; ?><?php if(sizeof($storek) > 1 || $storek[0] != ''): ?> data-storek="<?php echo implode(',', $storek); ?>"<?php endif; ?><?php if(sizeof($taggar) > 1 || $taggar[0] != ''): ?> data-taggar="<?php echo implode(',', $taggar); ?>"<?php endif; ?><?php if(sizeof($kategori) > 1 || $kategori[0] != ''): ?> data-kategori="<?php echo implode(',', $kategori); ?>"<?php endif; ?><?php if($searchText): ?> data-search="<?php echo $searchText; ?>"<?php endif; ?>>
                    <?php 
                    	woocommerce_product_loop_start();

                        if ( wc_get_loop_prop( 'total' ) ) {
                            while ( $the_query->have_posts() ) {
                                $the_query->the_post();
                    
                                /**
                                 * Hook: woocommerce_shop_loop.
                                 */
                                do_action( 'woocommerce_shop_loop' );
                                ?>

                                <div class="shopPage__listItem col-6 col-md-3">

                                    <?php wc_get_template_part( 'content', 'product' ); ?>

                                </div>

                                <?php 
                            }
                        }
                    
                        woocommerce_product_loop_end();
                    	do_action( 'woocommerce_after_shop_loop' );
                    ?>
                </div>
            <?php } else{ ?>
                <div class="shopPage__empty"><?php do_action( 'woocommerce_no_products_found' ); ?></div>
            <?php } ?>
            <?php do_action( 'woocommerce_after_main_content' ); ?>
        </div>
    </div>
</div>


<?php 
get_footer( 'shop' );

