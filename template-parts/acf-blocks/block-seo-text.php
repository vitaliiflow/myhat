<?php 

$content = get_sub_field('seo_text');

if ($content) : ?>

<section class="section seo-text content-block bg-color bg-color--white">
    <div class="container">
        <div class="seo-text__content">
            <?php echo $content; ?>
        </div>
    </div>

    <div class="seo-text__opener"></div>
</section>

<?php endif; ?>