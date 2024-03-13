    <?php 

    /**
     * Header scripts
     */

    $bodyScriptsBottom = get_field('body_scripts_bottom','option');
    $footerScripts = get_field('footer_scripts','option');

    if ($bodyScriptsBottom) : echo $bodyScriptsBottom; endif; 

    ?>


    <?php get_template_part('template-parts/footer/footer'); ?>

    </div> <!-- THE END OF WRAPPER -->

    <?php wp_footer(); ?>

    <?php if ($footerScripts) : echo $footerScripts; endif; ?>
    <?php echo do_shortcode('[lightbox id="popup-new" width="500px" padding="0px" auto_open="true" auto_timer="30000" auto_show="once"][block id="popup-newsletter"][/lightbox]');?>
  </body>
</html> 
