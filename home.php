<?php get_header(); ?>
<?php 
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 9,
    'paged' => get_query_var( 'paged' ),
);
$the_query = new WP_Query($args);
$page_title = get_the_title( get_option('page_for_posts', true) );
?>
<?php if($the_query->have_posts()): ?>
    <section class="postsPage">
        <div class="container">
            <?php if(!empty($page_title)): ?>
                <div class="postsPage__top">
                    <h1 class="postsPage__title"><?php echo $page_title; ?></h1>
                </div>
            <?php endif; ?>
            <div class="postsPage__list row">
                <?php while($the_query->have_posts()): $the_query->the_post(); ?>
                    <?php 
                    if(!empty(get_the_post_thumbnail_url(  ))):
                        $image = get_the_post_thumbnail_url();
                    else:
                        $image = get_template_directory_uri() . '/assets/images/myhat_posts_placeholder.svg'; 
                    endif;
                    ?>
                    <div class="postsPage__listItem__wrapper col-lg-4 col-sm-6 col-12">
                        <a href="<?php the_permalink() ?>" class="postsPage__listItem">
                            <div class="postsPage__listItem__top">
                                <div class="postsPage__listItem__image"><img src="<?php echo $image; ?>" alt=""></div>
                            </div>
                            <div class="postsPage__listItem__body">
                                <h4 class="postsPage__listItem__title">
                                    <?php the_title(); ?>
                                </h4>
                                <div class="postsPage__listItem__description">
                                    <?php the_excerpt(  ); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
                <div class="postsPage__paginationWrapper">
                    <div class="postsPage__pagination">
                        <?php 
                            echo paginate_links( array(
                                'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                                'total'        => $the_query->max_num_pages,
                                'current'      => max( 1, get_query_var( 'paged' ) ),
                                'format'       => '?paged=%#%',
                                'show_all'     => false,
                                'type'         => 'plain',
                                'end_size'     => 2,
                                'mid_size'     => 1,
                                'prev_next'    => true,
                                'prev_text'    => sprintf( '<i></i> %1$s', __( '', 'text-domain' ) ),
                                'next_text'    => sprintf( '%1$s <i></i>', __( '', 'text-domain' ) ),
                                'add_args'     => false,
                                'add_fragment' => '',
                            ) );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
    <section class="noPostsFound">
        <div class="container">
            <h3 class="noPostsFound__title"><?php _e('No posts was found', 'custom_text'); ?></h3>
        </div>
    </section>
<?php endif; ?>
<?php get_footer(); ?>