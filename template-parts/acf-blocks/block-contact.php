<?php 

$content = get_sub_field('form_content');
$form = get_sub_field('form');

$phone = get_field('phone','option');
$open_hours = get_field('open_hours','option');
$address = get_field('address','option');

$socials = get_field('social_media_list','option');

if ($content || $form || $phone || $open_hours || $address) : ?>

<section class="section contact-us">
    <div class="container">
        <div class="row justify-content-lg-between">

            <?php if ($content || $phone || $open_hours || $address) : ?>

                <div class="col-md-6 col-lg-5 content-block">

                    <?php if ($content) : ?>

                        <?php echo $content; ?>

                    <?php endif; ?>

                    <div class="row">

                        <?php if ($phone || $address) : ?>

                            <div class="col-sm-6">

                                <?php if ($phone) : ?>

                                    <div class="contact-us__item">

                                        <div class="h4 font-weight font-weight--500 mb-2"><?php _e('Tel:','codelibry');?></div>

                                        <?php echo $phone; ?>

                                    </div>

                                <?php endif; ?>

                                <?php if ($address) : ?>

                                    <div class="contact-us__item">

                                        <div class="h4 font-weight font-weight--500 mb-2"><?php _e('Address:','codelibry');?></div>

                                        <?php echo $address; ?>

                                    </div>

                                <?php endif; ?>

                            </div>

                        <?php endif; ?>

                        <?php if ($open_hours || $socials) : ?>


                            <div class="col-sm-6">

                                <?php if ($open_hours) : ?>

                                    <div class="contact-us__item">

                                        <div class="h4 font-weight font-weight--500 mb-2"><?php _e('Open hours:','codelibry');?></div>

                                        <?php echo $open_hours; ?>

                                    </div>

                                <?php endif; ?>

                                <?php if ($socials) : ?>

                                    <div class="contact-us__item">

                                        <div class="h4 font-weight font-weight--500 mb-2"><?php _e('Social Media:','codelibry');?></div>

                                        <?php get_template_part('template-parts/parts/social-media'); ?>

                                    </div>


                                <?php endif; ?> 

                            </div>

                        <?php endif; ?>


                    </div>                    

                </div>

            <?php endif; ?>

            <?php if ($form) : ?> 

                <div class="col-md-6 col-lg-5 mt-3 mt-md-0">

                    <?php echo $form; ?>

                </div>

            <?php endif; ?>

        </div>
    </div>
</section>


<?php endif; ?>
