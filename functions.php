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
