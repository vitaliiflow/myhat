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


function cptui_register_my_taxes_team() {

	/**
	 * Taxonomy: Teams.
	 */

	$labels = [
		"name" => esc_html__( "Teams", "codelibry" ),
		"singular_name" => esc_html__( "Team", "codelibry" ),
	];

	
	$args = [
		"label" => esc_html__( "Teams", "codelibry" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'team', 'with_front' => true, 'hierarchical' => true],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "team",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => true,
		"show_in_graphql" => false,
	];
	register_taxonomy( "team", [ "product" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_team' );



function cptui_register_my_taxes_color() {

	/**
	 * Taxonomy: Colors.
	 */

	$labels = [
		"name" => esc_html__( "Colors", "codelibry" ),
		"singular_name" => esc_html__( "Color", "codelibry" ),
	];

	
	$args = [
		"label" => esc_html__( "Colors", "codelibry" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'color', 'with_front' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "color",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => true,
		"show_in_graphql" => false,
	];
	register_taxonomy( "color", [ "product" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_color' );


