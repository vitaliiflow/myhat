<?php

/*
==============================
    Add Styles And Scripts
==============================
*/


function codelibry_enqueue () {

  $DEVELOPMENT = true; // change to false if PRODUCTION

  $ABSOLUTE_DIST = get_template_directory() . '/dist'; // Absolute path to the dist folder
  $DIST = get_template_directory_uri() . '/dist'; // Dir to the dist theme folder

  if($DEVELOPMENT) {
    
    $style_version = filemtime( "{$ABSOLUTE_DIST}/main.min.css" );
    $vendor_version = filemtime( "{$ABSOLUTE_DIST}/vendor.min.js" );
    $custom_version = filemtime( "{$ABSOLUTE_DIST}/main.min.js" );

  } else {

    $style_version = '1.0.0';
    $vendor_version = '1.0.0';
    $custom_version = '1.0.0';

  }

  if (is_cart()) : 

    // Separate CSS for cart page to fix render blocking speed

    wp_enqueue_style( 'cart', "{$DIST}/cart.min.css", array(), $style_version, 'all' ); // main css

  else : 

    wp_enqueue_style( 'main', "{$DIST}/main.min.css", array(), $style_version, 'all' ); // main css

  endif; 


  wp_enqueue_script( 'vendor', "{$DIST}/vendor.min.js", array('jquery'), $vendor_version, true ); // vendor js
  wp_enqueue_script( 'main', "{$DIST}/main.min.js", array('vendor'), $custom_version, true ); // main js


  $localized_strings = array(
       'searching' => __('Search...', 'codelibry'),
       'resetError'   => __('Unable to reset. Please try again.', 'codelibry'),
       'searchError'   => __('Error during search. Please try again.', 'codelibry'),
       'ajaxError'    => __('Error during reset. Please try again.', 'codelibry'),
       'noTeamsFound'    => __('No teams found.', 'codelibry'),
       'noBrandsFound'    => __('No brands found.', 'codelibry'),
  );


  wp_localize_script( 'main', 'codelibry',
    array( 
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'ajax_nonce' => wp_create_nonce( "secure_nonce_name" ),
      'site_url' => get_site_url(),
      'theme_url' => get_template_directory_uri(),
      'strings' => $localized_strings,
    )
  );
}

add_action('wp_enqueue_scripts', 'codelibry_enqueue');
