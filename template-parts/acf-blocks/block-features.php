<?php 

$list = get_sub_field('icons_list');

if ($list) : ?>


<section class="section">
    <div class="container">
        <div class="row">
            <?php foreach ($list as $item) :

                $link = $item['link'];
                $image = $item['icon'];
                $content = $item['content'];
                
                ?>

                <div class="col">

                    <?php if ($content) : ?>

                        <?php echo $content; ?>

                    <?php endif; ?>

                    <?php if( !empty( $image ) ): ?>
                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                    <?php endif; ?>


                    <?php if( $link ): 
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                        <a class="button" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php endif; ?>