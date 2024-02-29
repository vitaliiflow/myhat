<?php 

$image = get_sub_field('image');

if( !empty( $image ) ): ?>

    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="block-image"/>

<?php endif; ?>


