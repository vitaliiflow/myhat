<?php get_header(); ?>

<section class="404">
  <div class="404__container">

    <h1><?php _e('Oops!', 'theme-name') ?></h1>
    <p><?php _e('The Page you are looking for doesn\'t exist', 'theme-name') ?></p>
    <div>
      <a href="<?php echo get_home_url() ?>"><?php _e('Back To Homepage', 'theme-name'); ?></a>
    </div>

  </div>
</section>

<?php get_footer(); ?>
