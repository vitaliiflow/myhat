<?php

get_header(); ?>


<div class="section pt-3">
    <div class="container">

        <?php get_template_part('template-parts/parts/breadcrumbs'); ?>

        <div class="content-block">

            <?php the_content(); ?>

        </div>
        
    </div>
</div>



<?php get_footer();?>