<?php
/**
 * Small helpers used by patterns and templates.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cache-busting version for a theme asset (file modification time).
 *
 * @param string $relative Path relative to the theme root.
 * @return string
 */
function axd_asset_version( $relative ) {
	$path = get_theme_file_path( $relative );
	return file_exists( $path ) ? (string) filemtime( $path ) : AXD_THEME_VERSION;
}

/**
 * Escaped URL of a bundled theme image.
 *
 * @param string $file File name inside assets/images.
 * @return string
 */
function axd_img( $file ) {
	return esc_url( get_theme_file_uri( 'assets/images/' . $file ) );
}

/**
 * Escaped URL of a bundled icon (assets/icons/{name}-{color}.svg).
 *
 * @param string $name  Icon name.
 * @param string $color blue|purple|white.
 * @return string
 */
function axd_icon( $name, $color = 'blue' ) {
	return esc_url( get_theme_file_uri( 'assets/icons/' . $name . '-' . $color . '.svg' ) );
}
