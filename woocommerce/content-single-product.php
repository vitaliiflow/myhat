<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
$attachment_ids = $product->get_gallery_image_ids();
$post_image = get_the_post_thumbnail_url(  );
?>
<section id="product-<?php the_ID(); ?>" <?php wc_product_class( 'singleProduct', $product ); ?>>
	<div class="container">
		<div class="singleProduct__breadcrumbs"><?php woocommerce_breadcrumb(); ?></div>
		<div class="singleProduct__content">
			<div class="singleProduct__galleryWrapper">
				<div class="singleProduct__gallery">
					<?php if(!empty($attachment_ids) || !empty($post_image)): ?>
						<?php if(!empty($post_image)): ?>
							<div class="singleProduct__gallery__item">
								<img src="<?php echo $post_image; ?>" alt="">
							</div>
						<?php endif; 
						if(!empty($attachment_ids)):
							foreach( $attachment_ids as $attachment_id ) {
								$image_link = wp_get_attachment_url( $attachment_id ); ?>
								<div class="singleProduct__gallery__item">
									<img src="<?php echo $image_link; ?>" alt="">
								</div>
							<?php } 
						endif; ?>
					<?php else: ?>
						<div class="singleProduct__gallery__item">
							<img src="<?php echo wc_placeholder_img_src(); ?>" alt="">
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="singleProduct__dataWrapper">
				<div class="singleProduct__data">
					<?php $categories = get_the_terms( $product->get_id(), 'varumarke' ); ?>
					<?php if($categories && ! is_wp_error( $categories )): ?>
						<?php 
							$tax = $categories[0]->name; 
							$tax_id = $categories[0]->term_id;
						?>
						<div class="singleProduct__tax"><?php echo $tax; ?></div>
					<?php endif; ?>
					<h2 class="singleProduct__title"><?php the_title(); ?></h2>
					<div class="singleProduct__price<?php if($product->is_on_sale()){ echo ' sale'; } ?>"><?php echo $product->get_price_html(); ?></div>
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
									if(count($results) > 1):
										foreach($results as $result):?>
											<?php 
											$color = get_field('color', 'pa_colors_' . $result->term_id); 
											?>
											<div class="singleProduct__colorsList__itemWrapper">
												<div class="singleProduct__colorsList__item attributes-picker-item" style="background-color: <?php echo $color ?>" data-attribute="<?php echo $result->slug; ?>">
												</div>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php 
					if(is_a( $product, 'WC_Product_Variable' )):
					?>
						<div class="singleProduct__sizeWrapper">
							<?php foreach($attributes as $attribute): ?>
								<?php $attributelabel = wc_attribute_label( $attribute['name'] ); ?>
								<?php if($attributelabel == 'Storlek'): ?>
									<?php $results = woocommerce_get_product_terms($product->id, $attribute['name']); ?>
									<?php if(count($results) > 1): ?>
										<div class="singleProduct__sizeTitle">Välj storlek</div>
										<div class="singleProduct__sizeList attributes-picker-list" data-attribute-name="<?php echo $attribute['name']; ?>">
											<?php foreach($results as $result): ?>
												<div class="singleProduct__sizeList__item attributes-picker-item" data-attribute="<?php echo $result->slug; ?>"><?php echo $result->name; ?></div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<div class="singleProduct__purchase">
						<?php do_action('woocommerce_product_add_to_cart'); ?>
					</div>
					<?php if(have_rows('product_features', 'options')): ?>
						<div class="singleProduct__featuresList">
							<?php while(have_rows('product_features', 'options')): the_row(); ?>
								<?php 
								$icon = get_sub_field('icon');
								$text = get_sub_field('text');
								if($icon || $text):
								?>
								<div class="singleProduct__featuresList__item">
									<?php if($icon): ?>
										<div class="singleProduct__featuresList__itemIcon"><img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['title']; ?>"></div>
									<?php endif; ?>
									<?php if($text): ?>
										<div class="singleProduct__featuresList__itemLabel"><?php echo $text; ?></div>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
					<?php if(!empty($product_tabs)): ?>
						<div class="singleProduct__accordionList">
							<?php foreach($product_tabs as $key => $product_tab): ?>
								<div class="singleProduct__accordionItem">
									<div class="singleProduct__accordionItem__title"><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?></div>
									<?php 
									if ( isset( $product_tab['callback'] ) ) :
									?>
										<div class="singleProduct__accordionItem__content"><?php call_user_func( $product_tab['callback'], $key, $product_tab ); ?></div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if(!empty($tax)): ?>
						<a href="<?php echo get_category_link($tax_id); ?>" class="singleProduct__link"><?php echo 'Se mer från ' . $tax; ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php 
$title = get_field('related_products_title', 'options');
$link = get_field('related_products_link', 'options');
?>
<section class="section latest-products">
    <div class="container">
		<?php if($title || $link): ?>
			<div class="section__title">
				<?php if($title): ?>
					<h2 class="text-center"><?php echo $title; ?></h2>
				<?php endif; ?>
				<?php if($link): ?>
					<?php $link_target = $link['target'] ? $link['target'] : '_self'; ?>
					<a class="btn button--arrow sm" href="<?php echo $link['url']; ?>" target="<?php echo $link_target; ?>"><?php echo $link['title']; ?><?php echo get_inline_svg('arrow-right.svg');?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php 
		
		$related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $product_per_page, $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
		$products = wc_products_array_orderby( $related_products, 'rand', 'desc' );
		?>
        <ul class="row products latest-products__list latest-products__list-slider">
        <?php foreach ($products as $product_id) :
            
			$post_object = get_post( $product_id->get_id() );
			setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found ?>

			<div class="shopPage__listItem latest-products__item<?php if ($slider) : echo ' col-lg-auto'; else : echo ' col-6 col-sm-4 col-md-3'; endif;?>">

				<?php wc_get_template_part('content', 'product'); ?>

			</div>
            <?php 
			// Reset the global post data
            wp_reset_postdata();
        endforeach; ?>
        </ul>
    </div>
</section>

<?php do_action( 'woocommerce_after_single_product' );?>
