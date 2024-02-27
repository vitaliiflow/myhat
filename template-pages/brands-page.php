<?php

/**
 * Template name: Brands archive page
 */

get_header(); 


$title = get_field('page_title') ? get_field('page_title') : get_the_title();
$description = get_field('page_description');
?>


<div class="section brands-page pt-3">
    <div class="container">

        <?php get_template_part('template-parts/parts/breadcrumbs'); ?>

        <div class="content-block">

            <h1><?php echo $title; ?></h1>

            <?php if ($description) :?>

                <p><?php echo $description; ?></p>

            <?php endif; ?>

        </div>

        <div class="codelibry-under-dev search mt-4 mb-4">
            <form id="search-brands-form">
                <input class="text-color text-color--cream" type="text" placeholder="<?php _e('Search in our brands','codelibry');?>">
                <button type="submit" class="brands-page-search-btn text-color text-color--cream bg-color bg-color--main">
                    <img src="<?php echo get_template_directory_uri(  ) ?>/assets/images/icons/search.png" alt="">
                </button>
            </form>
            <button type="button" class="clear-search-results-btn text-color text-color--cream bg-color bg-color--main"><?php _e('Reset','codelibry');?></button>
        </div>

        

        <?php 

        $categories = get_terms(array(
            'taxonomy' => 'varumarke', // Custom taxonomy name
            'hide_empty' => true,
            'parent' => 0,
            'orderby' => 'name'
        ));



        ?>

        <?php if (!empty($categories) && !is_wp_error($categories)) :?>

            <strong>A–Ö</strong>

            <ul class="row brands-page__list">
            
            <?php 
                foreach ($categories as $category) :
                    $category_name = $category->name;
                    $category_url = get_term_link($category); // Get category URL
                    $category_description = $category->description;
                    $color = get_field('select_color', $category);
                    $logo = get_field('logo', $category);
                    
                    ?>
                    <li class="col-4 col-sm-3 col-md-2 brands-page__item">
                        <a href="<?php echo esc_url($category_url); ?>" class="brands-page__item-link">
                            <?php if ($logo) : ?>
                                <div class="img-wrapper d-flex">
                                    <img src="<?php echo $logo['url'] ?>" class="" alt="<?php echo $logo['title'] ?>">
                                </div>
                            <?php else :?>
                                <?php echo $category_name; ?>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>

            </ul>

        
    </div>
</div>



<?php get_footer();?>