<?php 


$image = get_sub_field('image');
$caption = get_sub_field('image_caption');
$content = get_sub_field('content');
$image_left = get_sub_field('image_left');

if ($image || $content) : ?>

    <section class="section image-content">
        <div class="container">
            <div class="row justify-content-lg-between">

                <div class="col-12 col-md-6 col-lg-5">

                    <?php if ($content) : ?>

                        <?php echo $content; ?>

                    <?php endif; ?>


                </div>

                <?php if( !empty( $image ) ): ?>

                    <div class="col-12 col-md-6 col-lg-5">

                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="block-image"/>

                        <?php if ($caption) : ?>

                            <?php echo $caption; ?>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>

            </div>
        </div>
    </section>


<?php endif; ?>