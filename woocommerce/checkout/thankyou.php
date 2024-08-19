<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>
<?php 
$content = get_field('successful_content', 'options');
$failed_content = get_field('failed_content', 'options');

$label_home = get_field('label_home','option') ? get_field('label_home','option') : _('Hem','myhat');
$label_shop_now = get_field('label_shop_now','option') ? get_field('label_shop_now','option') : _('Visa nu','myhat');
?>
<div class="orderThanks">
	<div class="orderThanks__content">
		<?php
		if ( $order ) :
			do_action( 'woocommerce_before_thankyou', $order->get_id() );
			?>
			<?php if ( $order->has_status( 'failed' ) ) : ?>
				<?php if($failed_content): ?>
					<?php echo $failed_content; ?>
				<?php endif; ?>
				<div class="orderThanks__buttons">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn button--black pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn button--white pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<?php if($content): ?>
					<?php echo $content; ?>
				<?php endif; ?>
			<?php endif; ?>
			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
		<?php else : ?>
			<?php if($content): ?>
				<?php echo $content; ?>
			<?php endif; ?>
			<div class="orderThanks__buttons">
				<a href="<?php echo get_home_url(  ); ?>" class="btn button--black pay"><?php echo $label_home;?></a>
				<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) );; ?>" class="btn button--white pay"><?php echo $label_shop_now;?></a>
			</div>
		<?php endif; ?>
	</div>
</div>