<?php 

/**
 * 
 */

$title = get_sub_field('section_title');
$list = get_sub_field('product_brands_list');
$link = get_sub_field('link');

if ($list ) : ?>

<section class="section product-cat product-cat--brand bg-color bg-color--white">
    <div class="container">

        <?php if ($title || $link) : ?>

            <div class="section__title">

            <?php if ($title) : ?>
                <h2 class="text-center"><?php echo $title; ?></h2>
            <?php endif; ?>

            <?php if ($link) :
                        
                $link_url = $link['url'];
                $link_title = $link['title'];
                $link_target = $link['target'] ? $link['target'] : '_self';
                ?>

                <a class="button button--arrow mt-3" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?><?php echo get_inline_svg('arrow-right.svg');?></a>

            <?php endif; ?>

            </div>

        <?php endif; ?>

        <ul class="row justify-content-center product-cat--brand-slider">

            <?php foreach ($list as $item) : 

                $category = $item['category'];
                
                $name = $item['custom_name'] ? $item['custom_name'] : $category->name;
                $id = $category->term_id;
                $link = get_term_link($id);

                $image = get_field('logo' ,$category  );

                if ($image) : ?>

                    <li class="product-cat__item col-auto col-lg-2">
                        <a href="<?php echo $link; ?>" class="product-cat__item-link text-center">

                            <?php if ( $image ) : ?>

                                <img class="product-cat__img mb-2" src="<?php echo $image['url']; ?>" alt="<?php echo $name . ' logo'; ?>" />

                             <?php endif; ?>

                        </a>
                    </li>

                <?php endif; ?>

            <?php endforeach; ?>
            
        </ul>
    </div>
</section>

<?php endif; ?>