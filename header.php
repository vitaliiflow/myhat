<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <?php wp_head(); ?>
	

    <?php 

    /**
     * Header scripts
     */

    $headScripts = get_field('header_scripts','option');
    $bodyScripts = get_field('body_scripts_top','option');

	if ($headScripts) : echo $headScripts; endif; 

	?>

</head>

<body <?php body_class(); ?>>

    <?php if ($bodyScripts) : echo $bodyScripts; endif; ?>

    <?php function_exists('wp_body_open') ? wp_body_open() : do_action( 'wp_body_open' ); ?>

    <?php get_template_part('template-parts/header/header'); ?>

    <div class="wrapper">