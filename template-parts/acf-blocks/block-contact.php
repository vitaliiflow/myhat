<?php 

$content = get_sub_field('form_content');
$form = get_sub_field('form');

$phone = get_field('phone','option');
$open_hours = get_field('open_hours','option');
$address = get_field('address','option');

if ($content || $form || $phone || $open_hours || $address) : ?>

<section class="section contact-us">
    <div class="container">
        <div class="row justify-content-lg-between">

            <?php if ($content || $phone || $open_hours || $address) : ?>

                <div class="col-md-6 col-lg-5">

                    <?php if ($content) : ?>

                        <?php echo $content; ?>

                    <?php endif; ?>

                    <?php if ($phone) : ?>

                        <?php echo $phone; ?>

                    <?php endif; ?>

                    <?php if ($open_hours) : ?>

                        <?php echo $open_hours; ?>

                    <?php endif; ?>

                    <?php if ($address) : ?>

                        <?php echo $address; ?>

                    <?php endif; ?>

                    <?php get_template_part('template-parts/parts/social-media'); ?>

                </div>

            <?php endif; ?>

            <?php if ($form) : ?> 

                <div class="col-md-6 col-lg-5">

                    <?php echo $form; ?>

                </div>

            <?php endif; ?>

        </div>
    </div>
</section>


<?php endif; ?>
