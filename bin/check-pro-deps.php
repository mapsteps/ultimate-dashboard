<?php
/**
 * Checks that the free plugin still provides everything Ultimate Dashboard PRO relies on.
 *
 * Usage: php bin/check-pro-deps.php
 *
 * The list lives in bin/pro-deps.json, the explanation in PRO-DEPENDENCIES.md.
 * Dev only, never shipped.
 *
 * @package Ultimate_Dashboard
 */

// phpcs:disable WordPress.WP.AlternativeFunctions, WordPress.WP.GlobalVariablesOverride, WordPress.Security.EscapeOutput.OutputNotEscaped -- Command line tool.

if ( 'cli' !== PHP_SAPI ) {
	exit( 1 );
}

$root = dirname( __DIR__ );
$deps = json_decode( file_get_contents( __DIR__ . '/pro-deps.json' ), true );

// Read all PHP files of the free plugin.
$sources  = array();
$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $root, FilesystemIterator::SKIP_DOTS ) );

foreach ( $iterator as $file ) {
	$path = str_replace( '\\', '/', substr( $file->getPathname(), strlen( $root ) + 1 ) );

	if ( 'php' !== $file->getExtension() || preg_match( '#^(vendor|node_modules|bin)/#', $path ) ) {
		continue;
	}

	$sources[ $path ] = file_get_contents( $file->getPathname() );
}

$all      = implode( "\n", $sources );
$failures = array();

/**
 * Find the file that declares a class (by fully qualified name).
 *
 * @param array  $sources The free plugin's PHP files.
 * @param string $fqn     Fully qualified class name.
 * @return string|false The file's source or false.
 */
function udb_deps_class_source( $sources, $fqn ) {
	$parts = explode( '\\', $fqn );
	$class = array_pop( $parts );
	$ns    = implode( '\\', $parts );

	foreach ( $sources as $source ) {
		if ( preg_match( '/^namespace\s+' . preg_quote( $ns, '/' ) . ';/m', $source ) && preg_match( '/^\s*(abstract\s+|final\s+)?class\s+' . $class . '\b/m', $source ) ) {
			return $source;
		}
	}

	return false;
}

foreach ( $deps['classes'] as $fqn ) {
	if ( ! udb_deps_class_source( $sources, $fqn ) ) {
		$failures[] = "Class missing: $fqn";
	}
}

foreach ( $deps['methods'] as $item ) {
	list( $fqn, $method ) = explode( '::', $item );

	$source = udb_deps_class_source( $sources, $fqn );

	if ( ! $source || ! preg_match( '/function\s+' . $method . '\s*\(/', $source ) ) {
		$failures[] = "Method missing: $item";
	}
}

// Pro unhooks these by hook, method and priority. If any of the three changes, the removal silently stops working.
foreach ( $deps['removed_callbacks'] as $item ) {
	list( $hook, $fqn, $method, $priority ) = $item;

	$source  = udb_deps_class_source( $sources, $fqn );
	$pattern = '/add_(?:action|filter)\(\s*[\'"]' . preg_quote( $hook, '/' ) . '[\'"]\s*,\s*array\(\s*[^,]+,\s*[\'"]' . $method . '[\'"]\s*\)\s*(?:,\s*(\d+))?/';
	$found   = false;

	foreach ( $sources as $candidate ) {
		if ( preg_match_all( $pattern, $candidate, $matches ) ) {
			foreach ( $matches[1] as $found_priority ) {
				if ( (int) ( '' === $found_priority ? 10 : $found_priority ) === (int) $priority ) {
					$found = true;
				}
			}
		}
	}

	if ( ! $source || ! $found ) {
		$failures[] = "Removable callback changed: $hook → $fqn::$method (priority $priority)";
	}
}

foreach ( $deps['hooks'] as $hook ) {
	if ( ! preg_match( '/(?:do_action|apply_filters)(?:_ref_array)?\(\s*[\'"]' . preg_quote( $hook, '/' ) . '[\'"]/', $all ) ) {
		$failures[] = "Hook no longer fired: $hook";
	}
}

foreach ( $deps['constants'] as $constant ) {
	if ( ! preg_match( '/define\(\s*[\'"]' . $constant . '[\'"]/', $all ) ) {
		$failures[] = "Constant missing: $constant";
	}
}

foreach ( $deps['paths'] as $path ) {
	if ( ! file_exists( $root . $path ) ) {
		$failures[] = "File missing: $path";
	}
}

foreach ( $deps['handles'] as $handle ) {
	if ( ! preg_match( '/wp_(?:register|enqueue)_(?:script|style)\(\s*[\'"]' . preg_quote( $handle, '/' ) . '[\'"]/', $all ) ) {
		$failures[] = "Script/style handle missing: $handle";
	}
}

foreach ( $deps['options'] as $option ) {
	if ( ! preg_match( '/(?:get|update)_option\(\s*[\'"]' . $option . '[\'"]/', $all ) ) {
		$failures[] = "Option no longer used: $option";
	}
}

foreach ( $deps['ajax_actions'] as $action ) {
	if ( false === strpos( $all, 'wp_ajax_' . $action ) ) {
		$failures[] = "AJAX action missing: $action";
	}
}

foreach ( $deps['nonces'] as $nonce ) {
	// Created or checked by free (a nonce PRO creates itself only needs free's check).
	if ( ! preg_match( '/(wp_create_nonce\(\s*|wp_verify_nonce\(\s*\$\w+,\s*|check_ajax_referer\(\s*)[\'"]' . $nonce . '[\'"]/', $all ) ) {
		$failures[] = "Nonce missing: $nonce";
	}
}

foreach ( $deps['post_types'] as $post_type ) {
	if ( ! preg_match( '/register_post_type\(\s*[\'"]' . $post_type . '[\'"]/', $all ) ) {
		$failures[] = "Post type missing: $post_type";
	}
}

foreach ( $deps['menu_slugs'] as $slug ) {
	if ( ! preg_match( '/add_(?:sub)?menu_page\([^;]*[\'"]' . $slug . '[\'"]/s', $all ) ) {
		$failures[] = "Menu page missing: $slug";
	}
}

$total = 0;

foreach ( $deps as $group ) {
	$total += count( $group );
}

if ( $failures ) {
	echo implode( PHP_EOL, $failures ) . PHP_EOL;
	echo count( $failures ) . " of $total Pro dependencies broken." . PHP_EOL;
	exit( 1 );
}

echo "All $total Pro dependencies OK." . PHP_EOL;
