<?php 


$image = get_sub_field('image');
$caption = get_sub_field('image_caption');
$content = get_sub_field('content');
$image_left = get_sub_field('image_left');

if ($image || $content) : ?>

    <section class="section image-content<?php if ($image_left) : echo ' image-content--image-left'; endif;?>">
        <div class="container">
            <div class="row justify-content-lg-between">

                <div class="col-12 col-md-6 col-lg-5 content-block image-content__content-col">

                    <?php if ($content) : ?>

                        <?php echo $content; ?>

                    <?php endif; ?>


                </div>

                <?php if( !empty( $image ) ): ?>

                    <div class="col-12 col-md-6 col-lg-5 image-content__image-col mt-4 mt-md-0">

                        <?php if ($caption) : ?>
                        
                            <div class="image-content__image-wrapper bg-color bg-color--white p-3">

                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="image-content__image"/>
                                
                                <div class="content-block text-center">
                                    <?php echo $caption; ?>
                                </div>

                            </div>
                            
                        <?php else : ?>

                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="image-content__image"/>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>

            </div>
        </div>
    </section>


<?php endif; ?>