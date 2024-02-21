<?php get_header(); ?>

<section class="error-page section">
    <div class="container">
        <div class="error-page__wrap">
            <div class="error-page__title text-center">
                <h1>404</h1>
            </div>
            <div class="error-page__subtitle text-center h2">
                <?php echo _e('Page not found');?>
            </div>
            <div class="error-page__btn d-flex justify-content-center">
                <a href="<?php echo home_url(); ?>" class="button button--lg"><?php _e('Home'); ?></a>
                
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                  <a href="<?php echo wc_get_page_permalink( 'shop' ) ?>" class="ml-3 button button--outline button--lg"><?php _e('Shop');?></a>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
