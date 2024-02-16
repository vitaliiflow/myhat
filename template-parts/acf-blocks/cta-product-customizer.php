<?php 

$image = get_sub_field('image');
$link = get_sub_field('link');
$title = get_sub_field('title');

if ($image && $link) :

    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self'; 
    ?>

<sectioin class="section cta cta--product-customizer">

    <img src="<?php echo $image['url'];?>" alt="<?php echo $image['alt'];?>" class="img-absoolute cta__img">
    
    <div class="cta__content text-center">

        <img src="<?php echo get_template_directory_uri() . '/assets/images/blocks/linked-cards/magic-wand-white.png';?>" alt="magic wand" class="cta__icon">

        <?php if ($title) : ?>

            <h2 class="h1"><?php echo $title; ?></h2>

        <?php endif; ?>

        <a class="button button--arrow mt-2 mt-md-3" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?><?php echo get_inline_svg('arrow-right.svg');?></a>

    </div>

</sectioin>

<?php endif; ?>