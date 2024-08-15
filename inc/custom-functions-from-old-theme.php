<?php

/**
 * This functions are just copy pasted. We should decrease this file to minimum scope and move functions to correct files
 * 
 */

/**
 * Trim zeros in price decimals
 **/
 add_filter( 'woocommerce_price_trim_zeros', '__return_true' );

//add_action( 'wp_footer', 'mzm_automatic_language_detector' );
/**
 * Country, Langage:
 * DE => German
 * SE => Sweden
 * FI => Finland
 * 
 */


// used like this in the WP All Import [soflyy_get_yoast_term_meta({Term ID}, "varumarke", "wpseo_title")]
function soflyy_get_yoast_term_meta( $term_id, $taxonomy = 'product_cat', $meta_key = '' ) {
	$yoast_tax_meta = get_option("wpseo_taxonomy_meta");
	if ( ! empty ( $yoast_tax_meta ) && isset( $yoast_tax_meta[$taxonomy] ) ) {
		$cat_meta = $yoast_tax_meta[$taxonomy];
		if ( ! empty ( $cat_meta ) && isset( $cat_meta[$term_id] ) ) {
			switch($meta_key) {
				case 'wpseo_title':
					return ($cat_meta[$term_id]['wpseo_title'] != '') ? $cat_meta[$term_id]['wpseo_title'] : '';
				case 'wpseo_desc':
					return ($cat_meta[$term_id]['wpseo_desc'] != '') ? $cat_meta[$term_id]['wpseo_desc'] : '';
				case 'wpseo_linkdex':
					return ($cat_meta[$term_id]['wpseo_linkdex'] != '') ? $cat_meta[$term_id]['wpseo_linkdex'] : '';
				case 'wpseo_content_score':
					return ($cat_meta[$term_id]['wpseo_content_score'] != '') ? $cat_meta[$term_id]['wpseo_content_score'] : '';
				case 'wpseo_keywordsynonyms':
					return ($cat_meta[$term_id]['wpseo_keywordsynonyms'] != '') ? $cat_meta[$term_id]['wpseo_keywordsynonyms'] : '';
				case 'wpseo_focuskw':
					return ($cat_meta[$term_id]['wpseo_focuskw'] != '') ? $cat_meta[$term_id]['wpseo_focuskw'] : '';
				default:
					return '';
			}	
		}
	} else {
		return '';
	}
}


//From multisite functions.php
//
/**
 * Trim zeros in price decimals
 **/
 add_filter( 'woocommerce_price_trim_zeros', '__return_true' );


add_action( 'pmxi_saved_post', 'my_saved_post', 10, 3 );
function my_saved_post( $post_id, $xml_node, $is_update ) {
    global $wpdb;
    if ( term_exists( $post_id ) ) {
        $term_description = term_description( $post_id );
        $term_description = html_entity_decode( $term_description );
        $wpdb->update( $wpdb->term_taxonomy, array( 'description' => $term_description ), array( 'term_id' => $post_id ) );
    } else {
        $post = get_post( $post_id, ARRAY_A );
        if ( ! empty( $post ) ) {
            $content = html_entity_decode( $post['post_content'] );
            $content = str_ireplace( '</ h2>', '</h2>', $content );
            $content = str_ireplace( ' _x000d_  n', '', $content );
            $content = str_ireplace( '_x000d_  n', '', $content );
            $content = str_ireplace( '_x000d_', '', $content );
            $content = str_ireplace( ' _ x000d_  n', '', $content );
            $content = str_ireplace( '_ x000d_  n', '', $content );
            $content = str_ireplace( '_ x000d_', '', $content );
            $content = str_ireplace( '#VALUE!', '', $content );
            $post['post_content'] = $content;
            wp_update_post( $post );
        }
    }
}

// used like this in the WP All Import [soflyy_get_yoast_term_meta({Term ID}, "varumarke", "wpseo_title")]
// function soflyy_get_yoast_term_meta( $term_id, $taxonomy = 'product_cat', $meta_key = '' ) {
// 	$yoast_tax_meta = get_option("wpseo_taxonomy_meta");
// 	if ( ! empty ( $yoast_tax_meta ) && isset( $yoast_tax_meta[$taxonomy] ) ) {
// 		$cat_meta = $yoast_tax_meta[$taxonomy];
// 		if ( ! empty ( $cat_meta ) && isset( $cat_meta[$term_id] ) ) {
// 			switch($meta_key) {
// 				case 'wpseo_title':
// 					return ($cat_meta[$term_id]['wpseo_title'] != '') ? $cat_meta[$term_id]['wpseo_title'] : '';
// 				case 'wpseo_desc':
// 					return ($cat_meta[$term_id]['wpseo_desc'] != '') ? $cat_meta[$term_id]['wpseo_desc'] : '';
// 				case 'wpseo_linkdex':
// 					return ($cat_meta[$term_id]['wpseo_linkdex'] != '') ? $cat_meta[$term_id]['wpseo_linkdex'] : '';
// 				case 'wpseo_content_score':
// 					return ($cat_meta[$term_id]['wpseo_content_score'] != '') ? $cat_meta[$term_id]['wpseo_content_score'] : '';
// 				case 'wpseo_keywordsynonyms':
// 					return ($cat_meta[$term_id]['wpseo_keywordsynonyms'] != '') ? $cat_meta[$term_id]['wpseo_keywordsynonyms'] : '';
// 				case 'wpseo_focuskw':
// 					return ($cat_meta[$term_id]['wpseo_focuskw'] != '') ? $cat_meta[$term_id]['wpseo_focuskw'] : '';
// 				default:
// 					return '';
// 			}	
// 		}
// 	} else {
// 		return '';
// 	}
// }

//used for wpall import to acceppt h1 tags
foreach ( array( 'pre_term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_filter_kses' );
}
 
foreach ( array( 'term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_kses_data' );
}

/**
 * Add a prefix (WBTRPR) to the order number
 */

if($blog_id == 2) {

add_filter('woocommerce_order_number', function($order_id) {
    return 'MY-FI-' . $order_id;
});
}
if($blog_id == 3) {

add_filter('woocommerce_order_number', function($order_id) {
    return 'MY-DE-' . $order_id;
});
}
if($blog_id == 6) {

add_filter('woocommerce_order_number', function($order_id) {
    return 'MY-NO-' . $order_id;
});
}
if($blog_id == 7) {

add_filter('woocommerce_order_number', function($order_id) {
    return 'MY-DK-' . $order_id;
});
}

//Autoremove sale items from sale category
if($blog_id == 2) {

 add_action( 'save_post_product', 'update_product_set_sale_cat' );
function update_product_set_sale_cat( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    if ( ! current_user_can( 'edit_product', $post_id ) ) {
        return $post_id;
    }
    if( get_post_status( $post_id ) == 'publish' && isset($_POST['_sale_price']) ) {
        $sale_price = $_POST['_sale_price'];

        if( $sale_price >= 0 && ! has_term( 'Lippalakki ale', 'product_cat', $post_id ) ){
            wp_set_object_terms($post_id, 'lippalakki-ale', 'product_cat', true );
        } elseif ( $sale_price == '' && has_term( 'Lippalakki ale', 'product_cat', $post_id ) ) {
            wp_remove_object_terms( $post_id, 'lippalakki-ale', 'product_cat' );
        }
    }
}
}
if($blog_id == 3) {

add_action( 'save_post_product', 'update_product_set_sale_cat' );
function update_product_set_sale_cat( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    if ( ! current_user_can( 'edit_product', $post_id ) ) {
        return $post_id;
    }
    if( get_post_status( $post_id ) == 'publish' && isset($_POST['_sale_price']) ) {
        $sale_price = $_POST['_sale_price'];

        if( $sale_price >= 0 && ! has_term( 'Caps Sale', 'product_cat', $post_id ) ){
            wp_set_object_terms($post_id, 'caps-sale', 'product_cat', true );
        } elseif ( $sale_price == '' && has_term( 'Caps Sale', 'product_cat', $post_id ) ) {
            wp_remove_object_terms( $post_id, 'caps-sale', 'product_cat' );
        }
    }
}
}

if($blog_id == 6) {

add_action( 'save_post_product', 'update_product_set_sale_cat' );
function update_product_set_sale_cat( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    if ( ! current_user_can( 'edit_product', $post_id ) ) {
        return $post_id;
    }
    if( get_post_status( $post_id ) == 'publish' && isset($_POST['_sale_price']) ) {
        $sale_price = $_POST['_sale_price'];

        if( $sale_price >= 0 && ! has_term( 'Capser på salg', 'product_cat', $post_id ) ){
            wp_set_object_terms($post_id, 'cap-salg', 'product_cat', true );
        } elseif ( $sale_price == '' && has_term( 'Capser på salg', 'product_cat', $post_id ) ) {
            wp_remove_object_terms( $post_id, 'cap-salg', 'product_cat' );
        }
    }
}
}

if($blog_id == 7) {

add_action( 'save_post_product', 'update_product_set_sale_cat' );
function update_product_set_sale_cat( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    if ( ! current_user_can( 'edit_product', $post_id ) ) {
        return $post_id;
    }
    if( get_post_status( $post_id ) == 'publish' && isset($_POST['_sale_price']) ) {
        $sale_price = $_POST['_sale_price'];

        if( $sale_price >= 0 && ! has_term( 'Udsalg', 'product_cat', $post_id ) ){
            wp_set_object_terms($post_id, 'kasket-udsalg', 'product_cat', true );
        } elseif ( $sale_price == '' && has_term( 'Udsalg', 'product_cat', $post_id ) ) {
            wp_remove_object_terms( $post_id, 'kasket-udsalg', 'product_cat' );
        }
    }
}
}

add_filter( 'woocommerce_adjust_non_base_location_prices', '__return_false' );

if($blog_id == 6) {
		add_filter( 'gettext', function ( $strings ) {
			/**
			 * Holding translations/changes.
			 * 'to translate' => 'the translation or rewording'
			 */
			$text = array(
				'Shopping cart' => 'Handlekurv',
				'Checkout details'   => 'Ordredetaljer',
				'Order complete'   => 'Ordre fullført',
				'No products added to the wishlist'   => 'Ingen produkter lagt til i ønskelisten din',
				'Product Name'   => 'Produktnavn',
			);

	$strings = str_ireplace( array_keys( $text ), $text, $strings );

	return $strings;
}, 20 );
}

