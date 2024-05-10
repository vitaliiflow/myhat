<?php 
add_action('woocommerce_product_add_to_cart', 'woocommerce_template_single_add_to_cart', 30);




add_filter('term_link', 'remove_product_category_slug', 10, 3);

function remove_product_category_slug($url, $term, $taxonomy) {
    if ($taxonomy === 'product_cat') {
        $url = str_replace('/product-category', '', $url);
    }
    return $url;
}

//Sale in percents
add_action( 'woocommerce_sale_flash', 'sale_badge_percentage', 25 );
 
function sale_badge_percentage() {
   global $product;
   if ( ! $product->is_on_sale() ) return;
   if ( $product->is_type( 'simple' ) ) {
      $max_percentage = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;
   } elseif ( $product->is_type( 'variable' ) ) {
      $max_percentage = 0;
      foreach ( $product->get_children() as $child_id ) {
         $variation = wc_get_product( $child_id );
         $price = $variation->get_regular_price();
         $sale = $variation->get_sale_price();
         if ( $price != 0 && ! empty( $sale ) ) $percentage = ( $price - $sale ) / $price * 100;
         if ( $percentage > $max_percentage ) {
            $max_percentage = $percentage;
         }
      }
   }
   if ( $max_percentage > 0 ) echo "<span class='onsale shopPage__listItem__badge'>-" . round($max_percentage) . "%</span>"; 
}


//Sold Products Count
function dw_product_totals() {
   global $wpdb;

   $post = get_post(get_the_ID());

   $current_product = get_the_ID($post);

   $order_items = apply_filters('woocommerce_reports_top_earners_order_items', $wpdb->get_results("
       SELECT order_item_meta_2.meta_value as product_id, SUM(order_item_meta.meta_value) as total_quantity
       FROM {$wpdb->prefix}woocommerce_order_items as order_items
       LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
       LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id
       LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
       WHERE posts.post_type = 'shop_order'
       AND posts.post_status IN ('" . implode("','", array('wc-completed', 'wc-processing', 'wc-on-hold')) . "')
       AND order_items.order_item_type = 'line_item'
       AND order_item_meta.meta_key = '_qty'
       AND order_item_meta_2.meta_key = '_product_id'
       AND posts.post_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
       GROUP BY order_item_meta_2.meta_value
   "));

   $current = array($current_product);

   foreach ($order_items as $item) {
       if (in_array($item->product_id, $current)) {
           $total_quantity = $item->total_quantity;
       }
   }
   if(empty($total_quantity)){
      $total_quantity = 0;
   }

   $total_quantity = number_format($total_quantity, 0, '.', ''); 

   return $total_quantity;
}


//Change Default Products Ordering 
function custom_default_catalog_orderby($sortby) {
   return 'date';
}
add_filter('woocommerce_default_catalog_orderby', 'custom_default_catalog_orderby');




add_filter('woocommerce_resize_images', static function() {
   return false;
});

// Add JSON-LD markup for WooCommerce product
add_action('wp_head', 'add_product_json_ld');
function add_product_json_ld() {
    if (is_product()) {
        global $product;
        $product_id = $product->get_id();
        $product_name = $product->get_name();
        $product_image_url = wp_get_attachment_url($product->get_image_id());
        $product_price = $product->get_price();
        $product_rating = round(4.5 + ($product->get_id() / 100000) , 2);
		if ($product_rating > 4.9) {
			$product_rating = 4.9;
		}
        $review_count = ceil($product->get_id() / 10000);
		$availability = $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
		$price_valid_until = date('Y-m-d', strtotime('+3 months'));

        // Get the selected country from WooCommerce settings
        $store_country = get_option( 'woocommerce_default_country' );

        // Split the country value into country code and name
        $store_country_parts = explode( ':', $store_country );

        // Extract the country code and name
        $store_country_code = isset( $store_country_parts[0] ) ? trim( $store_country_parts[0] ) : '';


        // Fields from theme settings

        $returnDays = get_field('merchant_return_days','option');
        $shippingMaxValue = get_field('shipping_max_value','option');
        $shippingMinValue = get_field('shipping_min_value','option');
        $addressCountry = get_field('addressCountry','option');
		
        if ($product_image_url) {
            $json_ld_markup = '
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Product",
                "@id": "' . esc_url(get_permalink($product_id)) . '",
                "name": "' . esc_attr($product_name) . '",
                "image": "' . esc_url($product_image_url) . '",
                "offers": {
                    "@type": "Offer",
                    "priceCurrency": "' . get_option('woocommerce_currency') .'",
                    "price": "' . esc_attr($product_price) . '",
					"priceValidUntil": "' . esc_attr($price_valid_until) . '",
					"availability": "' . esc_url($availability) . '",
                    "hasMerchantReturnPolicy": {
                        "@type": "MerchantReturnPolicy",
                        "applicableCountry": "' . $store_country_code . '",
                        "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
                        "merchantReturnDays":' . $returnDays . ',
                        "returnMethod": "https://schema.org/ReturnByMail",
                        "returnFees": "https://schema.org/FreeReturn"
                    },
                    "shippingDetails": {
                        "@type": "OfferShippingDetails",
                        "shippingRate": {
                            "@type": "MonetaryAmount",
                            "maxValue": "' . esc_attr($shippingMaxValue) . '",
                            "minValue": "' . esc_attr($shippingMinValue) . '",
                            "currency": "' . get_option('woocommerce_currency') .'"
                        },
                        "shippingDestination": {
                            "@type": "DefinedRegion",
                            "addressCountry": ' . $addressCountry . '
                        }
                    }
                },
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "' . esc_attr($product_rating) . '",
                    "reviewCount": "' . esc_attr($review_count) . '"
                }
            }
            </script>';
            
            echo $json_ld_markup;
        }
    }
}

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