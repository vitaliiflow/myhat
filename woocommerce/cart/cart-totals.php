<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

$min_free_shipping_price = get_field('cart_free_shipping_min_price', 'options');

$label_remaining_till_free_shipping = get_field('label_remaining_till_free_shipping','option');
$label_grand_total = get_field('label_grand_total','option');
$label_go_to_checkout = get_field('label_go_to_checkout','option');
$label_keep_shopping = get_field('label_add_discount_code$label_keep_shopping','option');


?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">
			
	<?php do_action( 'woocommerce_before_cart_totals' ); ?>


	<div cellspacing="0" class="shop_table shop_table_responsive">


		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<div><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
				<div data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>



		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<div class="shipping">
				<div><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></div>
				<div data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></div>
			</div>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="fee">
				<div><?php echo esc_html( $fee->name ); ?></div>
				<div data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></div>
			</div>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<div><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<div data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
					</div>
					<?php
				}
			} else {
				?>
				<div class="tax-total">
					<div><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></div>
				</div>
				<?php
			}
		}
		?>
	</div>
	<div class="cart__orderData">
		<?php 
		$subtotal = intval(WC()->cart->subtotal);
		if($min_free_shipping_price > $subtotal):
		?>
			<div class="cart__orderFreeShipping">
				<div class="cart__orderFreeShipping__label"><?php if($label_remaining_till_free_shipping){echo $label_remaining_till_free_shipping;}else{_e('Kvar till fri frakt:','myhat');}?></div>
				<div class="cart__orderFreeShipping__price">
					<?php echo $min_free_shipping_price - $subtotal; ?>
					<span class="currency"><?php echo get_woocommerce_currency_symbol(); ?></span>
				</div>
			</div>
		<?php endif; ?>
		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<div class="cart__orderTotals">
			<div class="cart__orderTotals__label">
				<?php if($label_grand_total){echo $label_grand_total;}else{_e('Totalsumma:','myhat');} ?>
			</div>
			<div class="cart__orderTotals__price">
				<div data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></div>
			</div>
		</div>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
	</div>
	<div class="wc-proceed-to-checkout cart__totalsButtons">
        <a href="<?php echo wc_get_page_permalink( 'checkout' ); ?>" class="cart__submit btn button--black"><?php if($label_go_to_checkout){echo $label_go_to_checkout;}else{ _e('Gå till kassan','myhat');}?></a>
        <a href="<?php echo wc_get_page_permalink( 'shop' ); ?>" class="cart__shop btn button--white button--border--white"><?php if($label_keep_shopping){echo $label_keep_shopping;}else{_e('Fortsätt handla','myhat');}?></a>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
