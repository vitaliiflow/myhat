<?php

/*
======================
	  ACF functions
======================
*/

/*
=====================
	ACF options page
=====================
*/
if (function_exists('acf_add_options_page')) {

	acf_add_options_page(array(
		'page_title' => 'Theme General Settings',
		'menu_title' => 'Theme Settings',
		'menu_slug' => 'theme-general-settings',
		'capability' => 'edit_posts',
		'redirect' => false
	));

	acf_add_options_sub_page(array(
		'page_title' => 'Thank You Page Content',
		'menu_title' => 'Thank You Page',
		'parent_slug' => 'theme-general-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' => 'Single Product',
		'menu_title' => 'Single Product',
		'parent_slug' => 'theme-general-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' => 'Contact',
		'menu_title' => 'Contact',
		'parent_slug' => 'theme-general-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' => 'Scripts',
		'menu_title' => 'Scripts',
		'parent_slug' => 'theme-general-settings',
	));

}


/*
=====================
	ACF Flexible Template Loop
=====================
*/
function the_acf_loop()
{
	get_template_part('template-parts/loop/acf-blocks', 'loop');
}
