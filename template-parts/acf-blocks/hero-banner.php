<?php 

/**
 * The slider block. Each slide have individual link. 
 * 
 */

$small_copy = get_sub_field('small_copy');
$big_copy = get_sub_field('big_copy');
$bg_desktop = get_sub_field('image_desktop');
$bg_mobile = get_sub_field('image_mobile');
$link = get_sub_field('link');

if ($link || $bg_desktop) : 

    $link_url = $link['url'];
    $link_target = $link['target'] ? $link['target'] : '_self';

    ?>

<section class="hero-banner">


    <a class="section" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">

        <picture class="img-absoolute hero-banner__img" fetchpriority="high">

            <!-- For screens 768px and larger -->
            <source media="(min-width: 768px)" srcset="<?php echo $bg_desktop['url'];?>">

            <?php if ($bg_mobile) : ?>

                <!-- For screens smaller than 768px -->
                <source media="(max-width: 767px)" srcset="<?php echo $bg_mobile['url'];?>" fetchpriority="high">
			
			<?php else : ?>
			
				<!-- For screens smaller than 768px -->
                <source media="(max-width: 767px)" srcset="<?php echo $bg_desktop['sizes']['home-banner'];?>" fetchpriority="high">

            <?php endif; ?>

            <!-- Fallback image for browsers that do not support srcset -->
            <img src="<?php echo $bg_desktop['url'];?>" alt="<?php echo $bg_desktop['alt'];?>" class="img-absoolute cta__img">

        </picture>

        <?php if ($small_copy || $big_copy) : ?>

            <div class="container">

                <div class="col-md-5 hero-banner__content-wrapper text-color text-color--white">

                    <?php if ($small_copy) : ?>

                        <div class="h6 mb-3"><?php echo $small_copy; ?></div>

                    <?php endif; ?>

                    <?php if ($big_copy) : ?>

                        <div class="h1"><?php echo $big_copy; ?> <?php get_inline_svg('arrow-right.svg');?></div>

                    <?php endif; ?>

                </div>
            </div>

        <?php endif; ?>

    </a>
    
</section>

<?php endif; ?>