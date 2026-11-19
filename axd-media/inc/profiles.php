<?php
/**
 * Team profile pages (digital business cards) and their downloadable vCards.
 *
 * A profile page is any page whose slug matches a key below. Adding ?vcard=1
 * to the page address downloads a .vcf contact card (phone "Add to Contacts").
 * To add a person, add an entry here and create a child page of /team/ with
 * the matching slug, using the "Profile Card" template.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

/**
 * Contact details per profile slug.
 *
 * @return array<string,array<string,string>>
 */
function axd_profiles() {
	$profiles = array(
		'alex-dueso' => array(
			'first'   => 'Alex',
			'last'    => 'Dueso',
			'org'     => 'AXD Media Technologies',
			'title'   => 'Founder & Technology Consultant',
			'phone'   => '+13059293239',
			'email'   => 'info@axdmedia.com',
			'url'     => 'https://axdmedia.com',
			'profile' => '/team/alex-dueso/',
			'note'    => 'AXD Media: technology solutions for business. Data analytics and dashboards, custom applications and AI, website development, and IT support and consulting.',
			'photo'   => 'profile-alex-dueso-vcard.jpg',
		),
	);

	/** Filter to add or change profile contact details. */
	return apply_filters( 'axd_profiles', $profiles );
}

/**
 * Escape a vCard text value.
 *
 * @param string $value Raw value.
 * @return string
 */
function axd_vcard_escape( $value ) {
	$value = str_replace( array( "\r\n", "\r", "\n" ), ' ', (string) $value );
	return str_replace( array( '\\', ',', ';' ), array( '\\\\', '\\,', '\\;' ), $value );
}

/**
 * Fold a vCard line to 75 octets as the spec requires.
 *
 * @param string $line Unfolded line.
 * @return string
 */
function axd_vcard_fold( $line ) {
	$out = '';
	while ( strlen( $line ) > 75 ) {
		$out .= substr( $line, 0, 75 ) . "\r\n ";
		$line = substr( $line, 75 );
	}
	return $out . $line . "\r\n";
}

/**
 * Build the vCard text for a profile.
 *
 * @param array $p Profile data.
 * @return string
 */
function axd_vcard_build( $p ) {
	$e     = 'axd_vcard_escape';
	$full  = trim( $p['first'] . ' ' . $p['last'] );
	$lines = array(
		'BEGIN:VCARD',
		'VERSION:3.0',
		'N:' . $e( $p['last'] ) . ';' . $e( $p['first'] ) . ';;;',
		'FN:' . $e( $full ),
		'ORG:' . $e( $p['org'] ),
	);
	if ( ! empty( $p['title'] ) ) {
		$lines[] = 'TITLE:' . $e( $p['title'] );
	}
	if ( ! empty( $p['phone'] ) ) {
		$lines[] = 'TEL;TYPE=WORK,VOICE:' . $e( $p['phone'] );
	}
	if ( ! empty( $p['email'] ) ) {
		$lines[] = 'EMAIL;TYPE=INTERNET,WORK:' . $e( $p['email'] );
	}
	if ( ! empty( $p['url'] ) ) {
		$lines[] = 'URL;TYPE=WORK:' . $e( $p['url'] );
	}
	if ( ! empty( $p['profile'] ) ) {
		$lines[] = 'URL;TYPE=PROFILE:' . $e( home_url( $p['profile'] ) );
	}
	if ( ! empty( $p['note'] ) ) {
		$lines[] = 'NOTE:' . $e( $p['note'] );
	}
	if ( ! empty( $p['photo'] ) ) {
		$file = get_theme_file_path( 'assets/images/' . $p['photo'] );
		if ( is_readable( $file ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
			$lines[] = 'PHOTO;ENCODING=b;TYPE=JPEG:' . base64_encode( file_get_contents( $file ) );
		}
	}
	$lines[] = 'REV:' . gmdate( 'Ymd\THis\Z' );
	$lines[] = 'END:VCARD';

	return implode( '', array_map( 'axd_vcard_fold', $lines ) );
}

/**
 * Serve /team/<slug>/?vcard=1 as a downloadable contact card.
 */
function axd_maybe_serve_vcard() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- public, read-only download.
	if ( empty( $_GET['vcard'] ) || ! is_page() ) {
		return;
	}
	$slug     = get_post_field( 'post_name', get_queried_object_id() );
	$profiles = axd_profiles();
	if ( ! isset( $profiles[ $slug ] ) ) {
		return;
	}
	$vcard = axd_vcard_build( $profiles[ $slug ] );

	nocache_headers();
	header( 'Content-Type: text/vcard; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . sanitize_file_name( $slug ) . '.vcf"' );
	header( 'Content-Length: ' . strlen( $vcard ) );
	echo $vcard; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- vCard text, escaped per spec above.
	exit;
}
add_action( 'template_redirect', 'axd_maybe_serve_vcard', 5 );
