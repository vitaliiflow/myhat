<?php
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

            <ul class="products row">

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

                <div class="shopPage__listItem col-6 col-md-2">

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