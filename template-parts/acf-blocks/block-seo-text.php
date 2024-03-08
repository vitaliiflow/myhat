<?php 
if(is_shop()):
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
if ($content || !empty(category_description(get_queried_object_id()))) : ?>
<section class="section seo-text content-block bg-color bg-color--white">
    <div class="container">
        <div class="seo-text__content">
            <?php if(is_tax()): ?>
                <?php echo category_description(get_queried_object_id()); ?>   
            <?php else: ?>
                <?php echo $content; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="seo-text__opener"></div>
</section>

<?php endif; ?>