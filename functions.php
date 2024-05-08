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

// Hook to display custom information in order item meta
add_action('woocommerce_before_order_itemmeta', 'display_custom_order_item_data', 10, 3);

function display_custom_order_item_data($item_id, $item, $product)
{
    // Get the meta data of the item
    $item_meta_data = $item->get_meta_data();
	
	if (!$item instanceof WC_Order_Item_Product) {
		return;
	}
	
    $customization = "No";

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
                                    $customization = "_initialText";
                                } elseif (isset($parameters['originParams'])) {
                                    $url = $parameters['originParams']['source'];

                                    // Define the pattern to search for
                                    $patternUploads = '/fancy_products_uploads/';
                                    $patterncloudfront = '/cloudfront\.net/';
                                    $patternForProductAssets = '/fpd-product/';

                                    // Use preg_match to search for the pattern
                                    if (preg_match($patternUploads, $url)) {
                                        $customization = "Yes";
                                    } elseif (preg_match($patterncloudfront, $url)) {
                                        $customization = "Yes";
                                    } elseif (!preg_match($patternForProductAssets, $url)) {
                                        $customization = "Yes";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    // Display the custom information below the SKU on the order edit screen
    echo '<div class="custom-order-item-data">';
    echo '<p>Customization: ' . $customization . '</p>';
    echo '</div>';
}


function set_order_note_function($display_element) {
    // Get the current order ID
    $order_id = WC()->session->get('order_awaiting_payment');
    
    // Check if order ID is valid
    if ($order_id > 0) {
        // Get the order object
        $order = wc_get_order($order_id);
        
        // Ensure the order object is valid
        if ($order && is_a($order, 'WC_Order')) {
            // Write your code here to set order note using $display_element
            // For example:
            $note = 'Custom note: ' . $display_element;
            $order->add_order_note($note);
        } else {
            // Handle case where order object is not valid
            error_log('Invalid order object');
        }
    } else {
        // Handle case where order ID is not valid
        error_log('Invalid order ID');
    }
}


// Add this code to your functions.php file

add_action('woocommerce_before_checkout_form', 'display_order_item_details');

function display_order_item_details() {
    // Check if it's the checkout page
    if (is_checkout()) {
        // Get the current order
        $order = WC()->cart->get_cart();

        // Display order item details
        if (!empty($order)) {
            foreach ($order as $item) {
				
// 				$item_meta_data = $item->get_meta_data();
				
				if ( isset($item['fpd_data'])) {
					

					
					$fpd_data = $item['fpd_data'];
					
					if( isset($fpd_data['fpd_product']) && $fpd_data['fpd_product'] ) {
						$order = json_decode(stripslashes($fpd_data['fpd_product']), true);
						
						
						
						if(isset($order['product'])) {

							$views = $order['product'];

							$display_elements = FPD_WC_Cart::get_display_elements($views);
							
							

							foreach($display_elements as $display_element) {
								
								if ($display_element) {
									var_dump($display_element);
								}
							}
						}

					}
					
				
					
				}
					
                
			}
    	}
	}
}


function action_woocommerce_new_order( $order_id ) {
    // Check if the order exists
    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }
	
	// Sanitize and validate customer note
    $customer_note = sanitize_text_field( $order->get_customer_note() );
	
    // Initialize custom note
    $custom_note = 'Initial note:';

    // Get order items
    $items = $order->get_items();
	
	$custom_note = '1'; 

    // Loop through order items
    foreach ( $items as $item_id => $item ) {
        // Output the item variable for debugging
//         var_dump($item);
		$allmeta = $item->get_meta_data();
                // Display order item details
        if (!empty($allmeta)) {
  					
				$custom_note = '22'; 
				
// 				$item_meta_data = $item->get_meta_data();
				
				if ( isset($allmeta['fpd_data'])) {
					
					$custom_note = '333'; 
					
					$fpd_data = $allmeta['fpd_data'];
					
					if( isset($fpd_data['fpd_product']) && $fpd_data['fpd_product'] ) {
						$order = json_decode(stripslashes($fpd_data['fpd_product']), true);
						
						$custom_note = '444'; 
						
						if(isset($order['product'])) {

							$views = $order['product'];

							$display_elements = FPD_WC_Cart::get_display_elements($views);
							
							

							foreach($display_elements as $display_element) {
								
								if ($display_element) {
									$custom_note = 'Customized product inside';
								}
							}
						}

					}
					
				
					
				}
					
                
			
    	}
    }
	
	// Combine notes
    if ( ! empty( $customer_note ) ) {
        $note = $customer_note . "\n\n" . $custom_note;
    } else {
        $note = $custom_note;
    }

    // Set note
    $order->set_customer_note( $note );

    // Save
    $order->save();
}
//add_action( 'woocommerce_new_order', 'action_woocommerce_new_order', 10, 1 );
//
function eaction_woocommerce_new_order( $order_id ) {
    // Get the order object
//     $order_id = '50121';
    $order = wc_get_order( $order_id );
	
	// Sanitize and validate customer note
    $customer_note = sanitize_text_field( $order->get_customer_note() );
	
    // Initialize custom note
    $custom_note = 'Initial note:';
	
	// Iterating through each WC_Order_Item_Product objects
	foreach ($order->get_items() as $item_key => $item ):

    ## Using WC_Order_Item methods ##

    // Item ID is directly accessible from the $item_key in the foreach loop or
    $item_id = $item->get_id();
	$item_data    = $item->get_data();
	error_log( 'Item ID: ' . $item_id );
	error_log( 'Item ID: ' . print_r( $item_data, true ) );
	
	
	$item_meta = $item_data['meta_data'];
	
	foreach ($item_meta as $meta) {
		if ($meta->key === '_fpd_data') {
			$fpd_data_json = $meta->value;
			// Now you can use $fpd_data as needed
			 // Proceed with custom information logic if the necessary keys exist
            $fpd_data = json_decode(stripslashes($fpd_data_json), true);
			
			error_log( '$fpd_data: ' . print_r( $fpd_data, true ) );

            if (isset($fpd_data['product']) && $fpd_data['product']) {
                $myhat_products = $fpd_data['product'];
				
				error_log( '$myhat_products: ' . print_r( $myhat_products, true ) );

                foreach ($myhat_products as $myhat_product) {
                    $elements = $myhat_product['elements'];

                    if (isset($myhat_product['elements'])) {
						
						error_log( 'elements: ' . print_r( $myhat_product['elements'], true ) );

                        foreach($elements as $element) {
                            $parameters = $element['parameters'];

                            if (isset($parameters)) {
								
								error_log( '$parameters: ' . print_r( $parameters, true ) );

                                if (isset($parameters['_initialText'])) {
                                    $customization = "_initialText";
                                } elseif (isset($parameters['originParams'])) {
                                    $url = $parameters['originParams']['source'];

                                    // Define the pattern to search for
                                    $patternUploads = '/fancy_products_uploads/';
                                    $patterncloudfront = '/cloudfront\.net/';
                                    $patternForProductAssets = '/fpd-product/';

                                    // Use preg_match to search for the pattern
                                    if (preg_match($patternUploads, $url)) {
                                        $custom_note = "This order contains customizable product";
                                    } elseif (preg_match($patterncloudfront, $url)) {
                                        $custom_note = "This order contains customizable product";
                                    } elseif (!preg_match($patternForProductAssets, $url)) {
                                        $custom_note = "This order contains customizable product";
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
	
	// Combine notes
    if ( ! empty( $customer_note ) ) {
        $note = $customer_note . "\n\n" . $custom_note;
    } else {
        $note = $custom_note;
    }

    // Set note
    $order->set_customer_note( $note );

    // Save
    $order->save();
	
}
add_action( 'woocommerce_new_order', 'eaction_woocommerce_new_order', 10, 1 );
