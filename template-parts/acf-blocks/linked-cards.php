<?php 

$list = get_sub_field('cards');
$title = get_sub_field('section_title');
$link = get_sub_field('link');
if ($list) : ?>

<section class="section cards-list bg-color bg-color--white">
    <div class="container">

        <?php if ($title || $link) : ?>

            <div class="section__title">

                <?php if ($title) : ?>

                    <h2 class="text-center"><?php echo $title; ?></h2>
                    
                    <?php if ($link) :
                    
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>

                        <a class="button button--arrow mt-3" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?><?php echo get_inline_svg('arrow-right.svg');?></a>

                    <?php endif; ?>

                <?php endif; ?>

            </div>
        
        <?php endif; ?>

        <ul class="row cards-list__list">

            <?php foreach ($list as $item) : 
                
                $image = $item['image'];
                $title = $item['title'];
                $link = $item['link'];
                
                if ($image && $link) :  
                    
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    $link_url = $link['url'];

                    ?>

                    <li class="cards-list__item col-lg-4">

                        <a href="<?php echo esc_url( $link_url ); ?>" class="cards-list__item-inner" target="<?php echo esc_attr( $link_target ); ?>">
                            
                            <img src="<?php echo $image['url'];?>" alt="<?php echo $image['alt'];?>" class="img-absoolute cards-list__img">

                                <div class="cards-list__content text-center">

                                    <?php if ($title) : ?>

                                        <h3 class="mb-2"><?php echo $title; ?></h3>

                                    <?php endif; ?>
                                    
                                    <div class="h6 cards-list__link"><?php echo $link['title'];?><?php echo get_inline_svg('arrow-right.svg');?></div>
                                
                                </div>

                            </a>

                    </li>

                <?php endif; ?>

            <?php endforeach; ?>
        </ul>
    </div>
</section>


<?php endif; ?>