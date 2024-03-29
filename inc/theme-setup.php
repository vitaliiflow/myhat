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
      'languages-menu' => __('Languages Menu', 'theme-name'),
    )
  );
  add_image_size( 'home-category-icons', 180, 180, true ); // 300 pixels wide (and unlimited height)
	add_image_size( 'home-banner', 850, 700, true ); // 300 pixels wide (and unlimited height)
}

add_action( 'after_setup_theme', 'codelibry_setup', 0 );


//Add Icons To Menu
add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects', 10, 2);

function my_wp_nav_menu_objects( $items, $args ) {
    
    // loop
    foreach( $items as $item ) {
        
        // vars
        $icon = get_field('menu_item_icon_checkbox', $item);
        
        // append icon
        if( $icon ) {
            $image_id = get_term_meta( $item->object_id, 'thumbnail_id', true );
            $post_thumbnail_img = wp_get_attachment_image_src( $image_id, 'full' );
            
            
            if (is_array($post_thumbnail_img)) { // Check if $post_thumbnail_img is an array
                $item->classes[] = 'has-mobile-icon '; // No need for `.=` here
                $item->title .= '<img src="' . $post_thumbnail_img[0] . '"/>';
                
            } else  {
              $term = get_term($item->object_id);
              $team_thumbnail = get_field('taxonomy-image', $term );

              $brand_thumbnail = get_field('logo', $term);

              //var_dump($team_thumbnail)

              if (!empty($team_thumbnail)) {
                $item->classes[] = 'has-mobile-icon '; // No need for `.=` here
                $item->title .= '<img src="' . $team_thumbnail['url'] . '"/>';
              }

              if (!empty($brand_thumbnail)) {
                $item->classes[] = 'has-mobile-icon '; // No need for `.=` here
                $item->title .= '<img src="' . $brand_thumbnail['url'] . '"/>';
              }
              
            }
      }
    }
    
    // return
    return $items;
}
