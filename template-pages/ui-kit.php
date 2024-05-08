<?php
/*
Template Name: Ui Kit
*/
?>

<?php get_header(); ?>

<main class="ui-kit">
	
	

<?php get_template_part('/template-parts/ui/colors'); ?>

<?php get_template_part('/template-parts/ui/typography'); ?>

<?php get_template_part('/template-parts/ui/buttons'); ?>

<?php 
	
	$filePath = 'https://myhat.se/wp-content/themes/myhat/assets/images/icons/Facebook.svg';
	
	$content = file_get_contents($filePath); // Replace $url with the URL or file path you're trying to access
	
	

if ($content === false) {
    // file_get_contents() returned false
    echo "file_get_contents() returned false. There was an error accessing the resource.";
} elseif ($content === null) {
    // file_get_contents() returned null
    echo "file_get_contents() returned null. The resource might not exist or the content is empty.";
} else {
    // Content was successfully retrieved
    echo "Content retrieved successfully: " . $content;
	
	
}

	if (file_exists($filePath)) {
		echo 'exist';
	} else {
		echo 'notexist';
	}
	
	if (is_readable($filePath)) {
    echo "File is readable.\n";
} else {
    echo "File is not readable.\n";
}
	
	var_dump(file_exists($filePath));

	?>
	
	
</main>

<?php get_footer(); ?>
