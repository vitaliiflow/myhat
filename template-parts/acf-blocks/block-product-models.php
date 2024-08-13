<?php 

/**
 * Same block, as product categories but with different front-end
 */

$title = get_sub_field('section_title');
$list = get_sub_field('product_models_list');
$link = get_sub_field('link');

if ($list ) : ?>

<section class="section product-cat product-cat--model">
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

        <ul class="row justify-content-center">

            <?php foreach ($list as $item) : 

                $category = $item['category'];
                
                $name = $item['custom_name'] ? $item['custom_name'] : $category->name;
                $id = $category->term_id;
                $link = get_term_link($id);

                $thumbnail_id = get_term_meta( $id, 'thumbnail_id', true );
                $image = wp_get_attachment_url( $thumbnail_id );

                if (empty($image)) : 
					$image = $item['taxonomy-image']['url'];
				endif;

                if ($image) : ?>

                    <li class="product-cat__item col-auto">
                        <a href="<?php echo $link; ?>" class="product-cat__item-link">

                            <?php if ( $image ) : ?>

                                <img class="product-cat__img mb-3" src="<?php echo $image; ?>" alt="<?php echo $name . ' image'; ?>" />

                             <?php endif; ?>

                            <div><?php echo $name; ?></div>
                        </a>
                    </li>

                <?php endif; ?>

            <?php endforeach; ?>
            
        </ul>
    </div>
</section>

<?php endif; ?>