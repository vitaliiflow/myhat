<?php 


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

