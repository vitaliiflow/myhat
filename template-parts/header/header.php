<?php 
$logo = get_field('header_logo', 'options');
?>
<header class="header" id="header">
  <div class="container">
    <div class="header__content">
      <?php if($logo): ?>
        <div class="header__logo"><a href="<?php echo get_home_url(  ); ?>"><img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['title']; ?>"></a></div>
      <?php endif; ?>
      
      <div class="header__menu">

        <?php 
		  if (!is_cart() && !is_checkout()) {
			if (has_nav_menu('header-menu')) :
			  wp_nav_menu(array('theme_location' => 'header-menu')); 
			endif; 

			if (has_nav_menu('languages-menu')) :
			  wp_nav_menu(array('theme_location' => 'languages-menu'));
			endif; 
		  }
        ?>
        
      </div>
      <div class="header__iconsList">
        <div class="header__iconsList__item search">
          <img src="<?php echo get_template_directory_uri(  ) ?>/assets/images/icons/search.png" alt="">
          <div class="search__barWrapper">
            <form class="search-form" method="get" role="search" action="<?php echo get_home_url(); ?>">
                <div class="search__bar">
                  <input type="search" id="search-input" name="s" value="" class="search-input" placeholder="Search...">
                </div>
                <div class="search__btn">
                    <input type="submit" name="id">
                </div>
            </form>
            <div class="search__resultsList">
            </div>
          </div>
        </div>
        <div class="header__iconsList__item cart">

          <?php if ( class_exists( 'WooCommerce' ) ) : ?>
            <a href="<?php echo wc_get_cart_url(); ?>"><img src="<?php echo get_template_directory_uri(  ) ?>/assets/images/icons/shopping-bag.png" alt="">
            <div class="header__iconsList__item cart__count"><?php echo count( WC()->cart->get_cart() ) ?></div>
          </a>
          <?php endif; ?>
        </div>
      </div>
      <div class="header__burger header--toggler">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <div class="header__mobileMenu">
        <div class="header__mobileMenu__top">
          <?php if($logo): ?>
            <div class="header__logo"><a href="<?php echo get_home_url(); ?>"><img src="<?php echo $logo['url'] ?>" alt="<?php echo $logo['title']; ?>"></a></div>
          <?php endif; ?>
			<?php if (!is_cart() && !is_checkout()) : ?>
          		<div class="header__close header--toggler"></div>
		    <?php endif; ?>
        </div>
		<?php if (!is_cart() && !is_checkout()) : ?>
        <div class="header__menu">
          <?php wp_nav_menu(array('theme_location' => 'header-menu')); ?>
        </div>
		<?php endif; ?>
        <?php if(have_rows('mobile_after_menu_links', 'options')): ?>
          <div class="header__linksList">
            <?php while(have_rows('mobile_after_menu_links', 'options')): the_row(); ?>
              <?php 
              $link = get_sub_field('link');
              if($link):
              ?>
                <div class="header__linksList__item"><a href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a></div>
              <?php endif; ?>
            <?php endwhile; ?>
          </div>
        <?php endif; ?>
        <!-- <div class="header__languages header__menu"><?php wp_nav_menu(array('theme_location' => 'languages-menu')); ?></div> -->
      </div>
    </div>
  </div>
</header>