<?php

$title = get_sub_field('title');
$link = get_sub_field('link');
$quantity = get_sub_field('quantity_of_products_to_show');

// Define the query arguments
$args = array(
    'limit' => 5,
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

            <ul class="latest-products__list latest-products__list-slider products row">

            <?php foreach ($products as $product_id) :
                // Load product
                $product = wc_get_product($product_id);
                if (!$product) {
                    continue;
                }
                
                // Setup global product data
                global $post, $product;
                $post = get_post($product_id);
                setup_postdata($post);

                ?>

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

<?php endif; ?>

