<?php get_header(); ?>
<section class="searchBanner my-5">
    <div class="container">
        <div class="searchBanner__content">
            <h1 class="searchBanner__title">Search for: <?php echo get_search_query(); ?></h1>
        </div>
    </div>
</section>
<section class="search spacing--sm">
    <div class="container">
        <div class="search__posts">
        <?php 
        $searchText=get_search_query();

        $args = array(
            'public'   => true,
            '_builtin' => false
        );
        $output = 'names';
        $operator = 'and';
        $post_types = get_post_types( $args, $output, $operator );
        if ( $post_types ) {
            foreach ( $post_types  as $post_type ) {
                $args = array(
                    'posts_per_page' => -1,
                    's'     => $searchText,
                );
                $pt_name = get_post_type_object($post_type);
                ?>
                <?php $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) { ?>
                    <?php $i = 0;
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post(); ?>
                        <?php if(get_post_type(get_the_ID()) == $post_type):
                        $i++;
                        endif;
                    }
                    if($i > 0):
                    ?>
                    <div class="search__postsList__wrapper">
                        <h2 class="search__postsList__title mb-4"><?php echo $pt_name->label; ?></h2>
                        <div class="search__postsList row">
                            <?php
                            while ( $the_query->have_posts() ) {
                                $the_query->the_post(); ?>
                                    <?php if(get_post_type(get_the_ID()) == $post_type): ?>
                                        <?php if($post_type == 'product'): ?>
                                            <div class="search__postsList__item col-lg-3 col-md-4 col-sm-6 col-12 mb-5">
                                                <?php 
                                                $image = get_the_post_thumbnail_url( );
                                                if(empty($image)):
                                                    $image = get_template_directory_uri() . '/assets/images/elementor-placeholder-image.webp';
                                                endif;
                                                ?>
                                                <div class="search__postsList__itemImage mb-3"><img src="<?php echo $image; ?>" alt="" class="img-absoolute"></div>
                                                <h3 class="search__postsList__itemTitle mb-3"><?php the_title(); ?></h3>
                                                <div class="search__postsList__itemDesc mb-3"><?php the_excerpt(); ?></div>
                                                <div class="search__postsList__itemLink link__wrapper">
                                                    <a href="<?php the_permalink(); ?>" class="link">Read More</a>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="search__postsList__item col-lg-3 col-md-4 col-sm-6 col-12 mb-5">
                                                <h3 class="search__postsList__itemTitle mb-3"><?php the_title(); ?></h3>
                                                <?php if(!empty(get_the_excerpt())): ?>
                                                    <div class="search__postsList__itemDesc mb-3"><?php the_excerpt(); ?></div>
                                                <?php endif; ?>
                                                <div class="search__postsList__itemLink link__wrapper">
                                                    <a href="<?php the_permalink(); ?>" class="link">Read More</a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php } ?>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="is-ajax-search-no-result">
                            <div class="no-results"><?php echo 'Nothing was found'; ?></div>
                        </div>
                    <?php endif; ?>

                <?php }

                else{ ?>
                <?php }

            }

            $args = array(
                'posts_per_page' => -1,
                's'     => $searchText,
            );
            $the_query = new WP_Query($args);
            $num = 0;
            if($the_query->have_posts()):
                while($the_query->have_posts()): $the_query->the_post();
                    $num++;
                endwhile;
            endif;
            ?>
            <?php if($num == 0): ?>
                <div class="is-ajax-search-no-result">
                    <div class="no-results"><?php echo 'Nothing was found'; ?></div>
                </div>
            <?php endif;

        }
        ?>
            
        </div>
    </div>
</section>



<?php get_footer(); ?>