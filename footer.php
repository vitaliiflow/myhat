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
   
  </body>
</html> 
