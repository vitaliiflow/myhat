<?php

/**
 * Template name: Teams archive page
 */

get_header(); 


$title = get_field('page_title') ? get_field('page_title') : get_the_title();
$description = get_field('page_description');

$list = get_field('leagues-list');
$taxonomy_name = 'product_cat';
?>


<div class="section teams-page pt-3">
    <div class="container">

        <?php get_template_part('template-parts/parts/breadcrumbs'); ?>

        <div class="content-block">

            <h1><?php echo $title; ?></h1>

            <?php if ($description) :?>

                <p><?php echo $description; ?></p>

            <?php endif; ?>

        </div>

        <div class="codelibry-under-dev search mt-4 mb-4">
            <form>
                <input class="text-color text-color--cream" type="text" placeholder="<?php _e('Search in our brands','codelibry');?>">
            </form>
        </div>

        <?php if ($list) : ?>

            <div class="tabs teams-page__tabs">

                <ul class="row mt-3">

                    <?php $i = 1; ?>

                    <?php foreach ($list as $item) : 

                        $category = $item['category'];
                        $name = $item['custom_name'] ? $item['custom_name'] : $category->name;
                        $id = $category->term_id;

                        ?>

                            <li class="tabsNav__item col">
                                <a class="tabs__nav text-uppercase js-tab-nav <?php if($i == '1') : echo 'active'; endif;?>" href="<?php echo "#tab" . $i; $i++;?>">
                                    <h6><?php echo $name; ?></h6>
                                </a>
                            </li>

                    <?php endforeach; ?>
                    
                </ul>

                <ul class="row">

                    <?php $c = 1; ?>
                    <?php foreach ($list as $item) : 

                        $category = $item['category'];
        
                        $terms = get_terms( array(
                            'taxonomy' => 'product_cat',
                            'orderby' => 'name',
			                'order' => 'ASC',
                            'hide_empty' => true,
                            'child_of' => $category->term_id
                        ) );

                        get_terms($args);

                        //var_dump($children);

                        // Check if products exist
                        if (! empty( $terms ) && ! is_wp_error( $terms)) : ?>
                        

                            <li id="<?php echo 'tab' . $c;?>" class="tabs__item col <?php echo 'tab' . $c;?>  <?php if($c == 1) : echo 'active'; endif;?>">

                                <div class="tabs__item-inner bg-color bg-color--white">

                                    <ul class="row mx-0 tabs__item-list">

                                    <?php foreach ( $terms as $term ) : 

                                        $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                                        $image = wp_get_attachment_url( $thumbnail_id );
                                        $name = $term->name;
                        
                                        
                                        if ($image) : ?>  
                                            <li class="col-4 col-md-3 col-lg-2 py-2 tabs__item-child-item">
                                                <a href="<?php echo $link; ?>" class="product-cat__item-link">
                        
                                                    <?php if ( $image ) : ?>
                        
                                                        <img class="teams-page__logo mb-3" src="<?php echo $image; ?>" alt="<?php echo $name . ' logo'; ?>" />
                        
                                                    <?php endif; ?>
                        
                                                    <div><?php echo $name; ?></div>
                                                </a>
                                            </li>
                                                       
                                        <?php endif; ?>


                                    <?php endforeach; ?>

                                    </ul>

                                </div>

                            </li>


                        <?php endif; ?>

                        <?php $c++; endforeach;?>

                    </ul>

            </div>

        <?php endif; ?>    
        
    </div>
</div>



<?php get_footer();?>