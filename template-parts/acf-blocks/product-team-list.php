<?php 

/**
 * 
 */ 

$title = get_sub_field('section_title');
$list = get_sub_field('product_team_list');
$link = get_sub_field('link');

if ($list ) : ?>

<section class="section tabs product-cat product-cat--team">
    <div class="container">

        <div class="section__title">

            <?php if ($title) : ?>
                <h2 class="text-center"><?php echo $title; ?></h2>
            <?php endif; ?>

            <?php if ($link) :
                        
                $link_url = $link['url'];
                $link_title = $link['title'];
                $link_target = $link['target'] ? $link['target'] : '_self';
                ?>

                <a class="button button--arrow mt-3 mb-4" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?><?php echo get_inline_svg('arrow-right.svg');?></a>

            <?php endif; ?>

            <ul class="row justify-content-between tabs__labels-slider mt-3">

                <?php $i = 1; ?>

                <?php foreach ($list as $item) : 

                    $category = $item['category'];
                    // $name = $item['custom_name'] ? $item['custom_name'] : $category->name;
                    $name = $category->name;
                    $id = $category->term_id;

                    ?>

                        <li class="tabsNav__item mb-4 col-auto">
                            <a class="tabs__nav text-uppercase js-tab-nav <?php if($i == '1') : echo 'active'; endif;?>" href="<?php echo "#tab" . $i; $i++;?>">
                                <h6><?php echo $name; ?></h6>
                            </a>
                        </li>

                <?php endforeach; ?>
                
            </ul>

        </div>

        

        <ul>

            <?php $c = 1; ?>
            <?php foreach ($list as $item) : 

                $category = $item['category'];
                $id = $category->term_id;
                $category_name = $category->name;
                $category_url = get_term_link($category); // Get category URL
                //var_dump($category);

                // Define the query arguments
                $args = array(
                    'limit' => 6,
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'status' => 'publish',
                    'return' => 'ids', // Optional: If you only need IDs, or 'objects' for product objects
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'team',
                            'field' => 'term_id',
                            'terms' => $id,
                            'operator' => 'IN'
                        )
                    ),
                    'meta_key' => 'total_sales', // Sorting by total sales
                    'meta_query' => array(
                        array(
                            'key' => '_stock_status',
                            'value' => 'instock',
                            'compare' => '='
                        )
                    )
                );

                // The Query
                $product_query = new WC_Product_Query($args);
                $products = $product_query->get_products();

                $count = count($products);

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
                                    
                                    
                                    if ($product && $product->get_stock_status() == 'instock') :

                                    //Setup global product data
                                    global $post, $product;
                                    $post = get_post($product_id);
                                    setup_postdata($post);
                                    ?>


                                        <div class="shopPage__listItem latest-products__item col-6 col-sm-4 col-lg-auto">

                                            <?php wc_get_template_part('content', 'product'); ?>

                                        </div>

                                    <?php endif; ?>
                                    <?php  wp_reset_postdata();

                                endforeach; ?>

                            </ul>

                            <?php if ($count > 5) : ?>
                                
                                <a class="button button--arrow mt-3" href="<?php echo esc_url( $category_url ); ?>" target="_self"><?php echo esc_html( __('View all', 'codelibry') . ' ' . esc_html($category_name) ); ?>
<?php echo get_inline_svg('arrow-right.svg');?></a>

                            <?php endif; ?>
                        </div>

                    </li>


                <?php endif; ?>

            <?php $c++; endforeach;?>

        </ul>

    </div>
</section>

<?php endif; ?>

