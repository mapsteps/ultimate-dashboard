<?php
/**
 * Widget styles.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$settings       = get_option( 'udb_settings' );
$icon_color     = isset( $settings['icon_color'] ) ? $settings['icon_color'] : '#555555';
$headline_color = isset( $settings['headline_color'] ) ? $settings['headline_color'] : '#23282d';
?>

<?php if ( $icon_color && '#555555' !== $icon_color ) : ?>

  [id*='ms-udb'] .fa,
  [id*='ms-udb'] .dashicons,
  .udb-content-wrapper {
    color: <?php echo esc_attr( $icon_color ); ?>;
  }

  [id*='ms-udb'] .fa:hover,
  [id*='ms-udb'] .dashicons:hover {
    color: <?php echo esc_attr( $icon_color ); ?>;
  }

<?php endif; ?>

<?php if ( $headline_color && '#23282d' !== $headline_color ) : ?>

  [id*="ms-udb"] .hndle {
    color: <?php echo esc_attr( $headline_color ); ?>;
  }

<?php endif; ?>
