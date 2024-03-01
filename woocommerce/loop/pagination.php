<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}
$link = explode('%#%', $base);
$prev = $current - 1;
$next = $current + 1;


$args = array(
	'post_type' => 'product',
	'post_status'    => array( 'publish' ),
	'posts_per_page' => 16,
	'paged' => $current,
	'meta_query'     => array(
		array(
			'key'     => '_stock_status',
			'value'   => 'instock',
			'compare' => '=',
		),
	),
);
$the_query = new WP_Query($args);

?>
<div class="shopPage__pagination">
    <div class="shopPage__paginationButton prev<?php if($current == 1){ echo ' disabled';} ?>"><?php echo get_inline_svg('pagination-arrow-right.svg'); ?>Föregående</div>
    <div class="shopPage__paginationPage">
        <span class="current"><?php echo $current ?></span>
        <span>/</span>
        <span class="total"><?php echo $the_query->max_num_pages; ?></span>
    </div>
    <div class="shopPage__paginationButton next<?php if($the_query->max_num_pages <= 1 || $current == $the_query->max_num_pages){echo ' disabled';} ?>">Nästa<?php echo get_inline_svg('pagination-arrow-right.svg'); ?></div>
</div>
