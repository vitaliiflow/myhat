<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	// echo ' product invisible inside';
	// return;
}
?>
<a href="<?php the_permalink( ); ?>" class="shopPage__listItem__content">
	<div class="shopPage__listItem__labels">
		<?php get_template_part( 'woocommerce/loop/sale-flash' ); ?>
		<?php get_template_part( 'woocommerce/parts/product-bages' ); ?>

	</div>
	<?php 
	$categories = get_the_terms( $product->get_id(), 'varumarke' );
	if(!empty(wp_get_attachment_url( $product->get_image_id() ))):
		// $image = wp_get_attachment_url( $product->get_image_id() );
		$image = wp_get_attachment_image_src( $product->get_image_id(), 'woocommerce_single' )[0];
	else: 
		$image = get_template_directory_uri() . '/assets/images/elementor-placeholder-image.webp';
	endif;
	$gallery_images = $product->get_gallery_image_ids();

	if ( $gallery_images ) {
		$first_image_url = wp_get_attachment_image_url( $gallery_images[0], 'woocommerce_single' );
	}
	?>
	<div class="shopPage__listItem__image<?php if(!empty($first_image_url)){echo ' has-hover';} ?>">
		<img src="<?php echo $image; ?>" class="img-absoolute" alt="<?php echo $product->get_title(); ?>">
		<?php if(!empty($first_image_url)): ?>
			<img src="<?php echo $first_image_url; ?>" alt="<?php echo $product->get_title() . 'second view'; ?>" class="img-absoolute hover-image">
		<?php endif; ?>
	</div>
	<?php if($categories && ! is_wp_error( $categories )): ?>
		<div class="shopPage__listItem__tax"><?php echo $categories[0]->name; ?></div>
	<?php endif; ?>
	<div class="shopPage__listItem__title"><?php echo $product->get_title(); ?></div>
	<div class="shopPage__listItem__price<?php if($product->is_on_sale()){ _e(' Sale','codelibry');} ?>"><?php echo $product->get_price_html(); ?></div>
</a>
