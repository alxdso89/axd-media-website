<?php
/**
 * Light security hardening for the AXD Media site.
 *
 * - Sends standard browser security headers on front-end pages.
 * - Turns off XML-RPC (an old remote-publishing API that is a common target
 *   for password-guessing attacks; nothing on this site uses it).
 * - Stops anonymous visitors from listing usernames through ?author=N scans,
 *   author archives, or the REST API users endpoint. Logged-in users (the
 *   block editor, application passwords) are unaffected.
 * - Removes the WordPress version number from page source.
 *
 * Each item can be switched off with the matching filter, e.g.
 * add_filter( 'axd_security_xmlrpc_off', '__return_false' );
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

/**
 * Security headers.
 *
 * @param array $headers Headers WordPress is about to send.
 * @return array
 */
function axd_security_headers( $headers ) {
	if ( ! apply_filters( 'axd_security_headers_on', true ) ) {
		return $headers;
	}
	$headers['X-Content-Type-Options'] = 'nosniff';
	$headers['X-Frame-Options']        = 'SAMEORIGIN';
	$headers['Referrer-Policy']        = 'strict-origin-when-cross-origin';
	$headers['Permissions-Policy']     = 'camera=(), microphone=(), geolocation=(), payment=()';
	if ( is_ssl() ) {
		// One year; subdomains are left out on purpose so email/other services on subdomains are not affected.
		$headers['Strict-Transport-Security'] = 'max-age=31536000';
	}
	return $headers;
}
add_filter( 'wp_headers', 'axd_security_headers' );

/**
 * Turn off XML-RPC.
 */
if ( apply_filters( 'axd_security_xmlrpc_off', true ) ) {
	add_filter( 'xmlrpc_enabled', '__return_false' );
	add_filter( 'xmlrpc_methods', '__return_empty_array' );
	add_filter(
		'wp_headers',
		static function ( $headers ) {
			unset( $headers['X-Pingback'] );
			return $headers;
		}
	);
	remove_action( 'wp_head', 'rsd_link' );

	// Refuse XML-RPC requests outright rather than answering with an empty method list.
	add_action(
		'init',
		static function () {
			if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
				status_header( 403 );
				header( 'Content-Type: text/plain; charset=utf-8' );
				echo 'XML-RPC is disabled on this site.';
				exit;
			}
		},
		0
	);
}

/**
 * Block anonymous username discovery via ?author=N and author archives.
 */
function axd_security_block_author_scans() {
	if ( is_user_logged_in() || ! apply_filters( 'axd_security_hide_users', true ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check.
	if ( isset( $_GET['author'] ) || is_author() ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}
}
add_action( 'template_redirect', 'axd_security_block_author_scans', 0 );

/**
 * Hide the REST API users endpoints from anonymous requests.
 *
 * @param array $endpoints Registered REST routes.
 * @return array
 */
function axd_security_rest_users( $endpoints ) {
	if ( is_user_logged_in() || ! apply_filters( 'axd_security_hide_users', true ) ) {
		return $endpoints;
	}
	foreach ( array_keys( $endpoints ) as $route ) {
		if ( 0 === strpos( $route, '/wp/v2/users' ) ) {
			unset( $endpoints[ $route ] );
		}
	}
	return $endpoints;
}
add_filter( 'rest_endpoints', 'axd_security_rest_users' );

/**
 * Don't print the WordPress version in page source or feeds.
 */
add_filter( 'the_generator', '__return_empty_string' );
remove_action( 'wp_head', 'wp_generator' );
