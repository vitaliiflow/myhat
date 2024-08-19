<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action( 'woocommerce_cart_is_empty' );

$label_cart_empty_message = get_field('label_cart_empty_message','option') ? get_field('label_cart_empty_message','option') : _('Din varukorg är för närvarande tom','myhat');
$label_cart_empty_message_long = get_field('label_cart_empty_message_long','option') ? get_field('label_cart_empty_message_long','option') : _('Innan du går vidare till kassan måste du lägga till några produkter i din varukorg. Du hittar många intressanta produkter på vår "Shop"-sida.','myhat');
$label_shop_now = get_field('label_shop_now','option') ? get_field('label_shop_now','option') : _('Visa nu','myhat');

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<div class="emptyCart">
        <div class="emptyCart__container">
            <h1 class="emptyCart__title"><?php echo $label_cart_empty_message;?></h1>
            <div class="emptyCart__subtitle"><?php echo $label_cart_empty_message_long;?></div>
            <div class="emptyCart__button">
                <a href="<?php echo wc_get_page_permalink( 'shop' ) ?>" class="btn button--black"><?php echo $label_shop_now;?></a>
            </div>
        </div>
    </div>
<?php endif; ?>
