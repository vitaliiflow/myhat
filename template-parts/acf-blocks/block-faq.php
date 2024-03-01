<?php 

$tabs_title = get_sub_field('tabs_title');

?>

<?php if(have_rows('tabs')): ?>

    <section class="section faq-opened bg-color bg-color--white">
        <div class="container">

                <?php if($tabs_title): ?>

                    <div class="section__title">
                        <h2><?php echo $tabs_title; ?></h2>
                    </div>

                <?php endif; ?>



                <ul class="faq-opened__list row justify-content-lg-between">

                    <?php while(have_rows('tabs')): the_row(); 

                        $title = get_sub_field('tab_title');
                        $content = get_sub_field('tab_content');

                        if($title && $content): ?>

                            <li class="col-md-6 col-lg-5 faq-opened__item">

                                <h4 class="mb-3"><?php echo $title; ?></h4>
                                <p><?php echo $content; ?></p>

                            </li>
                            
                        <?php endif; ?>

                    <?php endwhile; ?>
                </ul>


        </div>
    </section>

 <?php endif; ?>