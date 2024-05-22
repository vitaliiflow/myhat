<?php 
if(is_shop() && empty($_GET['kategori'])):
    $page_id = get_option( 'woocommerce_shop_page_id' ); ;
    $page_content = get_post_field( 'post_content', $page_id );
    $content = '<h2>' . get_post_field( 'post_title', $page_id ) . '</h2>' . $page_content;
endif;
if(is_tax(  )):
    $content = category_description(get_queried_object_id());
endif;
if(!is_shop()):
    $content = get_sub_field('seo_text');
endif;
if ($content || is_shop() || is_tax()) : ?>
<section class="section seo-text content-block bg-color bg-color--white"<?php if(!empty($_GET['kategori']) || !empty(category_description(get_queried_object_id()))): ?> style="display: -webkit-box;"<?php endif; ?>>
    <div class="container">
        <div class="seo-text__content">
            <?php if(is_tax()): ?>
                <?php echo category_description(get_queried_object_id()); ?>   
            <?php elseif(!empty($_GET['kategori'])): ?>
                <?php 
                $kategori_id = get_term_by('slug', $_GET['kategori'], 'product_cat');
                echo category_description($kategori_id->term_id); 
                ?>
            <?php else: ?>
                <?php echo $content; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="seo-text__opener"></div>
</section>

<?php endif; ?>