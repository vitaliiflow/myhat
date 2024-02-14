<?php

/*
=========================
    Custom Taxonomies
=========================
*/

/**
 *
 * Register Brands Taxonomy
 * This function is moved from the old theme
 * */ 
function custom_taxonomy_brands()  {

    $labels = array(
        'name'                       => 'Varumärken',
        'singular_name'              => 'Varumärke',
        'menu_name'                  => 'Varumärken',
        'all_items'                  => 'Alla varumärken',
        'parent_item'                => 'Förälder',
        'parent_item_colon'          => 'Förälder:',
        'new_item_name'              => 'Nytt varumärkesnamn',
        'add_new_item'               => 'Nytt varumärke',
        'edit_item'                  => 'Redigera Varumärke',
        'update_item'                => 'Uppdatera Varumärke',
        'search_items'               => 'Sök Varumärke',
        'add_or_remove_items'        => 'Lägg till eller Ta bort Varumärken'
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'          => true,
        'rest_base'             => 'varumarke',
        'rest_controller_class' => 'WP_REST_Terms_Controller'
    );
    
    register_taxonomy( 'varumarke', 'product', $args );
}

add_action( 'init', 'custom_taxonomy_brands', 0 );