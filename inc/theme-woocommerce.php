<?php 
add_filter('term_link', 'remove_product_category_slug', 10, 3);

function remove_product_category_slug($url, $term, $taxonomy) {
    if ($taxonomy === 'product_cat') {
        $url = str_replace('/product-category', '', $url);
    }
    return $url;
}