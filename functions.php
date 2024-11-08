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


// function add_custom_intervals($schedules) {
//     $schedules['minute'] = array(
//         'interval' => 60,
//         'display' => __('Once Every Minute')
//     );
//     return $schedules;
// }
// add_filter('cron_schedules', 'add_custom_intervals');



// function register_cron_for_update_products() {
//     if ( ! wp_next_scheduled( 'update_wc_products_event' ) ) {
//         wp_schedule_event( time(), 'minute', 'update_wc_products_event' );
//     }
// }
// add_action( 'wp', 'register_cron_for_update_products' );

// function update_wc_products_batch() {
//     $args = array(
//         'post_type'      => 'product',
//         'posts_per_page' => 10,
//         'orderby'        => 'modified',
//         'order'          => 'ASC',
//         'fields'         => 'ids',
//     );

//     $products = get_posts( $args );

//     if ( empty( $products ) ) {
//         error_log( 'No product to update.' );
//         return;
//     }

//     foreach ( $products as $product_id ) {
//         $product = wc_get_product( $product_id );

//         if ( ! $product ) {
//             error_log( "Can't load product with ID $product_id" );
//             continue;
//         }

//         $product->set_date_modified( current_time( 'timestamp' ) );

//         $product->save();

//         error_log( "Product with ID $product_id updated" );
//     }
// }
// add_action( 'update_wc_products_event', 'update_wc_products_batch' );

// function remove_cron_for_update_products() {
//     $timestamp = wp_next_scheduled( 'update_wc_products_event' );
//     wp_unschedule_event( $timestamp, 'update_wc_products_event' );
// }
// register_deactivation_hook( __FILE__, 'remove_cron_for_update_products' );
