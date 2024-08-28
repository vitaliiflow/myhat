<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}
$link = explode('%#%', $base);
$prev = $current - 1;
$next = $current + 1;


$searchText = '';
if(!empty($_GET['s'])):
    $searchText = $_GET['s'];
endif;

if(!empty($_GET['varumarke_cat'])):
    $varumarke = explode(',', $_GET['varumarke_cat']);
else:
    $varumarke = array('');
endif;
if(!empty($_GET['storek'])):
    $storek = explode(',', $_GET['storek']);
else:
    $storek = array('');
endif;
if(!empty($_GET['taggar'])):
    $taggar = explode(',', $_GET['taggar']);
else:
    $taggar = array('');
endif;
if(!empty($_GET['teams'])):
    $team = explode(',', $_GET['teams']);
else:
    $team = array('');
endif;
if(!empty($_GET['colors'])):
    $color = explode(',', $_GET['colors']);
else:
    $color = array('');
endif;
if(!empty($_GET['kategori'])):
    $kategori = explode(',', $_GET['kategori']);
else:
    $kategori = array('');
endif;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$current_term = get_queried_object();

if(!empty($current_term->slug)):
    $term_id = $current_term->slug;
endif;
if(!empty($current_term->taxonomy)):
    $taxonomy_slug = $current_term->taxonomy;
endif;
if(isset($_GET['orderby'])):
    switch($_GET['orderby']):
        case 'popularity':
            $orderby = 'popularity';
            $order = 'ASC';
            $metaKey = '';
            break;
        case 'rating':
            $orderby = 'meta_value_num';
            $metaKey = '_wc_average_rating';
            $order = 'ASC';
            break;
        case 'date':
            $orderby = 'publish_date';
            $order = 'DESC';
            $metaKey = '';
            break;
        case 'price':
            $orderby = 'meta_value_num';
            $metaKey = '_price';
            $order = 'ASC';
            break;
        case 'price-desc':
            $orderby = 'meta_value_num';
            $metaKey = '_price';
            $order = 'DESC';
            break;
    endswitch;
    $settedOrder = $_GET['orderby'];
else:
    $orderby = 'publish_date';
    $metaKey = '';
    $order = 'DESC';
    $settedOrder = 'date';
endif;


$args = array(
    'post_type' => 'product',
    'post_status'    => array( 'publish' ),
    'posts_per_page' => 16,
    'paged' => $paged,
    'orderby' => $orderby,
    'order' => $order,
    'meta_query' => array(
        array(
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '=',
        ),
    ),
    'tax_query' => array(),
);

if(!empty($searchText) || $searchText != ''){
    $args['specific_chars'] = $searchText;
}

if(!empty($metaKey)){
    $args['meta_key'] = $metaKey;
}


if(get_locale() == "sv_SE"){
    $size_name = "pa_storlek";
} elseif(get_locale() == "nb_NO") {
    $size_name = "pa_storrelse";
} elseif(get_locale() == 'de_DE'){
    $size_name = "pa_groesse";
} elseif(get_locale() == 'da_DK'){
    $size_name = "pa_stoerrelse";
} elseif(get_locale() == 'fi'){
    $size_name = "pa_koko";
}

if(!empty($term_id) && !empty($taxonomy_slug)) {
    if(gettype($term_id) == 'string'){
        $term_id = [$term_id];
    }

    if($taxonomy_slug == 'varumarke' && (sizeof($varumarke) > 1 || $varumarke[0] != '')){
        $term_id = array_merge( $term_id, $varumarke );
    }
    if($taxonomy_slug == 'varumarke'){
        $varumarke = $term_id;
    }

    if($taxonomy_slug == $size_name && (sizeof($storek) > 1 || $storek[0] != '')){
        $term_id = array_merge( $term_id, $storek );
    }
    if($taxonomy_slug == $size_name){
        $storek = $term_id;
    }

    if($taxonomy_slug == 'product_tag' && (sizeof($taggar) > 1 || $taggar[0] != '')){
        $term_id = array_merge( $term_id, $taggar );
    }
    if($taxonomy_slug == 'product_tag'){
        $taggar = $term_id;
    }
    
    if($taxonomy_slug == 'team' && (sizeof($team) > 1 || $team[0] != '')){
        $term_id = array_merge( $term_id, $team );
    }
    if($taxonomy_slug == 'team'){
        $team = $term_id;
    }

    if($taxonomy_slug == 'color' && (sizeof($color) > 1 || $color[0] != '')){
        $term_id = array_merge( $term_id, $color );
    }
    if($taxonomy_slug == 'color'){
        $color = $term_id;
    }

    if($taxonomy_slug == 'product_cat' && (sizeof($kategori) > 1 || $kategori[0] != '')){
        $term_id = array_merge( $term_id, $kategori );
    }
    if($taxonomy_slug == 'product_cat'){
        $kategori = $term_id;
    }


    $tax_array = array(
        'taxonomy' => $taxonomy_slug, 
        'field' => 'slug',
        'terms' => $term_id 
    );


    array_push($args["tax_query"], $tax_array);
}


if((sizeof($varumarke) > 1 || $varumarke[0] != '') && $taxonomy_slug != 'varumarke'){
    $varumarke__arr = array(
        'taxonomy' => 'varumarke', 
        'field' => 'slug',
        'terms' => $varumarke 
    );
    array_push($args["tax_query"], $varumarke__arr);
}
if((sizeof($storek) > 1 || $storek[0] != '') && $taxonomy_slug != $size_name){
    $storek__arr = array(
        'taxonomy' => $size_name, 
        'field' => 'slug',
        'terms' => $storek 
    );
    array_push($args["tax_query"], $storek__arr);
}
if((sizeof($taggar) > 1 || $taggar[0] != '') && $taxonomy_slug != 'product_tag'){
    $taggar__arr = array(
        'taxonomy' => 'product_tag', 
        'field' => 'slug',
        'terms' => $taggar 
    );
    array_push($args["tax_query"], $taggar__arr);
}
if((sizeof($team) > 1 || $team[0] != '') && $taxonomy_slug != 'team'){
    $team_arr = array(
        'taxonomy' => 'team', 
        'field' => 'slug',
        'terms' => $team 
    );
    array_push($args["tax_query"], $team_arr);
}
if((sizeof($color) > 1 || $color[0] != '') && $taxonomy_slug != 'color'){
    $color_arr = array(
        'taxonomy' => 'color', 
        'field' => 'slug',
        'terms' => $color 
    );
    array_push($args["tax_query"], $color_arr);
}
if((sizeof($kategori) > 1 || $kategori[0] != '') && $taxonomy_slug != 'product_cat'){
    $kategori_arr = array(
        'taxonomy' => 'product_cat', 
        'field' => 'slug',
        'terms' => $kategori 
    );
    array_push($args["tax_query"], $kategori_arr);
}
$the_query = new WP_Query($args);

if(empty(get_queried_object()->taxonomy)){
    $link = wc_get_page_permalink( 'shop' );
} else {
    $link = get_term_link(get_queried_object()->slug, get_queried_object()->taxonomy);
}
$prev = $current - 1;
$next = $current + 1;
?>
<div class="shopPage__pagination">
    <a href="<?php if($prev > 0){echo $link . 'page/' . $prev . '/';} else{echo 'javascript:void(0);';} ?>" rel="previous" class="shopPage__paginationButton prev<?php if($current == 1){ echo ' disabled';} ?>"><?php echo get_inline_svg('pagination-arrow-right.svg'); ?>Föregående</a>
    <div class="shopPage__paginationPage">
        <span class="current"><?php echo $current ?></span>
        <span>/</span>
        <span class="total"><?php echo $the_query->max_num_pages; ?></span>
    </div>
    <a href="<?php if($next <= $the_query->max_num_pages) {echo $link . 'page/' . $next . '/';} else{echo 'javascript:void(0);';} ?>" rel="next" class="shopPage__paginationButton next<?php if($the_query->max_num_pages <= 1 || $current == $the_query->max_num_pages){echo ' disabled';} ?>">Nästa<?php echo get_inline_svg('pagination-arrow-right.svg'); ?></a>
</div>
