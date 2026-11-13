<?php
/**
 * Widget styles.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$settings       = get_option( 'udb_settings' );
$icon_color     = isset( $settings['icon_color'] ) ? strtolower( $settings['icon_color'] ) : '';
$headline_color = isset( $settings['headline_color'] ) ? strtolower( $settings['headline_color'] ) : '';

// The defaults are WordPress' own text & headline colors (dashboard.css, WordPress), so nothing is printed for them.
// #555555 & #23282d were the defaults before 3.9: saved untouched, they mean "default" too.
$icon_color     = in_array( $icon_color, array( '#3c434a', '#555555' ), true ) ? '' : $icon_color;
$headline_color = in_array( $headline_color, array( '#1d2327', '#23282d' ), true ) ? '' : $headline_color;
?>

<?php if ( $icon_color ) : ?>

	[id*='ms-udb'] .udb-icon,
	[id*='ms-udb'] .dashicons,
	.udb-content-wrapper {
		color: <?php echo esc_attr( $icon_color ); ?>;
	}

	[id*='ms-udb'] .udb-icon:hover,
	[id*='ms-udb'] .dashicons:hover {
		color: <?php echo esc_attr( $icon_color ); ?>;
	}

<?php endif; ?>

<?php if ( $headline_color ) : ?>

	[id*="ms-udb"] .hndle {
		color: <?php echo esc_attr( $headline_color ); ?>;
	}

<?php endif; ?>

/* 
 * List styling for widgets.
 * Ensures bullets (disc) and numbers (decimal) are visible by adding padding,
 * which is often removed by default WordPress dashboard styles.
 */
.udb-content-wrapper ul,
.udb-html-wrapper ul {
	list-style-type: disc;
	padding-left: 20px;
	margin: 1em 0;
}

.udb-content-wrapper ol,
.udb-html-wrapper ol {
	list-style-type: decimal;
	padding-left: 20px;
	margin: 1em 0;
}

/* 
 * Support for nested lists.
 */
.udb-content-wrapper ul ul,
.udb-content-wrapper ol ul,
.udb-html-wrapper ul ul,
.udb-html-wrapper ol ul {
	list-style-type: circle;
}

/* 
 * Fix for centered lists.
 * Prevents list indicators (bullets/numbers) from being misaligned when 
 * the parent container uses text-align: center. 
 */
[style*="text-align: center"] ul,
[style*="text-align: center"] ol,
[style*="text-align:center"] ul,
[style*="text-align:center"] ol {
	display: inline-block;
	text-align: left;
}

/* 
 * Basic form styling for HTML widgets.
 * Ensures labels and inputs are clearly separated and responsive.
 */
.udb-html-wrapper form {
	margin: 10px 0;
}

.udb-html-wrapper label {
	display: inline-block;
	margin-bottom: 5px;
	font-weight: 600;
}

.udb-html-wrapper input[type="text"],
.udb-html-wrapper input[type="email"],
.udb-html-wrapper textarea {
	width: 100%;
	box-sizing: border-box;
}
