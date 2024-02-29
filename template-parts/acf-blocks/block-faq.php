<?php 

$tabs_title = get_field('tabs_title');

?>

<?php if(have_rows('tabs')): ?>

    <section class="section faq-opened bg-color bg-color--white">
        <div class="container">


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
                                    
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>

        </div>
    </section>

 <?php endif; ?>