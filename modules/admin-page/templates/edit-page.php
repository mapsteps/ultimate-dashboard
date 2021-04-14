<?php
/**
 * Template Name: Ultimate Dashboard Admin Page
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<!DOCTYPE html>
<html>
<head>
	<?php wp_head(); ?>
</head>
<body spellcheck="false" <?php body_class(); ?>>

	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	}
	?>

	<?php wp_footer(); ?>
</body>
</html>
