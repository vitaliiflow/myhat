<?php

$title = get_sub_field('title');
$link = get_sub_field('link');
$quantity = get_sub_field('quantity_of_products_to_show');

$slider = get_sub_field('enable_slider');

// Define the query arguments
$args = array(
    'limit' => $quantity,
    'orderby' => 'date',
    'order' => 'DESC',
    'status' => 'publish',
    'return' => 'ids', // Optional: If you only need IDs, or 'objects' for product objects
);

// The Query
$product_query = new WC_Product_Query($args);
$products = $product_query->get_products();

// Check if products exist
if (!empty($products)) : ?>

    <section class="section latest-products">

        <div class="container">

            <?php if ($title || $link) : ?>

                <div class="section__title">

                    <?php if ($title) : ?>

                        <h2 class="text-center"><?php echo $title; ?></h2>

                    <?php endif; ?>
                        
                    <?php if ($link) :
                    
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>

                        <a class="button button--arrow mt-3" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?><?php echo get_inline_svg('arrow-right.svg');?></a>

                    <?php endif; ?>

                </div>

            <?php endif; ?>

            <ul class="row products latest-products__list<?php if ($slider) : echo ' latest-products__list-slider'; endif; ?>">

                <?php foreach ($products as $product_id) :
                    // Load product
                    $product = wc_get_product($product_id);

                    // Ensure visibility.
                    if ( empty( $product ) || ! $product->is_visible() ) {
                        continue;
                    }
                    
                    // Setup global product data
                    global $post, $product;
                    $post = get_post($product_id);
                    setup_postdata($post);
                    ?>

                    <li class="shopPage__listItem latest-products__item<?php if ($slider) : echo ' col-lg-auto'; else : echo ' col-6 col-sm-4 col-md-3'; endif;?>">

                        <?php wc_get_template_part('content', 'product'); ?>

                    </li>


                    <?php 
                    // Reset the global post data
                    wp_reset_postdata();
                endforeach; ?>

            </ul>

        </div>

    </section>

<?php endif; ?>

