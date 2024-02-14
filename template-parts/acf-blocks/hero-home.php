<?php 

/**
 * The slider block. Each slide have individual link. 
 * 
 */

$slides = get_sub_field('slides');

if ($slides) : ?>

<section class="section hero-home slider-full">
    <div class="container">
        <ul class="slider-full__list">

            <?php foreach ($slides as $slide) : 
                
                $smallCopy = $slide['small_copy'];
                $bigCopy = $slide['big_copy'];
                $link = $slide['link'];
                $image = $slide['image'];
                ?>

                <li class="slider-full__item">

                <?php if ($link) : 
                    
                    $link_url = $link['url'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    
                    ?>

                    <a class="row hero-home__item-inner" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">

                        <div class="col-md-5 hero-home__content-wrapper">
                        
                        <?php if ($smallCopy) : ?>

                            <div class="h6 mb-3"><?php echo $smallCopy; ?></div>

                        <?php endif; ?>
                    
                        <?php if ($bigCopy) : ?>

                            <div class="h1"><?php echo $bigCopy; ?> <?php echo '1' . get_inline_svg('arrow-right.svg');?></div>
                        
                        <?php endif; ?>

                        </div>

                        <div class="col-md-7 hero-home__image-wrapper">

                            <?php if (!empty( $image )) : ?>
                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                            <?php endif; ?>


                        </div>
                    </a>
                
                <?php else : ?>

                    Same markup as above
                
                <?php endif; ?> 
                </li>
            
            <?php endforeach; ?>

            </ul>
        </div>
    </section>

<?php endif; ?>