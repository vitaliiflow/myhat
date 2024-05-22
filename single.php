<?php get_header(); ?>
<?php 
if(!empty(get_the_post_thumbnail_url(  ))):
    $image = get_the_post_thumbnail_url();
else:
    $image = get_template_directory_uri() . '/assets/images/myhat_posts_placeholder.svg'; 
endif;
?>
<section class="postPage">
    <div class="container">
        <h2 class="postPage__title"><?php the_title(); ?></h2>
        <div class="postPage__image"><img src="<?php echo $image; ?>" alt=""></div>
        <div class="postPage__text"><?php the_content(); ?></div>
    </div>
</section>
<?php get_footer(); ?>