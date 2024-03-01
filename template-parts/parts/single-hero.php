<?php 

$enable_hero = get_field('enable_hero');
$image = get_field('background');

if ($enable_hero) : ?>

<section class="single-hero">

    <?php if( !empty( $image ) ): ?>

        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="single-hero__img"/>

    <?php else : ?>

        <img src="<?php echo get_template_directory_uri() . '/assets/images/single-hero/hero-2.jpg';?>" alt="<?php echo get_the_title() . ' Image';?>" class="single-hero__img">

    <?php endif; ?>

    <h1><?php the_title(); ?></h1>

</section>

<?php endif; ?>