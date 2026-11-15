<?php
/**
 * Small shortcodes used in template parts.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

/**
 * [axd_copyright] - "© {current year} AXD Media. All rights reserved."
 *
 * @param array $atts Shortcode attributes (name).
 * @return string
 */
function axd_copyright_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'name' => 'AXD Media' ), $atts, 'axd_copyright' );
	return sprintf(
		/* translators: 1: year, 2: company name */
		esc_html__( '© %1$s %2$s. All rights reserved.', 'axd-media' ),
		esc_html( wp_date( 'Y' ) ),
		esc_html( $atts['name'] )
	);
}
add_shortcode( 'axd_copyright', 'axd_copyright_shortcode' );

/**
 * Fallbacks for the AXD Support Desk plugin's form shortcodes, so the
 * Contact and Support pages still show useful contact details if the
 * plugin is deactivated. The plugin's real shortcodes take priority.
 */
function axd_register_form_fallbacks() {
	$fallback = static function () {
		return '<p class="axd-note">' . wp_kses_post(
			__( 'Our online form is temporarily unavailable. Please email <a href="mailto:info@axdmedia.com">info@axdmedia.com</a> or call <a href="tel:+13059293239">(305) 929-3239</a>.', 'axd-media' )
		) . '</p>';
	};
	foreach ( array( 'axd_contact_form', 'axd_support_form' ) as $tag ) {
		if ( ! shortcode_exists( $tag ) ) {
			add_shortcode( $tag, $fallback );
		}
	}
}
add_action( 'init', 'axd_register_form_fallbacks', 99 );

/**
 * [axd_year] - the current year.
 *
 * @return string
 */
function axd_year_shortcode() {
	return esc_html( wp_date( 'Y' ) );
}
add_shortcode( 'axd_year', 'axd_year_shortcode' );
