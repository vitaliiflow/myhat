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
$full_customizer =  isset($_GET['customize']);

if( 'simple' === $product->get_type() ) { ?>
	<style>
		.singleProduct__purchase {
			flex-wrap: wrap;
		}
		
		.singleProduct__purchase .stock {
			margin-bottom: 1rem;
			margin-top: 1rem;
			width: 100%;
		}
		
		.singleProduct__purchase .cart {
			flex-direction: row;
		}
		
		.single_add_to_cart_button {
			border-radius: 0;
		}
	</style>
<?php }

?>

<script>
	jQuery(document).on('click', '.btn-plus, .btn-minus', function(e) {
		const isNegative = jQuery(e.target).closest('.btn-minus').is('.btn-minus');
		const input = jQuery(e.target).closest('.wcbv-quantity').find('input');
		if (input.is('input')) {
			input[0][isNegative ? 'stepDown' : 'stepUp']();
			// Trigger 'input' event to notify any listeners about the value change
			input.trigger('change');
		}
	});
</script>

<?php 


if (!$full_customizer) : 


?>

<style>

@media (max-width: 576px) {
	.product-customizer__trigger-wrapper {
		text-align: center;
		margin-bottom: 1rem;
	}
}
	
	.pathes-tab-trigger {
/* 		opacity: 0; */
		margin-left: 10px;
		margin-right: 10px;
		margin-top: 0;
	}
	
	.fpd-text-templates {
		padding: 10px;
	}
	
	.fpd-padding + .pathes-tab-trigger {
		margin-top: -20px;
	}
	
	.fpd-container fpd-module-text .fpd-add-text .fpd-btn {
		margin-bottom: 0;
	}


</style>
<script>

	
	jQuery(document).ready(function () {
		// Log to verify the script is working
		// console.log('this script is working');

		// Event handler for clicking the product customizer trigger
		jQuery(document).on("click", ".product-customizer__trigger-wrapper", function () {

			// Select all target elements where you want to append the div
			var targetElements = jQuery('.fpd-text-templates'); // Replace with your target elements selector

			// Iterate through each target element and append the new div if it doesn't already exist
			targetElements.each(function() {
				if (jQuery(this).find('.pathes-tab-trigger').length === 0) {
					var newDiv = jQuery('<div>Flaggor och patchar.</div>').addClass('pathes-tab-trigger fpd-btn'); // Replace 'your-class' with the desired class
					jQuery(this).append(newDiv);
				}
			});

		});

		// Event handler for clicking the pathes-tab-trigger
		jQuery(document).on("click", ".pathes-tab-trigger", function () {
			jQuery('.fpd-add-design').click();
		});
	});

	
	
</script>

<section id="product-<?php the_ID(); ?>" <?php wc_product_class( 'singleProduct', $product ); ?>>
	<div class="container">
		<div class="singleProduct__breadcrumbs"><?php get_template_part('template-parts/parts/breadcrumbs'); ?></div>
		<div class="singleProduct__content">
			<div class="singleProduct__galleryWrapper">
				<div class="shopPage__listItem__labels">
					<?php echo get_template_part( 'woocommerce/loop/sale-flash' ); ?>
					<?php get_template_part( 'woocommerce/parts/product-bages' ); ?>


				</div>
				<div class="singleProduct__gallery">

					<div class="product-customizer__wrapper">
						<?php echo do_shortcode('[fpd]'); ?>
						<?php if (!$full_customizer) : ?>
							<div class="wc-block-components-notice-banner--fpd-color" style="margin-top: 30px; background: #f1f1ef; padding: 15px; display: none;">
								<div class="wc-block-components-notice-banner__content">
									! Klicka på din text för att ändra färg!
								</div>
							</div>
						<script>
							jQuery(document).ready(function () {
								//console.log('this sript is working');
								jQuery(".fancy-product").on("click", ".fpd-add-text .fpd-btn", function () {

									jQuery('.wc-block-components-notice-banner--fpd-color').show();
								})
							});
						</script>
						<style>
							.fpd-container .fpd-scroll-area.fpd-tools-nav {
								flex-direction: row;
							}
							
							.fpd-tool-position {
								display: none !important;
							}
							
							@media (max-width: 450px) {
								.wc-block-components-notice-banner--fpd-color {
									position: absolute;
									left: 0;
									bottom: 0;
									width: 100%;
									z-index: 100;
								}
							}
						
						</style>
						<?php endif; ?>
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
					
							$attachment_ids = $product->get_gallery_image_ids();
							if ( $attachment_ids && count( $attachment_ids ) > 0 ) {
							    foreach ( $attachment_ids as $attachment_id ) {
							        $image_url = wp_get_attachment_image_url( $attachment_id, 'full' ); ?>
									<div data-thumb="<?php echo esc_url( $image_url ); ?>" class="woocommerce-product-gallery__image">
										<a href="<?php echo esc_url( $image_url ); ?>">
											<?php echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) . '" />'; ?>
										</a>
									</div>
									<?php
							    }
							}
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
					$attachment_ids = $product->get_gallery_image_ids();
					if ( $attachment_ids && count( $attachment_ids ) > 0 ) {
					    foreach ( $attachment_ids as $attachment_id ) {
					        $image_url = wp_get_attachment_image_url( $attachment_id, 'full' ); ?>
							<div data-thumb="<?php echo esc_url( $image_url ); ?>" class="woocommerce-product-gallery__image">
								<?php echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) . '" />'; ?>
							</div>
							<?php
					    }
					}
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
					<h1 class="singleProduct__title h2"><?php the_title(); ?></h1>
					<div class="singleProduct__price<?php if($product->is_on_sale()){ echo ' sale'; } ?>"><?php echo $product->get_price_html(); ?></div>
					<div class="singleProduct__purchase">
						
						<?php 
						
						$product_id = $product->get_id();
						
						// Retrieve the value of the custom checkbox
						$wcbv_checked = get_post_meta($product_id, '_wcbv', true);
						
						if ($wcbv_checked === 'yes') {
							echo do_shortcode('[wcbv]');
							
							?>
						
						<style>
							.singleProduct__purchase {
								display: block;
							}
							
							.singleProduct__before-purchase {
								display: none;
							}
							
							.singleProduct__purchase .quantity-btn {
								display: none;
							}
							
							.wcbv-reset-variations {
						display: none;
					}
					
					.wcbv-fields select {
					  -webkit-appearance: none;
					  -moz-appearance: none;
					  text-indent: 1px;
					  text-overflow: '';
					  height: 45.5px;
					  padding: 10px;
					  margin-left: -5px !important;
					  min-width: 200px;
					  margin-right: 10px !important;
					}
					
					.wcbv-quantity--wrapper {
						display: flex;
					}
					
					.wcbv-wrapper .wcbv-quantity {
						flex-basis: 155px !important;
						padding-right: 20px;
					}
					
					#wcbv-add-row {
						padding: 10px 20px;
					}
					
					.wcbv-quantity--wrapper .btn {
						border-radius: 0;
					}
					
					.wcbv-attributes-head>div.wcbv-remove {
						border-bottom: 2px solid rgba(0,0,0,.1);
					}
					
					.wcbv-quantity--wrapper input {
						width: 70px;
						height: 100% !important;
						background-color: #f1f1ef;
						border-radius: 0;
						padding-left: 15px;
						padding-right: 15px;
						text-align: center;
					}
					
					.wcbv-remove .wcbv-remove-row {
						left: -10px;
					}
					
					
					
					@media screen and (max-width: 767px) {
    					.pvtfw_variant_table_block table.variant td:before {
							padding: 20px;
						}
						
						.product-select--thumbnails {
							width: 100%;
						}
						
						.product-select--thumbnails .fpd-view-thumbnails-wrapper {
							width: 100%;
						}
						
						.product-select--thumbnails .fpd-view-thumbnails-wrapper>.fpd-item {
							width: calc(25% - 15px);
							height: auto;
							aspect-ratio: 1 / 1;
						}
						.pvtfw_init_variation_table, .pvtfw_variant_table_block, table.variant, .singleProduct__purchase {
							width: 100%;
						}
					}
					
					@media (max-width: 568px) {
						.wcbv-row .wcbv-fields {
							display: flex !important;
						}
					}
					
					@media screen and (max-width: 450px) {
						.fpd-view-thumbnails-wrapper {
							gap: 0;
/* 							margin-left: -10px;
							margin-right: -10px; */
						}
						.product-select--thumbnails .fpd-view-thumbnails-wrapper>.fpd-item {
							width: calc(50% - 20px);
							margin: 10px
						}
						
						.pvtfw_variant_table_block, table.variant, .singleProduct__purchase {
							width: 100%;
						}
						
						.pvtfw_init_variation_table {
							margin-left: 10px;
							margin-right: 10px;
							width: calc(100% - 20px);
						}
						
						.product-select--thumbnails, .fpd-navigation--color-selection .singleProduct__purchase {
							padding-left: 10px;
							padding-right: 10px;
						}
						
						.wcbv-quantity--wrapper input {
							width: 50px;
							padding-left: 10px; 
							padding-right: 10px;
						}
						
						.wcbv-fields select {
							max-width: 175px;
							min-width: 175px;
							margin-left: 0 !important;
						}
						
						.wcbv-wrapper .wcbv-selects>* {
							margin-bottom: 0;
						}
						
						.wcbv-quantity--wrapper .btn {
							padding-left: 15px;
							padding-right: 15px;
						}
					}
					
					@media screen and (max-width: 390px) {
						.wcbv-fields select {
							max-width: 150px;
							min-width: 150px;
						}
					}
							
							.singleProduct__purchase .single_add_to_cart_button {
								margin-left: 0;
							}
							
							.singleProduct__colorsList {
								display: none;
							}
						</style>
						
						<div class="bulk-variation-addon <?php echo $product_id;?>" style="display:none;">
							<?php var_dump($wcbv_checked); ?>
						</div>
						
						<?php 
					
						}
						
						?>
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
										<div class="singleProduct__featuresList__itemIcon"><img src="<?php echo $icon; ?>" alt="check icon"></div>
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
<script>
	jQuery(document).on('click', '.btn-plus, .btn-minus', function(e) {
		const isNegative = jQuery(e.target).closest('.btn-minus').is('.btn-minus');
		const input = jQuery(e.target).closest('.wcbv-quantity').find('input');
		if (input.is('input')) {
			input[0][isNegative ? 'stepDown' : 'stepUp']();
			// Trigger 'input' event to notify any listeners about the value change
			input.trigger('change');
		}
	});
</script>
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
		$chosen_products = $product->get_upsell_ids();
		if(count($chosen_products) <= 5){
			$product_per_page = 5 - count($chosen_products);	
		}
		else{
			$product_per_page = 0;
		}
		
		$related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $product_per_page, $chosen_products ) ), 'wc_products_array_filter_visible' );
		$products = wc_products_array_orderby( $related_products, 'rand', 'desc' );
		?>
        <ul class="row products latest-products__list latest-products__list-slider">
		<?php $i = 0; foreach($chosen_products as $product_id): ?>
			<?php 
				if($i < 5):
				$post_object = get_post( $product_id );
				setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found ?>
	
				<div class="shopPage__listItem latest-products__item col-lg-auto">
	
					<?php wc_get_template_part('content', 'product'); ?>
	
				</div>
				<?php endif; $i++ ?>
				<?php 
				// Reset the global post data
				wp_reset_postdata();
			endforeach; ?>
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

<?php else : ?>

	

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php echo do_shortcode('[fpd]'); ?>
				<?php 
				
				$list = get_field('tabs_list','option'); /*repeater: category, custom name*/
				
				?>
				
				<?php if ($list) : 

				$locale = get_locale();

				$label_select_the_product = 'Select the product';
				$label_wholesale_discount = 'Wholesale discounts';

				if ($locale == 'sv_SE') :
					$label_select_the_product = 'Välj produkt';
					$label_wholesale_discount = 'Mängdrabatt';
				endif;
					
					
					?>

					

            <div class="tabs" style="background-color: white;">
				
				<div class="product-data-wrapper">
				
					<div class="product-select product-select--trigger">
						<span class="fpd-nav-icon fpd-icon-grid"></span>
						<span class="fpd-label"><?php echo $label_select_the_product;?></span>
					</div>

					<div class="product-select product-select--price-discount">
						<span class="fpd-nav-icon fpd-icon-grid"></span>
						<span class="fpd-label"><?php echo $label_wholesale_discount;?></span>
					</div>
					
				</div>
				
				<script>

					jQuery(document).ready(function(){
						jQuery(".product-select--trigger").on("click", function(){
							jQuery(".product-select--wrapper").slideToggle();
							jQuery(".product-select--price-discount-block").hide();
						})
					});
					
					jQuery(document).ready(function(){
						jQuery(".product-select--price-discount").on("click", function(){
							jQuery(".product-select--wrapper").hide();
							jQuery(".product-select--price-discount-block").slideToggle();
						})
					});
					
				</script>
				
				
				
				<style>
					
					.tabs {
						padding: 5px;
					}
					
					.product-data-wrapper {
						display: flex; 
						flex-wrap: wrap;
					}
					
					.product-select--trigger, .product-select--price-discount {
						cursor: pointer;
					}
					
					.product-select--wrapper, .product-select--price-discount-block {
						display: none;
						cursor: pointer;
					}
					
					.product-select {
						padding-left: 20px;
						padding-right: 20px;
						display: flex;
						align-items: center;
					}
					
					.product-select .fpd-label {
						padding-left: 10px;
					}
					
					.product-select .fpd-nav-icon { 
						line-height: 65px;
    					font-size: 26px;
					}
				
					.product-select {
						font-size: 15px;
						text-transform: uppercase;
					}
					
					.tabsNav__item {
						padding-left: 20px;
						padding-right: 20px;
					}
					
				</style>
				
				<div class="product-select--price-discount-block">
					<div class="wcbv-discounts-table" bis_skin_checked="1">
									<div class="wcbv-variation-desc" bis_skin_checked="1">
											</div>
					<div class="wcbv-discount-rules" bis_skin_checked="1">

						<div class="wcbv-head" bis_skin_checked="1">
							<div bis_skin_checked="1">QTY</div>
							<div bis_skin_checked="1">Discount</div>
						</div>

									<div class="wcbv-discount-rule" bis_skin_checked="1">
					<div bis_skin_checked="1">100</div>
					
					<div bis_skin_checked="1">50%</div>
				</div>
							<div class="wcbv-discount-rule" bis_skin_checked="1">
					<div bis_skin_checked="1">50</div>
					
					<div bis_skin_checked="1">40%</div>
				</div>
							<div class="wcbv-discount-rule" bis_skin_checked="1">
					<div bis_skin_checked="1">20</div>
					
					<div bis_skin_checked="1">30%</div>
				</div>
							<div class="wcbv-discount-rule" bis_skin_checked="1">
					<div bis_skin_checked="1">10</div>
					
					<div bis_skin_checked="1">20%</div>
				</div>
							<div class="wcbv-discount-rule" bis_skin_checked="1">
					<div bis_skin_checked="1">2</div>
					
					<div bis_skin_checked="1">10%</div>
				</div>
								</div>
										<div class="wcbv-variation-desc" bis_skin_checked="1">
											</div>
					
								</div>
				</div>
				
				<div class="product-select--wrapper">

                <ul class="row mt-3 mx-0">

                    <?php $i = 1; ?>

                    <?php foreach ($list as $item) :

   
                        $name = $item['label'];

                        ?>

                            <li class="tabsNav__item col-auto">
                                <a class="tabs__nav text-uppercase js-tab-nav <?php if($i == '1') : echo 'active'; endif;?>" href="<?php echo "#tab" . $i; $i++;?>">
                                    <h6><?php echo $name; ?></h6>
                                </a>
                            </li>

                    <?php endforeach; ?>
                    
                </ul>

                <ul class="row">

                    <?php $c = 1; ?>
                    <?php foreach ($list as $item) : 

                        $products = $item['products_for_customization'];
           

                        // Check if products exist
                        if (! empty( $products )) : ?>

                            <li id="<?php echo 'tab' . $c;?>" class="tabs__item col <?php echo 'tab' . $c;?>  <?php if($c == 1) : echo 'active'; endif;?>">

                                <div class="tabs__item-inner bg-color bg-color--white">

                                    <ul class="row mx-0 tabs__item-list">

                                    <?php foreach ( $products as $post ) : 
										setup_postdata($post);
                                        
                                        $image = get_the_post_thumbnail_url($post);
                                        $name = get_the_title($post);
                                        $link = get_the_permalink($post) . '?customize';
										
										
                        
                                        if ($image) : ?>
                                            <li class="col-4 col-md-3 col-lg-2 py-2 tabs__item-child-item">
                                                <a href="<?php echo $link; ?>" class="product-cat__item-link">
                        
                                                    <?php if ( $image ) : ?>

                                                        <div class="teams-page__logo-wrapper mb-3">
                                                            <img class="teams-page__logo" src="<?php echo $image; ?>" alt="<?php echo $name . ' logo'; ?>" />
                                                        </div>
                        
                                                    <?php endif; ?>
                        
                                                    <div><?php echo $name; ?></div>
                                                </a>
                                            </li>
                                                       
                                        <?php endif; ?>


                                    <?php endforeach; wp_reset_postdata();?>

                                    </ul>

                                </div>

                            </li>


                        <?php endif; ?>

                        <?php $c++; endforeach;?>

                    </ul>
					
					</div>

            </div>

        <?php endif; ?> 
				
				<div class="fpd-navigation--color-selection">

					<div class="product-select product-select--thumbnails">
						<?php echo do_shortcode('[fpd_view_thumbnails]');?>
					</div>
					
					
					
						
						<?php 
						
						$product_id = get_the_ID(); // Replace this with the ID of your product
						if (is_any_variation_in_stock($product_id)) { ?>
						
							<div class="singleProduct__purchase">

								
							
								<?php 

								echo do_shortcode('[wcbv]');
								do_action('woocommerce_product_add_to_cart');
																	 
																	 ?>
							</div> <?php 
							
						} else { ?>
						
							<div class="singleProduct__purchase" style="align-self: center;">

								<?php echo 'Sorry, the product is out of stock'; ?>
							</div>
						
						<?php } ?>
						
					
						
					
				</div>
				
				
				<style>
					.singleProduct__purchase .quantity-btn {
						display: none;
					}
					.singleProduct__purchase .single_add_to_cart_button {
						width: 100%;
						margin-left: 0;
					}
					.tabs {
						border-bottom: 1px solid var(--fpd-border-color);
					}
					
					.fpd-off-canvas fpd-main-bar .fpd-navigation {
						border-bottom: 0;	
					}
					
					label.screen-reader-text {
						display: none;
					}
					#variant-table {
						display: none;
					}
					.fpd-navigation--color-selection {
						background-color: white;
						padding: 5px;
						
						display: flex;
						align-items: flex-start;
					}
					
					@media only screen and (max-width: 768px) {
						.fpd-navigation--color-selection {
							flex-direction: column;
						}
					}
					
					.fpd-navigation--color-selection .fpd-color-selection {
						margin-top: 0;
						margin-bottom: 0;
					}
					
					.fpd-navigation--color-selection .fpd-cs-item {
						padding: 15px 20px;
					}
					
					.fpd-navigation--color-selection .fpd-title {
						font-size: 15px;
						color: var(--fpd-text-color);
						text-transform: uppercase;
						border-bottom: 0 !important;
					}
					
					.fpd-navigation--color-selection .singleProduct__purchase{
						padding: 15px 20px;
						margin-left: auto;
					}
					
					.singleProduct__purchase {
						flex-direction: column;
					}
					
					@media only screen and (max-width: 768px) {
						.fpd-navigation--color-selection .singleProduct__purchase{
							margin-left: 0;
						}
					}
					
					.fpd-navigation--color-selection .singleProduct__purchase .stock {
						display: none;
					}
					
					.fpd-panel-tabs [data-tab="fill"] {
						display: none;
					} 
					
					.fpd-navigation--color-selection .button {
						border-radius: 0;
					}
					
					.wcbv-reset-variations {
						display: none;
					}
					
					.wcbv-fields select {
					  -webkit-appearance: none;
					  -moz-appearance: none;
					  text-indent: 1px;
					  text-overflow: '';
					  height: 45.5px;
					  padding: 10px;
					  margin-left: -5px !important;
					  min-width: 200px;
					  margin-right: 10px !important;
					}
					
					.wcbv-quantity--wrapper {
						display: flex;
					}
					
					.wcbv-wrapper .wcbv-quantity {
						flex-basis: 155px !important;
						padding-right: 20px;
					}
					
					#wcbv-add-row {
						padding: 10px 20px;
					}
					
					.wcbv-quantity--wrapper .btn {
						border-radius: 0;
					}
					
					.wcbv-attributes-head>div.wcbv-remove {
						border-bottom: 2px solid rgba(0,0,0,.1);
					}
					
					.wcbv-quantity--wrapper input {
						width: 70px;
						height: 100% !important;
						background-color: #f1f1ef;
						border-radius: 0;
						padding-left: 15px;
						padding-right: 15px;
						text-align: center;
					}
					
					.wcbv-remove .wcbv-remove-row {
						left: -10px;
					}
					
					
					
					@media screen and (max-width: 767px) {
    					.pvtfw_variant_table_block table.variant td:before {
							padding: 20px;
						}
						
						.product-select--thumbnails {
							width: 100%;
						}
						
						.product-select--thumbnails .fpd-view-thumbnails-wrapper {
							width: 100%;
						}
						
						.product-select--thumbnails .fpd-view-thumbnails-wrapper>.fpd-item {
							width: calc(25% - 15px);
							height: auto;
							aspect-ratio: 1 / 1;
						}
						.pvtfw_init_variation_table, .pvtfw_variant_table_block, table.variant, .singleProduct__purchase {
							width: 100%;
						}
					}
					
					@media (max-width: 568px) {
						.wcbv-row .wcbv-fields {
							display: flex !important;
						}
					}
					
					@media screen and (max-width: 450px) {
						.fpd-view-thumbnails-wrapper {
							gap: 0;
/* 							margin-left: -10px;
							margin-right: -10px; */
						}
						.product-select--thumbnails .fpd-view-thumbnails-wrapper>.fpd-item {
							width: calc(50% - 20px);
							margin: 10px
						}
						
						.pvtfw_variant_table_block, table.variant, .singleProduct__purchase {
							width: 100%;
						}
						
						.pvtfw_init_variation_table {
							margin-left: 10px;
							margin-right: 10px;
							width: calc(100% - 20px);
						}
						
						.product-select--thumbnails, .fpd-navigation--color-selection .singleProduct__purchase {
							padding-left: 10px;
							padding-right: 10px;
						}
						
						.wcbv-quantity--wrapper input {
							width: 50px;
							padding-left: 10px; 
							padding-right: 10px;
						}
						
						.wcbv-fields select {
							max-width: 175px;
							min-width: 175px;
							margin-left: 0 !important;
						}
						
						.wcbv-wrapper .wcbv-selects>* {
							margin-bottom: 0;
						}
						
						.wcbv-quantity--wrapper .btn {
							padding-left: 15px;
							padding-right: 15px;
						}
					}
					
					@media screen and (max-width: 390px) {
						.wcbv-fields select {
							max-width: 150px;
							min-width: 150px;
						}
					}
				</style>

			</div>
		</div>
		
	</div>
	

<?php endif; ?>

<?php do_action( 'woocommerce_after_single_product' );?>
