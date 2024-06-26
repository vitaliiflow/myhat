<?php

require get_template_directory().'/inc/theme-setup.php';
require get_template_directory().'/inc/theme-support.php';
require get_template_directory().'/inc/theme-enqueue.php';

require get_template_directory().'/inc/custom-post-types.php';
require get_template_directory().'/inc/custom-taxonomies.php';

require get_template_directory().'/inc/acf.php';
require get_template_directory().'/inc/theme-functions.php';

require get_template_directory().'/inc/custom-functions-from-old-theme.php';
require get_template_directory().'/inc/theme-ajax.php';
require get_template_directory().'/inc/theme-woocommerce.php';

require get_template_directory().'/inc/theme-optimization.php';
require get_template_directory().'/inc/theme-permalinks.php'; // Redirects for products and product cat


function filter_posts_by_title( $where, &$wp_query ) {
    global $wpdb;
    if ( $specific_chars = $wp_query->get( 'specific_chars' ) ) {
        $where .= $wpdb->prepare( " AND $wpdb->posts.post_title LIKE %s", '%' . $wpdb->esc_like( $specific_chars ) . '%' );
    }
    return $where;
}
add_filter( 'posts_where', 'filter_posts_by_title', 10, 2 );


function custom_excerpt_length( $length ) {
    return 16;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * @snippet       Remove Sidebar @ Single Product Page
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=19572
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.2.6
 */
 
 add_action( 'wp', 'bbloomer_remove_sidebar_product_pages' );
 
 function bbloomer_remove_sidebar_product_pages() {
 if ( is_product() ) {
 remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
 }
 }


add_filter('woocommerce_breadcrumb_defaults', 'custom_woocommerce_breadcrumbs');

function custom_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => ' <span class="separator">/</span> ', // Вкажіть ваш новий сепаратор
        'wrap_before' => '<nav class="woocommerce-breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '<span class="woocommerce-breadcrumbs-item">',
        'after'       => '</span>',
        'home'        => _x('Home', 'breadcrumb', 'woocommerce'),
    );
}
