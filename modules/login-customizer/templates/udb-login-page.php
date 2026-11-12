<?php
/**
 * Template Name: Login Customizer
 *
 * The login screen shown in the Customizer preview of the Login Customizer.
 *
 * It mirrors the markup of the "Log In" screen of wp-login.php (WordPress 7.1), so the preview
 * looks like the real login page. It only displays the form: it never logs anyone in and handles
 * no other wp-login.php actions. When WordPress changes the login screen, update this file to match.
 *
 * Differences from core, on purpose:
 * - Ultimate Dashboard's PRO notice bar (free version only).
 * - The logo link has the "udb-login--logo-link" class (used by the preview script).
 * - wp_footer() runs, because the Customizer preview scripts are printed there.
 * - Newer core functions are only used when they exist (the plugin supports older WordPress versions).
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Only shown inside the Customizer preview. Everyone else is sent to the Customizer.
if ( ! is_customize_preview() ) {

	$udb_login_customizer_url = add_query_arg(
		array(
			'autofocus[panel]' => 'udb_login_customizer_panel',
			'return'           => rawurlencode( admin_url( 'index.php' ) ),
			'url'              => rawurlencode( home_url( 'udb-login-customizer' ) ),
		),
		admin_url( 'customize.php' )
	);

	wp_safe_redirect( $udb_login_customizer_url );
	exit;

}

// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited -- Same globals as wp-login.php, which plugins hooking into the login screen expect.
// phpcs:disable WordPress.WP.I18n.MissingArgDomain -- Core strings, translated by WordPress like on the real login page.
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Same output as wp-login.php (filtered core HTML).

global $error, $interim_login, $action;

$action        = 'login';
$interim_login = false;

/**
 * Print an inline script tag (wp_print_inline_script_tag() exists since WordPress 5.7).
 *
 * @param string $script The JavaScript.
 */
function udb_login_customizer_inline_script( $script ) {

	if ( function_exists( 'wp_print_inline_script_tag' ) ) {
		wp_print_inline_script_tag( $script );
	} else {
		echo '<script>' . $script . '</script>';
	}

}

/**
 * Print a notice above the form (wp_admin_notice() exists since WordPress 6.4).
 *
 * @param string $html    The notice content.
 * @param string $type    The notice type: error or info.
 * @param string $id      The notice ID.
 * @param array  $classes Additional classes.
 */
function udb_login_customizer_notice( $html, $type, $id, $classes = array() ) {

	if ( function_exists( 'wp_admin_notice' ) ) {
		wp_admin_notice(
			$html,
			array(
				'type'               => $type,
				'id'                 => $id,
				'additional_classes' => $classes,
				'paragraph_wrap'     => false,
			)
		);
	} else {
		echo '<div id="' . esc_attr( $id ) . '" class="notice notice-' . esc_attr( $type ) . ' ' . esc_attr( implode( ' ', $classes ) ) . '">' . $html . '</div>';
	}

}

/**
 * Output the login page header. Mirrors login_header() in wp-login.php.
 *
 * @param string   $title    The title in the <title> element.
 * @param WP_Error $wp_error The errors and messages to show.
 */
function udb_login_customizer_header( $title, $wp_error ) {

	global $error, $action;

	// Don't index the page.
	if ( function_exists( 'wp_robots_sensitive_page' ) ) {
		add_filter( 'wp_robots', 'wp_robots_sensitive_page' );
	}

	add_action(
		'login_head',
		function () {
			echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />' . "\n";
		}
	);

	$login_title = get_bloginfo( 'name', 'display' );

	/* translators: Login screen title. 1: Login screen name, 2: Network or site name. */
	$login_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress' ), $title, $login_title );

	/** This filter is documented in wp-login.php */
	$login_title = apply_filters( 'login_title', $login_title, $title );

	?><!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<title><?php echo $login_title; ?></title>
	<?php

	wp_enqueue_style( 'login' );

	/** This action is documented in wp-login.php */
	do_action( 'login_enqueue_scripts' );

	/** This action is documented in wp-login.php */
	do_action( 'login_head' );

	/** This filter is documented in wp-login.php */
	$login_header_url = apply_filters( 'login_headerurl', __( 'https://wordpress.org/' ) );

	/** This filter is documented in wp-login.php */
	$login_header_text = apply_filters( 'login_headertext', __( 'Powered by WordPress' ) );

	$classes = array( 'login-action-' . $action, 'wp-core-ui', 'admin-color-modern' );

	if ( is_rtl() ) {
		$classes[] = 'rtl';
	}

	$classes[] = 'locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_locale() ) ) );

	/** This filter is documented in wp-login.php */
	$classes = apply_filters( 'login_body_class', $classes, $action );

	?>
	</head>
	<body class="login no-js <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php
	udb_login_customizer_inline_script( "document.body.className = document.body.className.replace('no-js','js');" );
	?>

	<?php if ( ! udb_is_pro_active() ) : ?>
		<div class="udb-pro-login-customizer-notice">
			<?php esc_html_e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?>
			<a href="https://ultimatedashboard.io/docs/login-customizer/?utm_source=plugin&utm_medium=login_customizer_bar&utm_campaign=udb" class="button button-primary udb-login-customizer-pro-button" target="_blank">
				<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<?php
	/** This action is documented in wp-login.php */
	do_action( 'login_header' );
	?>
	<h1 class="screen-reader-text"><?php echo $title; ?></h1>
	<div id="login">
		<h1 role="presentation" class="wp-login-logo"><a class="udb-login--logo-link" href="<?php echo esc_url( $login_header_url ); ?>"><?php echo wp_kses_post( $login_header_text ); ?></a></h1>
	<?php

	/** This filter is documented in wp-login.php */
	$message = apply_filters( 'login_message', '' );

	if ( ! empty( $message ) ) {
		echo $message . "\n";
	}

	// In case a plugin uses $error rather than the $wp_errors object.
	if ( ! empty( $error ) ) {
		$wp_error->add( 'error', $error );
		unset( $error );
	}

	if ( $wp_error->has_errors() ) {
		$error_list = array();
		$messages   = '';

		foreach ( $wp_error->get_error_codes() as $code ) {
			$severity = $wp_error->get_error_data( $code );

			foreach ( $wp_error->get_error_messages( $code ) as $error_message ) {
				if ( 'message' === $severity ) {
					$messages .= '<p>' . $error_message . '</p>';
				} else {
					$error_list[] = $error_message;
				}
			}
		}

		if ( ! empty( $error_list ) ) {
			$errors = '';

			if ( count( $error_list ) > 1 ) {
				$errors .= '<ul class="login-error-list">';

				foreach ( $error_list as $item ) {
					$errors .= '<li>' . $item . '</li>';
				}

				$errors .= '</ul>';
			} else {
				$errors .= '<p>' . $error_list[0] . '</p>';
			}

			/** This filter is documented in wp-login.php */
			$errors = apply_filters( 'login_errors', $errors );

			udb_login_customizer_notice( $errors, 'error', 'login_error' );
		}

		if ( ! empty( $messages ) ) {
			/** This filter is documented in wp-login.php */
			$messages = apply_filters( 'login_messages', $messages );

			udb_login_customizer_notice( $messages, 'info', 'login-message', array( 'message' ) );
		}
	}

}

/**
 * Output the login page footer. Mirrors login_footer() in wp-login.php.
 */
function udb_login_customizer_footer() {

	?>
	<p id="backtoblog">
		<?php
		$html_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( home_url( '/' ) ),
			sprintf(
				/* translators: %s: Site title. */
				_x( '&larr; Go to %s', 'site' ),
				get_bloginfo( 'title', 'display' )
			)
		);

		/** This filter is documented in wp-login.php */
		echo apply_filters( 'login_site_html_link', $html_link );
		?>
	</p>
	<?php

	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link( '<div class="privacy-policy-page-link">', '</div>' );
	}

	?>
	</div><?php // End of <div id="login">. ?>

	<?php
	/** This filter is documented in wp-login.php */
	if ( apply_filters( 'login_display_language_dropdown', true ) ) {
		$languages = get_available_languages();

		if ( ! empty( $languages ) ) {
			?>
			<div class="language-switcher">
				<form id="language-switcher" method="get">

					<label for="language-switcher-locales">
						<?php esc_html_e( 'Language' ); ?><span class="dashicons dashicons-translation" aria-hidden="true"></span>
					</label>

					<?php
					$args = array(
						'id'                          => 'language-switcher-locales',
						'name'                        => 'wp_lang',
						'selected'                    => function_exists( 'determine_locale' ) ? determine_locale() : get_locale(),
						'show_available_translations' => false,
						'explicit_option_en_us'       => true,
						'languages'                   => $languages,
					);

					/** This filter is documented in wp-login.php */
					wp_dropdown_languages( apply_filters( 'login_language_dropdown_args', $args ) );
					?>

					<input type="submit" class="button" value="<?php esc_attr_e( 'Change' ); ?>">

				</form>
			</div>
			<?php
		}
	}

	/** This action is documented in wp-login.php */
	do_action( 'login_footer' );

	// The Customizer preview scripts expect this to exist.
	udb_login_customizer_inline_script( "var _customizePartialRefreshExports = '';" );

	wp_footer();

	?>
	</body>
	</html>
	<?php

}

/** This filter is documented in wp-login.php */
$login_link_separator = apply_filters( 'login_link_separator', ' | ' );

$errors = new WP_Error();

/** This filter is documented in wp-login.php */
$errors = apply_filters( 'wp_login_errors', $errors, admin_url() );

udb_login_customizer_header( __( 'Log In' ), $errors );

wp_enqueue_script( 'user-profile' );

if ( function_exists( 'wp_get_toggletip' ) ) {
	wp_enqueue_script( 'wp-tooltip' );
}

?>

<form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
	<p>
		<label for="user_login"><?php esc_html_e( 'Username or Email Address' ); ?></label>
		<input type="text" name="log" id="user_login" class="input ltr" value="" size="20" autocapitalize="off" autocomplete="username" required="required" />
	</p>

	<div class="user-pass-wrap">
		<label for="user_pass"><?php esc_html_e( 'Password' ); ?></label>
		<div class="wp-pwd">
			<input type="password" name="pwd" id="user_pass" class="input password-input ltr" value="" size="20" autocomplete="current-password" spellcheck="false" required="required" />
			<button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Show password' ); ?>">
				<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
			</button>
		</div>
	</div>
	<?php

	/** This action is documented in wp-login.php */
	do_action( 'login_form' );

	?>
	<p class="forgetmenot">
		<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
		<label for="rememberme"><?php esc_html_e( 'Remember Me' ); ?></label>
		<?php
		if ( function_exists( 'wp_get_toggletip' ) ) {
			/** This filter is documented in wp-login.php */
			$rememberme_help_text = apply_filters(
				'login_remember_me_help_text',
				__( 'Selecting "Remember Me" increases the length of time until you&#8217;re asked to log in again on this device. To keep your account secure, use this option only on your personal devices.' )
			);

			echo wp_get_toggletip(
				$rememberme_help_text,
				array(
					'id'    => 'rememberme-help-toggletip',
					'label' => __( 'Help' ),
				)
			);
		}
		?>
	</p>
	<p class="submit">
		<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Log In' ); ?>" />
		<input type="hidden" name="redirect_to" value="<?php echo esc_attr( admin_url() ); ?>" />
		<input type="hidden" name="testcookie" value="1" />
	</p>
</form>

<p id="nav">
	<?php

	if ( get_option( 'users_can_register' ) ) {
		$registration_url = sprintf( '<a class="wp-login-register" href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register' ) );

		/** This filter is documented in wp-includes/general-template.php */
		echo apply_filters( 'register', $registration_url );

		echo esc_html( $login_link_separator );
	}

	$html_link = sprintf( '<a class="wp-login-lost-password" href="%s">%s</a>', esc_url( wp_lostpassword_url() ), __( 'Lost your password?' ) );

	/** This filter is documented in wp-login.php */
	echo apply_filters( 'lost_password_html_link', $html_link );

	?>
</p>
<?php

udb_login_customizer_footer();
