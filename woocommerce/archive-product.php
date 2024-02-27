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
<div class="shopPage">
    <div class="container">
        <div class="shopPage__content">
            <div class="shopPage__top">
                <div class="shopPage__breadcrumbs"><?php do_action( 'woocommerce_before_main_content' ); ?></div></div>
                <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                    <h1 class="shopPage__title"><?php woocommerce_page_title(); ?></h1>
                <?php endif; ?>
                <?php 
                    $page_id = get_option( 'woocommerce_shop_page_id' ); ;
                    $page_content = get_post_field( 'post_content', $page_id );
                ?>
                <div class="shopPage__text"><?php echo wpautop($page_content); ?></div>
            </div>
            <?php if ( woocommerce_product_loop() ) { ?>
                <div class="shopPage__filters">
                    <?php do_action( 'woocommerce_before_shop_loop' ); ?>
                </div>
                <div class="shopPage__list">
                    <?php 
                    	woocommerce_product_loop_start();

                        if ( wc_get_loop_prop( 'total' ) ) {
                            while ( have_posts() ) {
                                the_post();
                    
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
$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}
$link = explode('%#%', $base);
$prev = $current - 1;
$next = $current + 1;
?>
<div class="pagination-test">
    <div class="prev">prev</div>
    <div class="items">
        <span><?php echo $current ?></span>
        <span>/</span>
        <span><?php echo $total; ?></span>
    </div>
    <div class="next">next</div>
</div>

<?php 
get_footer( 'shop' );
