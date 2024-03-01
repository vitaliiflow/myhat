<?php 

$list = get_sub_field('icons_list');
$title = get_sub_field('section_title');

if ($list) : ?>


<section class="section features bg-color bg-color--white">
    <div class="container">


        <?php if ($title) : ?>

            <div class="section__title text-center">

                <h2><?php echo $title; ?></h2>

            </div>

        <?php endif; ?>

        <ul class="row justify-content-center">
            <?php foreach ($list as $item) :

                $link = $item['link'];
                $image = $item['icon'];
                $content = $item['content'];
                
                if( !empty( $image ) ): ?>

                <li class="col-6 col-sm-auto features__item">


                        <?php if( $link ): 

                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>


                            <a class="d-block features__item-wrapper text-center" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
                        
                                
                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                            
                                <?php if ($content) : ?>

                                    <div class="features__item-content mt-3"><?php echo $content; ?></div>

                                <?php endif; ?>

                               

                            </a>

                        <?php else : ?>


                            <div class="features__item-wrapper text-center">

                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />

                                <?php if ($content) : ?>

                                    <div class="features__item-content mt-3"><?php echo $content; ?></div>

                                <?php endif; ?>

                            </div>


                        <?php endif; ?>

                
                </li>

                <?php endif; ?>

            <?php endforeach; ?>
        </ul>
    </div>
</section>

<?php endif; ?>