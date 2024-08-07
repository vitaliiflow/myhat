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
        $id = get_the_ID();
        $product = wc_get_product($id);
        $product_id = $product->get_id();
        $product_name = $product->get_name();
        $product_image_url = wp_get_attachment_url($product->get_image_id());

        /* Handle product pricing */
		
		$product_price = '';
		
        if ($product->is_type('variable')) {
            // Get all variations including those that are out of stock
            $variations = $product->get_children();
            $prices = [];

            foreach ($variations as $variation_id) {
                $variation_obj = new WC_Product_Variation($variation_id);

                // Check for regular and sale prices
                $regular_price = $variation_obj->get_regular_price();
                $sale_price = $variation_obj->get_sale_price();

                if (!$variation_obj->is_in_stock()) {
                    if ($regular_price) {
                        $prices[] = $regular_price;
                    }
                } else {
                    if ($sale_price) {
                        $prices[] = $sale_price;
                    } elseif ($regular_price) {
                        $prices[] = $regular_price;
                    }
                }
            }

            if (!empty($prices)) {
                $product_price = min($prices);
            } else {
                $product_price = 'Price not available';
            }
        } else {
            // Get regular price if the product is out of stock
            if (!$product->is_in_stock()) {
                $product_price = $product->get_regular_price();
            } else {
                $product_price = $product->get_price();
            }
        }
		
		/* End of handle product price */

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

        $delivery_handling_min = get_field('delivery_handling_min', 'option');
        $delivery_handling_max = get_field('delivery_handling_max', 'option');
        $delivery_transit_min = get_field('delivery_transit_min', 'option');
        $delivery_transit_max = get_field('delivery_transit_max', 'option');
		
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
                        },
                        "deliveryTime": {
                            "@type": "ShippingDeliveryTime",
                            "handlingTime": {
                              "@type": "QuantitativeValue",
                              "minValue": ' . $delivery_handling_min . ',
                              "maxValue": ' . $delivery_handling_max . ',
                              "unitCode": "d" // "d" stands for day(s)
                            },
                            "transitTime": {
                              "@type": "QuantitativeValue",
                              "minValue": ' . $delivery_transit_min . ',
                              "maxValue": ' . $delivery_transit_max . ',
                              "unitCode": "d" // "d" stands for day(s)
                            }   
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

    // Check if the product is valid
    if ( ! $product ) {
        return false;
    }

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
        // Product is a simple product, check if it's in stock
        return $product->is_in_stock();
    }
}


function add_shipping_note_on_order_creation( $order_id ) {
	
	$order = wc_get_order( $order_id );
	$products = $order->get_items();
	foreach($products as $product):
		 // Get the meta data of the item
		$item_meta_data = $product->get_meta_data();

		$isCustomProduct = 0;

		// Check if the custom information exists in the meta data
		foreach ($item_meta_data as $meta) {
			if ($meta->key === '_fpd_data') {
				$custom_info = $meta->value;

				// Proceed with custom information logic if the necessary keys exist
				$fpd_data = json_decode(stripslashes($custom_info), true);

				if (isset($fpd_data['product']) && $fpd_data['product']) {
					$myhat_products = $fpd_data['product'];

					foreach ($myhat_products as $myhat_product) {
						$elements = $myhat_product['elements'];

						if (isset($myhat_product['elements'])) {

							foreach($elements as $element) {
								$parameters = $element['parameters'];
								if (isset($parameters)) {
									if (isset($parameters['_initialText'])) {
										$isCustomProduct++;
									} elseif (isset($parameters['originParams'])) {
										$url = $parameters['originParams']['source'];

										// Define the pattern to search for
										$patternUploads = '/fancy_products_uploads/';
										$patterncloudfront = '/cloudfront\.net/';
										$patternForProductAssets = '/fpd-product/';

										// Use preg_match to search for the pattern
										if (preg_match($patternUploads, $url)) {
											$isCustomProduct++;
										} elseif (preg_match($patterncloudfront, $url)) {
											$isCustomProduct++;
										} elseif (!preg_match($patternForProductAssets, $url)) {
											$isCustomProduct++;
										}
									}
								}
							}
						}
					}
				}
			}
		}
	
	
	endforeach; 
	if($isCustomProduct > 0):
		$order = wc_get_order( $order_id );
		$note = "Customization: Yes" . 'order note:';
		$order->add_order_note( $note );
	endif;
}
add_action( 'woocommerce_thankyou', 'add_shipping_note_on_order_creation', 10, 1 );



// function custom_admin_order_page_styles($order_id) {

// 	global $pagenow, $post;
//     if ($pagenow == 'post.php' && isset($post) && $post->post_type == 'shop_order') {
//         $order_id = $post->ID;
// 		$order = wc_get_order( $order_id );
// 		if(!empty($order)):
// 			$products = $order->get_items();
// 			foreach($products as $product):
// 				 // Get the meta data of the item
// 				$item_meta_data = $product->get_meta_data();

// 				$isCustomProduct = 0;

// 				// Check if the custom information exists in the meta data
// 				foreach ($item_meta_data as $meta) {
// 					if ($meta->key === '_fpd_data') {
// 						$custom_info = $meta->value;

// 						// Proceed with custom information logic if the necessary keys exist
// 						$fpd_data = json_decode(stripslashes($custom_info), true);

// 						if (isset($fpd_data['product']) && $fpd_data['product']) {
// 							$myhat_products = $fpd_data['product'];

// 							foreach ($myhat_products as $myhat_product) {
// 								$elements = $myhat_product['elements'];

// 								if (isset($myhat_product['elements'])) {

// 									foreach($elements as $element) {
// 										$parameters = $element['parameters'];
// 										if (isset($parameters)) {
// 											if (isset($parameters['_initialText'])) {
// 												$isCustomProduct++;
// 											} elseif (isset($parameters['originParams'])) {
// 												$url = $parameters['originParams']['source'];

// 												// Define the pattern to search for
// 												$patternUploads = '/fancy_products_uploads/';
// 												$patterncloudfront = '/cloudfront\.net/';
// 												$patternForProductAssets = '/fpd-product/';

// 												// Use preg_match to search for the pattern
// 												if (preg_match($patternUploads, $url)) {
// 													$isCustomProduct++;
// 												} elseif (preg_match($patterncloudfront, $url)) {
// 													$isCustomProduct++;
// 												} elseif (!preg_match($patternForProductAssets, $url)) {
// 													$isCustomProduct++;
// 												}
// 											}
// 										}
// 									}
// 								}
// 							}
// 						}
// 					}
// 				}


// 			endforeach; 
// 			if($isCustomProduct > 0):
// 				echo '<style>
// 					#order_data .order_data_column p.order_note::after {
// 						content: ", Customization: Yes";
// 					}    
// 				</style>';
// 			endif;
// 		endif;
// 	}
// }
// add_action('admin_head', 'custom_admin_order_page_styles');



add_action('woocommerce_checkout_update_order_meta', 'custom_update_order_comment_based_on_customization', 10, 2);

function custom_update_order_comment_based_on_customization($order_id, $posted_data) {
    $order = wc_get_order($order_id);

    $isCustomProduct = 0;

    $products = $order->get_items();
    foreach($products as $product) {
        $item_meta_data = $product->get_meta_data();

        foreach ($item_meta_data as $meta) {
            if ($meta->key === '_fpd_data') {
                $custom_info = $meta->value;

                $fpd_data = json_decode(stripslashes($custom_info), true);

                if (isset($fpd_data['product']) && $fpd_data['product']) {
                    $myhat_products = $fpd_data['product'];

                    foreach ($myhat_products as $myhat_product) {
                        $elements = $myhat_product['elements'];

                        if (isset($myhat_product['elements'])) {
                            foreach($elements as $element) {
                                $parameters = $element['parameters'];
                                if (isset($parameters)) {
                                    if (isset($parameters['_initialText'])) {
                                        $isCustomProduct++;
                                    } elseif (isset($parameters['originParams'])) {
                                        $url = $parameters['originParams']['source'];

                                        $patternUploads = '/fancy_products_uploads/';
                                        $patterncloudfront = '/cloudfront\.net/';
                                        $patternForProductAssets = '/fpd-product/';

                                        if (preg_match($patternUploads, $url)) {
                                            $isCustomProduct++;
                                        } elseif (preg_match($patterncloudfront, $url)) {
                                            $isCustomProduct++;
                                        } elseif (!preg_match($patternForProductAssets, $url)) {
                                            $isCustomProduct++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    $order_comment = $order->get_customer_note();
	$separator = ''; 
	if(!empty($order_comment)){
		$separator = ' - ';
	}
    if ($isCustomProduct > 0) {
        $new_comment = $order_comment . $separator . 'Customization: Yes';
        $order->set_customer_note( $new_comment );
        $order->save();
    }
}


/* Remove Yoast SEO Prev/Next URL from all pages
 * Credit: Yoast Team
 * Last Tested: Jun 10 2017 using Yoast SEO 4.9 on WordPress 4.8
 */

 add_filter( 'wpseo_next_rel_link', '__return_false' );
 add_filter( 'wpseo_prev_rel_link', '__return_false' );



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

// Display customization badge on cart page
add_filter('woocommerce_cart_item_name', 'display_customization_badge_on_cart_page', 10, 2);
function display_customization_badge_on_cart_page($product_name, $cart_item) {
    if (isset($cart_item['fpd_data']['fpd_print_order'])) {
        //$fpd_print_order = json_decode($cart_item['fpd_data']['fpd_print_order'], true);
		$fpd_data = json_decode(stripslashes($cart_item['fpd_data']['fpd_print_order']), true);
        //$product_name .= ' <span class="customization-badge" style="display: none;">' . $fpd_data .'</span>';
        if (isset($fpd_data['custom_images']) && !empty($fpd_data['custom_images'])) {
            ob_start();
            get_template_part('woocommerce/parts/customization-badge');
            $badge = ob_get_clean();
            $product_name .= ' ' . $badge;
        }
    }
    return $product_name;
}
