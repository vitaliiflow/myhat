<?php 
/*
Template Name: Contact
*/
get_header();
$tabs_title = get_field('tabs_title');
$form_title = get_field('form_title');
$form = get_field('form');
$address = get_field('address');
?>
<section class="contact">
    <div class="container">
        <div class="contact__content">
            <?php if(!empty(get_the_content())): ?>
                <div class="contact__text"><?php the_content(); ?></div>
            <?php endif; ?>
            <?php if($tabs_title || have_rows('tabs')): ?>
                <div class="contact__accordionList__wrapper">
                    <?php if($tabs_title): ?>
                        <h3 class="contact__accordionList__title contact-title font-family-primary">
                            <?php echo $tabs_title; ?>
                        </h3>
                    <?php endif; ?>
                    <?php if(have_rows('tabs')): ?>
                        <div class="contact__accordionList">
                            <?php while(have_rows('tabs')): the_row(); ?>
                                <?php 
                                $title = get_sub_field('tab_title');
                                $content = get_sub_field('tab_content');
                                if($title || $content):
                                ?>
                                    <div class="contact__accordionList__item">
                                        <?php if($title): ?>
                                            <h4 class="contact__accordionList__itemTitle">
                                                <span class="contact__accordionList__itemTitle__icon">
                                                    <?php echo get_inline_svg('accordion__arrow.svg'); ?>
                                                </span> 
                                                <?php echo $title; ?>
                                            </h4>
                                        <?php endif; ?>
                                        <?php if($content): ?>
                                            <div class="contact__accordionList__itemContent"><?php echo $content; ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if($form || $form_title): ?>
                <div class="contact__formWrapper">
                    <?php if($form_title): ?>
                        <h3 class="contact__formTitle contact-title font-family-primary"><?php echo $form_title; ?></h3>
                    <?php endif; ?>
                    <?php if($form): ?>
                        <div class="contact__form"><?php echo $form; ?></div>
                    <?php endif; ?>
                    <?php if($address): ?>
                        <div class="contact__address content-block">
                            <?php echo $address; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php echo get_template_part('template-parts/acf-blocks/block-latest-products'); ?>
<?php get_footer(); ?>
