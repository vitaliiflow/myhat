<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>


<div class="shopPage__filtersRow">
    <div class="shopPage__filtersRow__item filter">
        <div class="shopPage__filtersRow__itemTitle"><span><img src="<?php echo get_template_directory_uri(  ) ?>/assets/images/icons/settings.png" alt="">Filter</span></div>
        <div class="shopPage__filtersRow__itemElements"></div>
    </div>
    <div class="shopPage__filtersRow__item sort">
        <div class="shopPage__filtersRow__itemTitle"><span>Sortering</span></div>
        <div class="shopPage__filtersRow__itemElements"></div>
    </div>
</div>