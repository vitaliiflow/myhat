<?php get_header();
$searchText=get_search_query();
?>
<div class="shopPage__top">
    <div class="container">
        <div class="shopPage__breadcrumbs"><?php do_action( 'woocommerce_before_main_content' ); ?></div></div>
    </div>
</div>
<?php get_template_part('template-parts/acf-blocks/block-seo-text'); ?>
<div class="shopPage">
    <div class="container">
        <div class="shopPage__content">
            <?php 
            $varumarke_terms = get_terms(
                array(
                    'taxonomy' => 'varumarke',
                    'hide_empty' => true,
                )
            );
            $pa_storlek = get_terms(array(
                'taxonomy' => 'pa_storlek',
                'hide_empty' => true,
            ));

            $tags = get_terms(
                array(
                    'taxonomy' => 'product_tag',
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
                $_GET['orderby'] = 'popularity';
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
                                <div class="shopPage__filtersRow__listItem<?php if(!isset($_GET['orderby']) || $_GET['orderby'] == 'popularity'){echo ' active';} ?>">
                                    <div class="shopPage__filtersRow__listItem__checkbox"></div>
                                    <div class="shopPage__filtersRow__listItem__name" data-slug="popularity"><?php _e('Sortera efter popularitet', 'woocommerce'); ?></div>
                                </div>
                                <div class="shopPage__filtersRow__listItem<?php if($_GET['orderby'] == 'rating'){echo ' active';} ?>">
                                    <div class="shopPage__filtersRow__listItem__checkbox"></div>
                                    <div class="shopPage__filtersRow__listItem__name" data-slug="rating"><?php _e('Sortera efter genomsnittligt betyg', 'woocommerce'); ?></div>
                                </div>
                                <div class="shopPage__filtersRow__listItem<?php if($_GET['orderby'] == 'date'){echo ' active';} ?>">
                                    <div class="shopPage__filtersRow__listItem__checkbox"></div>
                                    <div class="shopPage__filtersRow__listItem__name" data-slug="date"><?php _e('Sortera efter senast', 'woocommerce'); ?></div>
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
                            <div class="shopPage__filtersRow__list__apply button button--black mobile-toggle"><?php _e('APPLY', 'woocommerce'); ?></div>
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
                            <div class="shopPage__filtersRow__listClose mobile-toggler"></div>
                            <?php 
                            if ( !empty($varumarke_terms) && !is_wp_error( $varumarke_terms ) ):
                            ?>
                                <div class="shopPage__filtersRow__listItem opened" data-attr-name="varumarke">
                                    <div class="shopPage__filtersRow__listItem__title">VARUMÄRKE</div>
                                    <div class="shopPage__filtersRow__listItem__sublist" style="display: block;">
                                        <div class="shopPage__filtersRow__listItem__sublistItems">
                                            <?php foreach($varumarke_terms as $term): ?>
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
                                                    <div class="shopPage__filtersRow__listItem__sublistItem__description"><?php echo category_description($term->term_id); ?></div>
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
            <?php 
            $orderby = 'popularity';
            $order = 'ASC';
            $settedOrder = 'popularity';

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            
            $args = array(
                'post_type' => 'product',
                'post_status'    => array( 'publish' ),
                'posts_per_page' => 16,
                's'     => $searchText,
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
            );


            $the_query = new WP_Query($args); ?>
            <?php if($the_query->have_posts()): ?>
                <div class="shopPage__list" data-paged="<?php echo $paged; ?>" data-sort="<?php echo $settedOrder; ?>"<?php if(sizeof($varumarke) > 1 || $varumarke[0] != ''): ?> data-varumarke="<?php echo implode(',', $varumarke); ?>"<?php endif; ?><?php if(sizeof($storek) > 1 || $storek[0] != ''): ?> data-storek="<?php echo implode(',', $storek); ?>"<?php endif; ?><?php if(sizeof($taggar) > 1 || $taggar[0] != ''): ?> data-taggar="<?php echo implode(',', $taggar); ?>"<?php endif; ?><?php if(sizeof($kategori) > 1 || $kategori[0] != ''): ?> data-kategori="<?php echo implode(',', $kategori); ?>"<?php endif; ?><?php if($searchText): ?> data-search="<?php echo $searchText; ?>"<?php endif; ?>>
                    <ul class="products column-4">
                        <?php while($the_query->have_posts()): $the_query->the_post(); ?>
                            <div class="shopPage__listItem col-6 col-md-3">
                                <?php wc_get_template_part( 'content', 'product' ); ?>
                            </div>
                        <?php endwhile;?> 
                    </ul>
                    <div class="shopPage__pagination">
                        <div class="shopPage__paginationButton prev<?php if($paged == 1){ echo ' disabled';} ?>"><?php echo get_inline_svg('pagination-arrow-right.svg'); ?>Föregående</div>
                        <div class="shopPage__paginationPage">
                            <span class="current"><?php echo $paged ?></span>
                            <span>/</span>
                            <span class="total"><?php echo $the_query->max_num_pages; ?></span>
                        </div>
                        <div class="shopPage__paginationButton next<?php if($the_query->max_num_pages <= 1 || $current == $the_query->max_num_pages){echo ' disabled';} ?>">Nästa<?php echo get_inline_svg('pagination-arrow-right.svg'); ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>



<?php get_footer(); ?>