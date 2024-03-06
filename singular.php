<?php

get_header(); ?>

<?php get_template_part('template-parts/parts/single-hero'); ?>

<div class="section pt-3">
    <div class="container">

        <?php get_template_part('template-parts/parts/breadcrumbs'); ?>

        <div class="content-block">

            <?php the_content(); ?>

        </div>
        
    </div>
</div>
<?php if( is_page( 'cart' ) || is_cart() ): ?>
    <?php echo get_template_part('template-parts/acf-blocks/block-latest-products'); ?>
<?php endif; ?>




<?php get_footer();?>