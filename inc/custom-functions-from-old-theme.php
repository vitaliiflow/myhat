<?php

/**
 * This functions are just copy pasted. We should decrease this file to minimum scope and move functions to correct files
 * 
 */



//Add lagerplats to order edit pages
add_action( 'woocommerce_before_order_itemmeta', 'storage_location_of_order_items', 10, 3 );
function storage_location_of_order_items( $item_id, $item, $product ){
    // Only on backend order edit pages
    if( ! ( is_admin() && $item->is_type('line_item') ) ) return;

    // Get your ACF product value (replace the slug by yours below)
    if( $acf_value = get_field( 'lagerplats', $product->get_parent_id() ) ) {
        $acf_label = __('Lagerplats: ');

        // Outputing the value of the "location storage" for this product item
        echo '<p>' . $acf_label .  $acf_value . '<p>';
    }
}

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
function mzm_automatic_language_detector() {
    if ( ! is_admin() ) {
        if ( class_exists('WC_Geolocation') ) {
            $location = WC_Geolocation::geolocate_ip();
            if ( isset( $location['country'] ) ) {
                $country = $location['country'];
                $site_language = get_locale();
                switch ( $site_language ) {
                    case 'de_DE':
                        $site_language = 'DE';
                        break;
                    case 'sv_SE':
                        $site_language = 'SE';
                        break;
                    case 'fi':
                        $site_language = 'FI';
					case 'da_DK':
						break;
                        $site_language = 'DK';
                        break;
					case 'nb_NO':
                        $site_language = 'NO';
                        break;
                    default:
                        $site_language = 'SE';
                        break;
                }
                if ( $site_language != $country && ! isset( $_COOKIE['mzm_hide_language_switcher'] ) ) {
                    if ( in_array( $country, ['DE', 'SE', 'FI'] ) ) {
                        $sites = [
                            'DE' => [
                                'name' => 'German',
                                'link' => 'https://myhat.de/',
                            ],
                            'SE' => [
                                'name' => 'Swedish',
                                'link' => 'https://myhat.se/',
                            ],
                            'FI' => [
                                'name' => 'Finnish',
                                'link' => 'https://myhat.fi/',
                            ],
							'DK' => [
							    'name' => 'Danish',
                                'link' => 'https://myhat.dk/',
                            ],
							  'NO' => [
                                'name' => 'Norweigan',
                                'link' => 'https://myhat.no/',
                            ],
                        ];
                        $message = sprintf( __( 'You are showing the %s website, You can go to the %s website by click %s here %s', 'flatsome' ), $sites[ $site_language ]['name'], $sites[ $country ]['name'], '<a href="' . $sites[ $country ]['link'] . '">', '</a>' );
                        ?>
                        <div class="mzm-language-detector-overlay">
                            <div class="mzm-language-detector-container">
                                <a class="mzm-language-detector-button" href="#"></a>
                                <p><?php echo $message; ?></p>
                            </div>
                        </div>
                        <script>
                            jQuery(document).ready(function ($) {
                                setTimeout(() => {
                                    $('.mzm-language-detector-overlay').fadeIn('fast', function () {
                                        $('html').addClass('mzm-language-detector-opened');
                                    });
                                }, 1000);
                                $('.mzm-language-detector-button').click(function (e) { 
                                    e.preventDefault();
                                    $('.mzm-language-detector-overlay').fadeOut('fast', function () {
                                        $('html').removeClass('mzm-language-detector-opened');
                                        set_cookie('mzm_hide_language_switcher', 'hide', 7);
                                    });
                                });
                            });

                            function set_cookie( cname, cvalue, exdays ) {
                                const d = new Date();
                                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                                let expires = "expires="+ d.toUTCString();
                                document.cookie = cname + "=" + cvalue + ";" + expires;
                            }
                        </script>
                        <?php
                    }
                }
                // if ( isset($_GET['mzm']) ) {
                //     var_dump( $location );
                //     var_dump( $site_language );
                // }
            }
        }
    }


//Autoremove items from sale categorie
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

        if( $sale_price >= 0 && ! has_term( 'Keps Rea', 'product_cat', $post_id ) ){
            wp_set_object_terms($post_id, 'keps-rea', 'product_cat', true );
        } elseif ( $sale_price == '' && has_term( 'Keps Rea', 'product_cat', $post_id ) ) {
            wp_remove_object_terms( $post_id, 'keps-rea', 'product_cat' );
        }
    }
}

}

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
//Autoremove keps från kepsrea gruppen
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

        if( $sale_price >= 0 && ! has_term( 'Keps Rea', 'product_cat', $post_id ) ){
            wp_set_object_terms($post_id, 'keps-rea', 'product_cat', true );
        } elseif ( $sale_price == '' && has_term( 'Keps Rea', 'product_cat', $post_id ) ) {
            wp_remove_object_terms( $post_id, 'keps-rea', 'product_cat' );
        }
    }
}

