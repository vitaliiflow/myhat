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

?>

<section id="product-<?php the_ID(); ?>" <?php wc_product_class( 'singleProduct', $product ); ?>>
	<div class="container">
		<div class="singleProduct__breadcrumbs"><?php get_template_part('template-parts/parts/breadcrumbs'); ?></div>
		<div class="singleProduct__content">
			<div class="singleProduct__galleryWrapper">
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
							$product_variable = new WC_Product_Variable($product->get_id());
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
				<div class="singleProduct__gallery">

					<div class="product-customizer__wrapper">
						<?php echo do_shortcode('[fpd]'); ?>
					</div>
					

					<?php 
					global $product;

					$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
					$post_thumbnail_id = $product->get_image_id();
					$wrapper_classes   = apply_filters(
						'woocommerce_single_product_image_gallery_classes',
						array(
							'woocommerce-product-gallery',
							'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
							'woocommerce-product-gallery--columns-' . absint( $columns ),
							'images',
						)
					);
					?>
					<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
						<div class="woocommerce-product-gallery__wrapper">
							<?php
							if ( $post_thumbnail_id ) {
								$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
							} else {
								$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
								$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
								$html .= '</div>';
							}
					
							echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
					
							do_action( 'woocommerce_product_thumbnails' );
							?>
						</div>
					</div>
					
				</div>
			</div>
			<div class="singleProduct__gallerySlider">
				<div class="woocommerce-product-gallery__wrapper">
					<?php
					if ( $post_thumbnail_id ) {
						$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
					} else {
						$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
						$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
						$html .= '</div>';
					}
					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
					do_action( 'woocommerce_product_thumbnails' );
					?>
				</div>
				<div class="singleProduct__galleryOverlay"></div>
				<div class="singleProduct__gallerySlider__close"></div>
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
										<div class="singleProduct__sizeTitle">Välj storlek</div>
										<div class="singleProduct__sizeList attributes-picker-list" data-attribute-name="<?php echo $attribute['name']; ?>">
											<?php foreach($results as $result): ?>
												<div class="singleProduct__sizeList__item attributes-picker-item" data-attribute="<?php echo $result->slug; ?>"><?php echo $result->name; ?></div>
											<?php endforeach; ?>
										</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<div class="singleProduct__purchase">
						<input
							type="<?php echo esc_attr( $type ); ?>"
							<?php echo $readonly ? 'readonly="readonly"' : ''; ?>
							id="<?php echo esc_attr( $input_id ); ?>"
							class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
							name="<?php echo esc_attr( $input_name ); ?>"
							value="<?php echo esc_attr( $input_value ); ?>"
							aria-label="<?php esc_attr_e( 'Product quantity', 'woocommerce' ); ?>"
							size="4"
							min="<?php echo esc_attr( $min_value ); ?>"
							max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
							<?php if ( ! $readonly ) : ?>
								step="<?php echo esc_attr( $step ); ?>"
								placeholder="<?php echo esc_attr( $placeholder ); ?>"
								inputmode="<?php echo esc_attr( $inputmode ); ?>"
								autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
							<?php endif; ?>
						/>
						<?php do_action('woocommerce_product_add_to_cart'); ?>
					</div>

					<div class="product-customizer__trigger-wrapper">
						<a href="#product-customizer-popup" class="product-customizer__trigger d-block button--black mt-4 mb-4"><?php _e('Customize','myhat');?></a>
					</div>

					<!-- <div id="product-customizer-popup" class="popup-block">
						<div class="popup-block__wrapper">
							<div class="popup-block__inner">
								<div class="popup-block__close"></div>
								
							</div>
						</div>
					</div> -->
					
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
		$product_per_page = 5;
		$related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $product_per_page, $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
		$products = wc_products_array_orderby( $related_products, 'rand', 'desc' );
		?>
        <ul class="row products latest-products__list latest-products__list-slider">
        <?php foreach ($products as $product_id) :
            
			$post_object = get_post( $product_id->get_id() );
			setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found ?>

			<div class="shopPage__listItem latest-products__item col-lg-auto">

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
