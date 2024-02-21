<?php 

/**
 * 
 */

$title = get_sub_field('section_title');
$list = get_sub_field('product_team_list');

if ($list ) : ?>

<section class="section tabs">
    <div class="container">

        <?php if ($title) : ?>
            <h2 class="section__title text-center"><?php echo $title; ?></h2>
        <?php endif; ?>

        <ul class="row justify-content-center tabs__labels-slider">

            <?php $i = 1; ?>

            <?php foreach ($list as $item) : 

                $category = $item['category'];
                $name = $item['custom_name'] ? $item['custom_name'] : $category->name;
                $id = $category->term_id;

                ?>

                    <li class="tabsNav__item mb-4 col-auto">
                        <a class="tabs__nav js-tab-nav <?php if($i == '1') : echo 'active'; endif;?>" href="<?php echo "#tab" . $i; $i++;?>">
                            <h6><?php echo $name; ?></h6>
                        </a>
                    </li>

            <?php endforeach; ?>
            
        </ul>

        <ul>

            <?php $c = 1; ?>

            <?php foreach ($list as $item) : 

                $category = $item['category'];
                $id = $category->term_id;


                // Define the query arguments
                $args = array(
                    'limit' => 3,
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'status' => 'publish',
                    'return' => 'ids', // Optional: If you only need IDs, or 'objects' for product objects
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'term_id',
                            'terms' => $id,
                            'operator' => 'IN'
                        )
                    ),
                    'meta_key' => 'total_sales', // Sorting by total sales
                );

                // The Query
                $product_query = new WC_Product_Query($args);
                $products = $product_query->get_products();

                // Check if products exist
                if (!empty($products)) : ?>

                    <li id="<?php echo 'tab' . $c;?>" class="tabs__item <?php echo 'tab' . $c;?>  <?php if($c == 1) : echo 'active'; endif;?>">

                        <div class="tabs__item-inner">

                            <ul class="latest-products__list products row" data-category="<?php echo $category->name;?>">

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


                                    <?php  wp_reset_postdata();

                                endforeach; ?>

                            </ul>

                        </div>

                    </li>


                <?php endif; ?>

            <?php $c++; endforeach;?>

        </ul>

    </div>
</section>

<?php endif; ?>

