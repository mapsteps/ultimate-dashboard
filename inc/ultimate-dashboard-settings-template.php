<?php
/**
 * Settings Page Template
 *
 * @package Ultimate Dashboard
 */
 
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="post" action="options.php">
		<?php
			settings_fields( 'udb-settings-group' );
			do_settings_sections( 'ultimate-dashboard' );
			submit_button();
		?>
	</form>

</div>