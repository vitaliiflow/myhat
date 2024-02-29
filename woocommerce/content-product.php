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
	echo ' product invisible inside';
	// return;
}
?>
<a href="<?php the_permalink( ); ?>" class="shopPage__listItem__content">
	<div class="shopPage__listItem__labels">
		<?php echo get_template_part( 'woocommerce/loop/sale-flash' ); ?>
		<?php 
		$badges = get_field('badges');
		$new = false;
		$top = false;
		$last = false;
		$hide = false;
		$limited = false;
		if(!empty($badges)):
			foreach($badges as $badge):
				switch($badge['value']):
					case 'hide':
						$hide= true;
						break;
					case 'limited':
						$limited= true;
						break;
					case 'top':
						$top = true;
						break;
					case 'new':
						$new = true;
						break;
					case 'last':
						$last = true;
						break;
				endswitch;
			endforeach;
		else: 
			$badges = false;
		endif;
		if(!$hide):
			//New Product Label
			$newness_days = 30; 
			$created = strtotime( $product->get_date_created() );
			if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created  || $new) {
				echo '<span class="new-badge shopPage__listItem__badge">' . esc_html__( 'NEW', 'woocommerce' ) . '</span>';
			}

			//Last Chance Label
			$total_stock = 0;
			if($product->is_type('variable')){
				$product_variable = new WC_Product_Variable($product->id);
				$product_variations = $product_variable->get_available_variations();
				foreach ($product_variations as $variation)  {
					$total_stock += intval($variation['max_qty']);
				}
			} else {
				$total_stock = $product->get_stock_quantity();
			}
			if($total_stock <= 3 || $last){
				echo '<span class="last-chance shopPage__listItem__badge">' . esc_html__( 'Last Chance', 'woocommerce' ) . '</span>';
			}
			if(dw_product_totals() > 50 || $top){
				echo '<span class="top-seller shopPage__listItem__badge">' . esc_html__( 'Top seller', 'woocommerce' ) . '</span>';
			}
			if($limited):
				echo '<span class="limited-edition shopPage__listItem__badge">' . esc_html__( 'Limited Edition', 'woocommerce' ) . '</span>';
			endif;
		endif;
		?>

	</div>
	<?php 
	$categories = get_the_terms( $product->get_id(), 'varumarke' );
	if(!empty(wp_get_attachment_url( $product->get_image_id() ))):
		$image = wp_get_attachment_url( $product->get_image_id() );
	else: 
		$image = get_template_directory_uri() . '/assets/images/elementor-placeholder-image.webp';
	endif;
	?>
	<div class="shopPage__listItem__image">
		<img src="<?php echo $image; ?>" class="img-absoolute" alt="">
	</div>
	<?php if($categories && ! is_wp_error( $categories )): ?>
		<div class="shopPage__listItem__tax"><?php echo $categories[0]->name; ?></div>
	<?php endif; ?>
	<div class="shopPage__listItem__title"><?php echo $product->get_title(); ?></div>
	<div class="shopPage__listItem__price<?php if($product->is_on_sale()){ echo ' sale';} ?>"><?php echo $product->get_price_html(); ?></div>
</a>
