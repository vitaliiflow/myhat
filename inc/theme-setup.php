<?php

/*
=====================
    Theme setup
=====================	
*/


function codelibry_setup(){

	load_theme_textdomain( 'theme_slug', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'menus' );
	add_theme_support( 'woocommerce' );

	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 640;

  register_nav_menus(
    array(
      'header-menu' => __('Header Menu', 'theme-name'),
      'footer-menu' => __('Footer Menu', 'theme-name'),
    )
  );
}

add_action( 'after_setup_theme', 'codelibry_setup', 0 );


//Add Icons To Menu
add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects', 10, 2);

function my_wp_nav_menu_objects( $items, $args ) {
    
    // loop
    foreach( $items as &$item ) {
        
        // vars
        $icon = get_field('menu_item_icon', $item);
        
        
        // append icon
        if( $icon ) {
            
            $item->title .= ' <img src="' . $icon['url'] . '" alt="' . $icon['title'] .'">';
			$item->classes[] .= 'has-mobile-icon';
            
        }
        
    }
    
    
    // return
    return $items;
    
}