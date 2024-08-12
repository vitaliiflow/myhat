<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 6.1.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$product_id = $product->get_id();
$full_customizer = get_field('full_customier') || isset($_GET['customize']);
    
// Retrieve the value of the custom checkbox
$wcbv_checked = get_post_meta($product_id, '_wcbv', true);

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart"
    action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
    method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>"
    data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
    <?php do_action( 'woocommerce_before_variations_form' ); ?>

    <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
    <p class="stock out-of-stock">
        <?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?>
    </p>
    <?php else : ?>
    <table class="variations" cellspacing="0" role="presentation">
        <tbody>
            <?php foreach ( $attributes as $attribute_name => $options ) : ?>
            <tr>
                <th class="label"><label
                        for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label>
                </th>
                <td class="value">
                    <?php
								wc_dropdown_variation_attribute_options(
									array(
										'options'   => $options,
										'attribute' => $attribute_name,
										'product'   => $product,
									)
								);
								echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
							?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php 
					$attributes = $product->get_attributes();
					if(is_a( $product, 'WC_Product_Variable' )):
					?>
						<div class="singleProduct__colorsList attributes-picker-list" data-attribute-name="pa_colors">
							<?php foreach($attributes as $attribute): ?>
								<?php 
								$attributelabel = wc_attribute_label( $attribute['name'] );
								if($attributelabel == 'Colors'):
									$results = woocommerce_get_product_terms($product->id, $attribute['name']);
										foreach($results as $result):?>
											<?php 
											$color = get_field('color', 'pa_colors_' . $result->term_id); 
											?>
											<div class="singleProduct__colorsList__itemWrapper">
												<div class="singleProduct__colorsList__item attributes-picker-item" style="background-color: <?php echo $color ?>" data-attribute="<?php echo $result->slug; ?>"></div>
											</div>
										<?php endforeach; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

    <?php if ($wcbv_checked === 'no') : ?>
                    
    <div class="singleProduct__before-purchase row">
        <div class="singleProduct__sizeWrapper col-sm-6">
            <div class="singleProduct__sizeTitle">Välj storlek</div>
            <div class="singleProduct__sizeList attributes-picker-list"
                data-attribute-name="<?php echo $attribute->get_name(); ?>">
            </div>
        </div>

        <?php 
						
		$product_id = get_the_ID(); // Replace this with the ID of your product

        /**
         * Get products added to Settings->Single Product->available for full customization
         */

        $full_customization_tabs = get_field('tabs_list','option');
        //var_dump($full_customization_list);
        $products_id_array = array();
        foreach ($full_customization_tabs as $full_customization_tab) {

            $list = $full_customization_tab['products_for_customization'];
            //var_dump($list);
            foreach($list as $item) {
                //var_dump($item);
                array_push($products_id_array,$item->ID );
            }
            
        }



		if (is_any_variation_in_stock($product_id)) { ?>

        <?php if (in_array($product_id, $products_id_array) || $full_customizer) { ?>

            <div class="product-customizer__trigger-wrapper col-sm-6">
                <?php 

                                        $permalink = get_the_permalink(); ?>
                <a href="<?php echo $permalink .'?customize';?>"
                    class="d-block button--black"><?php _e('Customize','myhat');?></a>
            </div>

        <?php } else { ?>

            <div class="product-customizer__trigger-wrapper col-sm-6">
                <a href="#product-customizer-popup"
                    class="product-customizer__trigger d-block button--black"><?php _e('Customize','myhat');?></a>
            </div>

        <?php } ?>

        <?php } ?>

    </div>

    <?php endif; //if ($wcbv_checked === 'no') ?>

    <?php do_action( 'woocommerce_after_variations_table' ); ?>

    <?php 
						
    
    
    if ($wcbv_checked === 'yes' && !isset($_GET['customize'])) : 
    
    	$product_id = get_the_ID(); // Replace this with the ID of your product

						/**
						 * Get products added to Settings->Single Product->available for full customization
						 */

						$full_customization_tabs = get_field('tabs_list','option');
						//var_dump($full_customization_list);
						$products_id_array = array();
						foreach ($full_customization_tabs as $full_customization_tab) {

							$list = $full_customization_tab['products_for_customization'];
							//var_dump($list);
							foreach($list as $item) {
								//var_dump($item);
								array_push($products_id_array,$item->ID );
							}
						
							
						}
                        
                        ?>

        <div class="row">
            <?php if (is_any_variation_in_stock($product_id)) { ?>

                <?php if (in_array($product_id, $products_id_array) || $full_customizer) { ?>

                    <div class="product-customizer__trigger-wrapper col-sm-6">
                        <?php 

                                                $permalink = get_the_permalink(); ?>
                        <a href="<?php echo $permalink .'?customize';?>"
                            class="d-block button--black"><?php _e('Customize','myhat');?></a>
                    </div>

                <?php } else { ?>

                    <div class="product-customizer__trigger-wrapper col-sm-6">
                        <a href="#product-customizer-popup"
                            class="product-customizer__trigger d-block button--black"><?php _e('Customize','myhat');?></a>
                    </div>

                <?php } ?>

            <?php } ?>

            <div class="single_variation_wrap col-sm-6">
                <?php
                        /**
                         * Hook: woocommerce_before_single_variation.
                         */
                        do_action( 'woocommerce_before_single_variation' );

                        /**
                         * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
                         *
                         * @since 2.4.0
                         * @hooked woocommerce_single_variation - 10 Empty div for variation data.
                         * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
                         */
                        do_action( 'woocommerce_single_variation' );

                        /**
                         * Hook: woocommerce_after_single_variation.
                         */
                        do_action( 'woocommerce_after_single_variation' );
                    ?>
            </div>
        </div>

        

    <?php else : ?>

        <div class="single_variation_wrap">
            <?php
                    /**
                     * Hook: woocommerce_before_single_variation.
                     */
                    do_action( 'woocommerce_before_single_variation' );

                    /**
                     * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
                     *
                     * @since 2.4.0
                     * @hooked woocommerce_single_variation - 10 Empty div for variation data.
                     * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
                     */
                    do_action( 'woocommerce_single_variation' );

                    /**
                     * Hook: woocommerce_after_single_variation.
                     */
                    do_action( 'woocommerce_after_single_variation' );
                ?>
        </div>

    <?php endif; ?>

    <?php endif; ?>

    <?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );