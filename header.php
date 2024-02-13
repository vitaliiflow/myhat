<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php function_exists('wp_body_open') ? wp_body_open() : do_action( 'wp_body_open' ); ?>

<div class="wrapper">

  <?php get_template_part('template-parts/header/header');
