<?php
/**
 * Permanent (301) redirects from old-site addresses to their new pages.
 *
 * Only runs when WordPress would otherwise show "page not found", so a real
 * page or post at the same address always wins. To add one, add a line to the
 * map below: 'old-path' => '/new-path/'. Paths are matched without the leading
 * or trailing slash and are case-insensitive.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

/**
 * Old path => new path.
 *
 * @return array<string,string>
 */
function axd_redirect_map() {
	$map = array(
		// Old section pages.
		'services'        => '/',
		'solutions'       => '/',
		'project'         => '/our-work/',
		'projects'        => '/our-work/',
		'portfolio'       => '/our-work/',
		'work'            => '/our-work/',
		'about'           => '/about-us/',
		'contact-us'      => '/contact/',
		'blog'            => '/',
		'news'            => '/',
		'web-development' => '/website-development/',
		'websites'        => '/website-development/',
		'custom-applications' => '/custom-applications-ai/',
		'it-support'      => '/it-support-consulting/',
		'data-analytics'  => '/data-analytics-dashboards/',
		'help'            => '/support/',
		'nonprofit'       => '/nonprofits/',

		// Old blog posts (blog removed in the redesign).
		'why-a-strong-it-presence-is-no-longer-optional'        => '/it-support-consulting/',
		'how-technology-can-elevate-the-way-nonprofits-operate' => '/nonprofits/',
	);

	/** Filter to add or change redirects from a plugin. */
	return apply_filters( 'axd_redirect_map', $map );
}

/**
 * Redirect a 404 request if its path is in the map.
 */
function axd_maybe_redirect_old_url() {
	if ( ! is_404() ) {
		return;
	}

	$path = wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	if ( ! is_string( $path ) ) {
		return;
	}

	// Strip a subdirectory install prefix, then slashes.
	$home_path = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( '/' !== $home_path && 0 === strpos( $path, $home_path ) ) {
		$path = substr( $path, strlen( $home_path ) );
	}
	$key = strtolower( trim( rawurldecode( $path ), '/' ) );

	$map = axd_redirect_map();
	if ( '' === $key || ! isset( $map[ $key ] ) ) {
		return;
	}

	wp_safe_redirect( home_url( $map[ $key ] ), 301, 'AXD Media' );
	exit;
}
add_action( 'template_redirect', 'axd_maybe_redirect_old_url', 1 );
