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

        <div class="col-12 col-lg-4 footer__newsletter content-block">

          <div class="footer__newsletter-inner">

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

        </div>

      <?php endif; ?>

      <?php if ($footerNavfooter || $rightColContent) : ?>

        <div class="col-sm col-lg-4 footer__nav">

        <?php if ($footerNav) : ?>

          <div class="accordion footer__accordion mb-5">

            <div class="accordion__list">

              <?php $i = 1; ?>

              <?php foreach($footerNav as $item): ?>

                <?php
                $label = $item['accordion_label'];
                $childElements = $item['child_elements'];
                ?>

                <div class="accordion__item mb-3 js-accordion-item">
                  <button class="accordion__button js-accordion-button h6" type="button" data-for="<?php echo $i ?>">
                    <?php echo $label ?>
                    <div class="accordion__button-icon js-accordion-button-icon"><?php echo get_inline_svg('down-chevron-svgrepo-com.svg');?></div>
                  </button>
                  <div class="accordion__body js-accordion-body" data-body="<?php echo $i ?>">
                    <div class="accordion__flex">
                      <ul class="accordion__content">

                        <?php foreach($childElements as $element): 
                          
                          $link = $element['link'];
                          if( $link ): 
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>

                            <li class="mb-1">
                              <a class="button" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                            </li>
                            
                        <?php endif; ?>

                        <?php endforeach; ?>

                      </ul>
                    </div>
                  </div>
                </div>

                <?php $i++ ?>

              <?php endforeach; ?>

            </div>

          </div>

        <?php endif; ?>

        <?php if ($footerNavfooter) : ?>

          <div class="footer__nav-footer">

            <?php echo $footerNavfooter; ?>

          </div>

        <?php endif; ?>

        </div>

      <?php endif; ?>


      <?php if ($rightColContent) : ?>

        <div class="col-sm col-lg-4 footer__content-list check-list content-block">

          <?php echo $rightColContent; ?>

        </div>

      <?php endif; ?>
    </div>
  </div>
</footer>
