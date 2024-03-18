<?php

/**
 * Template name: Teams archive page
 */

get_header(); 


$title = get_field('page_title') ? get_field('page_title') : get_the_title();
$description = get_field('page_description');

$list = get_field('leagues-list'); /*repeater: category, custom name*/
$taxonomy_name = 'team';
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
            <form id="search-teams-form">
                <input class="text-color text-color--cream" type="text" placeholder="<?php _e('Search in our brands','codelibry');?>">
                <button type="submit" class="teams-page-search-btn text-color text-color--cream bg-color bg-color--mai">
                    <img src="<?php echo get_template_directory_uri(  ) ?>/assets/images/icons/search.png" alt="">
                </button>
            </form>
            <button type="button" class="clear-search-results-btn__teams text-color text-color--cream bg-color bg-color--main"><?php _e('Reset','codelibry');?></button>
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

                        echo $category->term_id;
                       

                        $terms = get_terms( array(
                            'taxonomy' => 'team',
                            'orderby' => 'name',
			                'order' => 'ASC',
                            'hide_empty' => true,
                            'child_of' => $category->term_id
                        ) );

                        // Check if products exist
                        if (! empty( $terms ) && ! is_wp_error( $terms)) : ?>

                            <li id="<?php echo 'tab' . $c;?>" class="tabs__item col <?php echo 'tab' . $c;?>  <?php if($c == 1) : echo 'active'; endif;?>">

                                <div class="tabs__item-inner bg-color bg-color--white">

                                    <ul class="row mx-0 tabs__item-list">

                                    <?php foreach ( $terms as $term ) : 

                                        //$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                                        $image = get_field('taxonomy-image', $term);
                                        $name = $term->name;
                                        $link = get_term_link($term);
                        
                                        if ($image) : ?>
                                            <li class="col-4 col-md-3 col-lg-2 py-2 tabs__item-child-item">
                                                <a href="<?php echo $link; ?>" class="product-cat__item-link">
                        
                                                    <?php if ( $image ) : ?>

                                                        <div class="teams-page__logo-wrapper mb-3">
                                                            <img class="teams-page__logo" src="<?php echo $image['url']; ?>" alt="<?php echo $name . ' logo'; ?>" />
                                                        </div>
                        
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

            <div class="search-results"></div>

        <?php endif; ?>    
        
    </div>
</div>



<?php get_footer();?>