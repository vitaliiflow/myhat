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
<?php 
$varumarke_terms = get_terms(
    array(
        'taxonomy' => 'varumarke',
        'hide_empty' => true,
    )
);
if(get_locale() == "sv_SE"){
    $size_name = "pa_storlek";
} elseif(get_locale() == "nb_NO") {
    $size_name = "pa_storrelse";
}
$pa_storlek = get_terms(array(
    'taxonomy' => $size_name,
    'hide_empty' => true,
));

$tags = get_terms(
    array(
        'taxonomy' => 'product_tag',
        'hide_empty' => true,
    )
);
$color = get_terms(
    array(
        'taxonomy' => 'color',
        'hide_empty' => true,
    )
);
$team = get_terms(
    array(
        'taxonomy' => 'team',
        'hide_empty' => true,
    )
);
$categories = get_terms(
    array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    )
);

if(empty($_GET['orderby'])){
    $_GET['orderby'] = 'date';
}
?>
<div class="shopPage__filters sort-wrapper desktop-lg">
    <div class="shopPage__filtersRow">  
        <div class="shopPage__filtersRow__pillsList">
        </div>
        <div class="shopPage__filtersRow__item sort">
            <div class="shopPage__filtersRow__itemTitle mobile-toggler"><span>Sortering</span></div>
            <div class="shopPage__filtersRow__itemOverlay mobile-toggler"></div>
            <div class="shopPage__filtersRow__listWrapper">
                <div class="shopPage__filtersRow__listClose mobile-toggler"></div>
                <div class="shopPage__filtersRow__list">
                    <div class="shopPage__filtersRow__listItem<?php if(!isset($_GET['orderby']) || $_GET['orderby'] == 'date'){echo ' active';} ?>">
                        <div class="shopPage__filtersRow__listItem__checkbox"></div>
                        <div class="shopPage__filtersRow__listItem__name" data-slug="date"><?php _e('Sortera efter senast', 'woocommerce'); ?></div>
                    </div>
                    <div class="shopPage__filtersRow__listItem<?php if($_GET['orderby'] == 'popularity'){echo ' active';} ?>">
                        <div class="shopPage__filtersRow__listItem__checkbox"></div>
                        <div class="shopPage__filtersRow__listItem__name" data-slug="popularity"><?php _e('Sortera efter popularitet', 'woocommerce'); ?></div>
                    </div>
                    <div class="shopPage__filtersRow__listItem<?php if($_GET['orderby'] == 'rating'){echo ' active';} ?>">
                        <div class="shopPage__filtersRow__listItem__checkbox"></div>
                        <div class="shopPage__filtersRow__listItem__name" data-slug="rating"><?php _e('Sortera efter genomsnittligt betyg', 'woocommerce'); ?></div>
                    </div>
                    <div class="shopPage__filtersRow__listItem<?php if($_GET['orderby'] == 'price'){echo ' active';} ?>">
                        <div class="shopPage__filtersRow__listItem__checkbox"></div>
                        <div class="shopPage__filtersRow__listItem__name" data-slug="price"><?php _e('Sortera efter pris: lågt till högt', 'woocommerce'); ?></div>
                    </div>
                    <div class="shopPage__filtersRow__listItem<?php if($_GET['orderby'] == 'price-desc'){echo ' active';} ?>">
                        <div class="shopPage__filtersRow__listItem__checkbox"></div>
                        <div class="shopPage__filtersRow__listItem__name" data-slug="price-desc"><?php _e('Sortera efter pris: högt till lågt', 'woocommerce'); ?></div>
                    </div>
                </div>
                <div class="shopPage__filtersRow__list__apply button button--black mobile-toggler"><?php _e('APPLY', 'woocommerce'); ?></div>
                <div class="shopPage__filtersRow__itemClose mobile-toggle"></div>
            </div>
        </div>
    </div>
</div>
<div class="shopPage__filters filters-wrapper desktop-lg">
    <div class="shopPage__filtersRow">
        <div class="shopPage__filtersRow__item filter">
            <div class="shopPage__filtersRow__itemTitle mobile-toggler">
                <span><img src="<?php echo get_template_directory_uri(  ) ?>/assets/images/icons/settings.png" alt="">Filter</span>
                <div class="shopPage__filtersRow__itemTitle__emptyContent">
                    <?php 
                    $page_id = get_option( 'woocommerce_shop_page_id' ); ;
                    $page_content = get_post_field( 'post_content', $page_id );
                    $content = '<h2>' . get_post_field( 'post_title', $page_id ) . '</h2>' . $page_content;
                    echo $content;
                    ?>
                </div>
            </div>
            <div class="shopPage__filtersRow__itemOverlay mobile-toggler"></div>
            <div class="shopPage__filtersRow__listWrapper">
                <div class="shopPage__filtersRow__listClose mobile-toggler refreshed"></div>
                <?php 
                if ( !empty($varumarke_terms) && !is_wp_error( $varumarke_terms ) ):
                ?>
                    <div class="shopPage__filtersRow__listItem" data-attr-name="varumarke">
                        <div class="shopPage__filtersRow__listItem__title">VARUMÄRKE</div>
                        <div class="shopPage__filtersRow__listItem__sublist">
                            <div class="shopPage__filtersRow__listItem__sublistItems">
                                <?php foreach($varumarke_terms as $term): ?>
                                    <?php 
                                    $parent_term_slug = '';
                                    if ($term->parent != 0) {
                                        $parent_term = get_term($term->parent, 'product_cat');
                                        $parent_term_slug = $parent_term->slug;
                                    }
                                    ?>
                                    <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term->slug; ?>"<?php if(!empty($parent_term_slug)): ?> data-parent="<?php echo $parent_term_slug; ?>"<?php endif; ?>>
                                        <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                        <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term->name; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php 
                if ( !empty($pa_storlek) && !is_wp_error( $pa_storlek ) ):
                ?>
                    <div class="shopPage__filtersRow__listItem" data-attr-name="storek">
                        <div class="shopPage__filtersRow__listItem__title">STORLEK</div>
                        <div class="shopPage__filtersRow__listItem__sublist">
                            <div class="shopPage__filtersRow__listItem__sublistItems">
                                <?php foreach($pa_storlek as $term): ?>
                                    <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term->slug; ?>">
                                        <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                        <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term->name; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php 
                if ( !empty($tags) && !is_wp_error( $tags ) ):
                ?>
                    <div class="shopPage__filtersRow__listItem" data-attr-name="taggar">
                        <div class="shopPage__filtersRow__listItem__title">TAGGAR</div>
                        <div class="shopPage__filtersRow__listItem__sublist">
                            <div class="shopPage__filtersRow__listItem__sublistItems">
                                <?php foreach($tags as $term): ?>
                                    <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term->slug; ?>">
                                        <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                        <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term->name; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php 
                if ( !empty($color) && !is_wp_error( $color ) ):
                ?>
                    <div class="shopPage__filtersRow__listItem" data-attr-name="color">
                        <div class="shopPage__filtersRow__listItem__title">FÄRG</div>
                        <div class="shopPage__filtersRow__listItem__sublist">
                            <div class="shopPage__filtersRow__listItem__sublistItems">
                                <?php foreach($color as $term): ?>
                                    <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term->slug; ?>">
                                        <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                        <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term->name; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php 
                if ( !empty($team) && !is_wp_error( $team ) ):
                ?>
                    <div class="shopPage__filtersRow__listItem" data-attr-name="team">
                        <div class="shopPage__filtersRow__listItem__title">TEAM</div>
                        <div class="shopPage__filtersRow__listItem__sublist">
                            <div class="shopPage__filtersRow__listItem__sublistItems">
                                <?php foreach($team as $term): ?>
                                    <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term->slug; ?>">
                                        <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                        <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term->name; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php 
                if ( !empty($categories) && !is_wp_error( $categories ) ):
                ?>
                    <div class="shopPage__filtersRow__listItem" data-attr-name="kategori">
                        <div class="shopPage__filtersRow__listItem__title">KATEGORI</div>
                        <div class="shopPage__filtersRow__listItem__sublist">
                            <div class="shopPage__filtersRow__listItem__sublistItems">
                                <?php foreach($categories as $term): ?>
                                    <div class="shopPage__filtersRow__listItem__sublistItem" data-slug="<?php echo $term->slug; ?>">
                                        <div class="shopPage__filtersRow__listItem__sublistItem__checkbox"></div>
                                        <div class="shopPage__filtersRow__listItem__sublistItem__name"><?php echo $term->name; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="shopPage__filtersRow__list__apply">
                    <div class="btn button--black">APPLY</div>
                </div>
            </div>
        </div>
    </div>
</div>