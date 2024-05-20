<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width initial-scale=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	
	
    <?php wp_head(); ?>
	
	<?php if (is_front_page()) : ?>
	
<!-- 	<link rel="preload" href="https://yourdomain.com/your-lcp-image-mobile.jpg" as="image" media="(min-width: 768px)">
	<link rel="preload" href="https://myhat.se/wp-content/uploads/2024/03/Webbanner2-2-1.webp" as="image" media="(max-width: 768px)"> -->
	
	<?php endif;?>

    <?php 

    /**
     * Header scripts
     */

    $headScripts = get_field('header_scripts','option');
    $bodyScripts = get_field('body_scripts_top','option');

	if ($headScripts) : echo $headScripts; endif; 

	
	?>

</head>
<?php 
$class = '';
if(is_home()):
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $posts = $the_query->found_posts;
    if($posts == 0):
        $class = 'posts_empty_page';
    endif;
    wp_reset_postdata(  );
endif;
?>
<body <?php body_class($class); ?>>

    <?php if ($bodyScripts) : echo $bodyScripts; endif; ?>

    <?php function_exists('wp_body_open') ? wp_body_open() : do_action( 'wp_body_open' ); ?>

    <?php get_template_part('template-parts/header/header'); ?>

    <div class="wrapper">