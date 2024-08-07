<?php

require get_template_directory().'/inc/theme-setup.php';
require get_template_directory().'/inc/theme-support.php';
require get_template_directory().'/inc/theme-enqueue.php';

require get_template_directory().'/inc/custom-post-types.php';
require get_template_directory().'/inc/custom-taxonomies.php';

require get_template_directory().'/inc/acf.php';
require get_template_directory().'/inc/theme-functions.php';

require get_template_directory().'/inc/custom-functions-from-old-theme.php';
require get_template_directory().'/inc/theme-ajax.php';
require get_template_directory().'/inc/theme-woocommerce.php';

require get_template_directory().'/inc/theme-optimization.php';
require get_template_directory().'/inc/theme-permalinks.php'; // Redirects for products and product cat