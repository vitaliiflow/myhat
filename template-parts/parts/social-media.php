<?php 

$socials = get_field('social_media_list','option');

if ($socials) : ?>

    <ul class="social-media list-style-none">

        <?php foreach ($socials as $social) : 
        
        $socialMedia = $social['social_media'];
        $socialURL = $social['social_media_url'];

        if ($socialURL) : 
        ?>
        <li class="social-media__item">
            <a href="<?php echo $socialURL;?>" target="_blank">
            <?php echo get_inline_svg($socialMedia . '.svg');?>
            </a>
        </li>
        

        <?php endif; ?>

        <?php endforeach; ?>
        
    </ul>

<?php endif; ?>