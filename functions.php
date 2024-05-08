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


/*
 * Remove a link from the Yoast SEO breadcrumbs
 * Credit: https://timersys.com/remove-link-yoast-breadcrumbs/
 * Last Tested: Mar 12 2017 using Yoast SEO 4.4 on WordPress 4.7.3
 */

add_filter( 'wpseo_breadcrumb_single_link' ,'wpseo_remove_breadcrumb_link', 10 ,2);

function wpseo_remove_breadcrumb_link( $link_output , $link ){
    $text_to_remove = 'Webbutik';
  
    if( $link['text'] == $text_to_remove ) {
      $link_output = '';
    }
 
    return $link_output;
}


// Redirect product category URLs with parent categories
function custom_redirect_product_category_urls() {
    if (is_product_category()) {
        $category = get_queried_object();
        $category_slug = $category->slug;
        $parent_categories = get_ancestors($category->term_id, 'product_cat');
        $parent_slugs = '';
        if (!empty($parent_categories)) {
            foreach ($parent_categories as $parent_category_id) {
                $parent_category = get_term($parent_category_id, 'product_cat');
                $parent_slugs .= $parent_category->slug . '/';
            }
        }
        $redirect_url = home_url('/' . $parent_slugs . $category_slug . '/');
        // Check if the current URL matches the destination URL
        $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (trailingslashit($current_url) != trailingslashit($redirect_url)) {
            wp_redirect($redirect_url, 301);
            exit();
        }
    }
}
add_action('template_redirect', 'custom_redirect_product_category_urls');




// Redirect product URLs with parent categories and categories
function custom_redirect_product_urls() {
    if (is_singular('product')) {
        global $post;
        $product_id = $post->ID;
        $product_categories = wp_get_post_terms($product_id, 'product_cat');
        
        // Check if the product is assigned to the 'uncategorized' category
        $uncategorized_term = get_term_by('slug', 'uncategorized', 'product_cat');
        $is_uncategorized = false;
        if (!empty($product_categories)) {
            foreach ($product_categories as $product_category) {
                if ($product_category->term_id === $uncategorized_term->term_id) {
                    $is_uncategorized = true;
                    break;
                }
            }
        }
        
        if (!$is_uncategorized) {
            $category_slugs = '';
            foreach ($product_categories as $product_category) {
                $category_slugs .= $product_category->slug . '/';
            }
            $redirect_url = home_url('/' . $category_slugs . $post->post_name . '/');
            // Check if the current URL matches the destination URL
            $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            // Check if the URL contains "?customize"
            if (strpos($current_url, '?customize') === false && trailingslashit($current_url) != trailingslashit($redirect_url)) {
                wp_redirect($redirect_url, 301);
                exit();
            }
        }
    }
}
add_action('template_redirect', 'custom_redirect_product_urls');

function is_any_variation_in_stock($product_id) {
    // Get the product object
    $product = wc_get_product($product_id);

    // Check if the product is a variable product
    if ($product->is_type('variable')) {
        // Get the product variations
        $variations = $product->get_available_variations();

        // Check if any variation is in stock
        foreach ($variations as $variation) {
            $variation_obj = wc_get_product($variation['variation_id']);
            if ($variation_obj->is_in_stock()) {
                return true; // Return true if any variation is in stock
            }
        }

        return false; // Return false if none of the variations are in stock
    } else {
        // Product is not a variable product
        return false;
    }
}

function dequeue_jquery_migrate( $scripts ) {
    if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            [ 'jquery-migrate' ]
        );
    }
}
add_action( 'wp_default_scripts', 'dequeue_jquery_migrate' );


// Removed because it was not working
// add_action( 'litespeed_optm', function() {
// //     $post_type = get_post_type();
//     if (!is_cart()) {
//         do_action( 'litespeed_conf_force', 'optm-ucss', false ); // Enable UCSS
//     } 
// });


add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );

function child_manage_woocommerce_styles() {
    //remove generator meta tag
    remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

    //first check that woo exists to prevent fatal errors
    if ( function_exists( 'is_woocommerce' ) ) {
        //dequeue scripts and styles
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }
 }

