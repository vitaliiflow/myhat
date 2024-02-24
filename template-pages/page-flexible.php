<?php

/**
 * Template name: Flexible template
 */

get_header();

?>

<?php if (!is_front_page()) : ?>

    <div class="breadcrumbs__wrapper pt-3">
        <div class="container">
            <?php get_template_part('template-parts/parts/breadcrumbs'); ?>
        </div>
    </div>

<?php endif; ?>


<?php the_acf_loop();?>


<?php get_footer();?>