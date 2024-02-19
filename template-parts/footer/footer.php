<?php

/**
 *  Showing the footer menu
 *  Everything is editable with ACF theme options
 */

$newsletter = get_field('newsletter_title','option'); // title + contact form shortcode
$socials = get_field('social_media_list','option');
$footerNav = get_field('footer_accordion_navigation','option');
$footerNavfooter = get_field('footer_navigation_content','option');
$rightColContent = get_field('right_tab_content','option');
?>

<footer id="footer" class="footer section bg-color bg-color--dark text-white">
  <div class="container footer__container">
    <div class="row">

      <?php if ($newsletter || $socials) : ?>

        <div class="col col-lg-4 footer__newsletter content-block">

          <?php echo $newsletter; ?>

          <?php if ($socials) : ?>

            <ul class="social-media">

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

        </div>

      <?php endif; ?>

      <?php if ($footerNavfooter || $rightColContent) : ?>

        <div class="col col-lg-4 footer__nav">

        <?php if ($footerNavfooter) : ?>

          <div class="footer__nav-footer">

            <?php echo $footerNavfooter; ?>

          </div>

        <?php endif; ?>

        </div>

      <?php endif; ?>


      <?php if ($rightColContent) : ?>

        <div class="col col-lg-4 footer__content-list check-list content-block">

          <?php echo $rightColContent; ?>

        </div>

      <?php endif; ?>
    </div>
  </div>
</footer>
