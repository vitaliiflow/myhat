<?php 

/**
 * Same block, as product categories but with different front-end
 */

$title = get_sub_field('section_title');
$list = get_sub_field('product_models_list');

if ($list ) : ?>

<section class="section product-cat product-cat--model">
    <div class="container">

        <?php if ($title) : ?>
            <h2 class="section__title text-center"><?php echo $title; ?></h2>
        <?php endif; ?>

        <ul class="row justify-content-center">

            <?php foreach ($list as $item) : 

                $category = $item['category'];
                
                $name = $item['custom_name'] ? $item['custom_name'] : $category->name;
                $id = $category->term_id;
                $link = get_term_link($id);

                $thumbnail_id = get_term_meta( $id, 'thumbnail_id', true );
                $image = wp_get_attachment_url( $thumbnail_id );

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