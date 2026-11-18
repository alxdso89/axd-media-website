<?php
/**
 * Title: Page: Profile Card
 * Slug: axd-media/page-profile
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 480
 * Description: Digital business card for a team member (use with the Profile Card template).
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

$axd_ico = static function ( $name, $color = 'blue' ) {
	return esc_url( get_theme_file_uri( 'assets/icons/' . $name . '-' . $color . '.svg' ) );
};

$axd_card  = axd_b_img( axd_img( 'profile-alex-dueso.webp' ), 'Alex Dueso', 'axd-profile__photo' );
$axd_card .= axd_b_h( 1, 'Alex Dueso', 'axd-profile__name' );
$axd_card .= axd_b_p( 'AXD Media', 'axd-profile__org' );
$axd_card .= axd_b_p( 'Technology Solutions for Business', 'axd-profile__tag' );
$axd_card .= axd_b_buttons(
	array( array( 'Save Contact', '/team/alex-dueso/?vcard=1', 'axd-profile__save' ) ),
	'axd-profile__primary'
);
$axd_card .= axd_b_buttons(
	array(
		array( 'Call', 'tel:+13059293239', 'is-call' ),
		array( 'Email', 'mailto:info@axdmedia.com', 'is-email' ),
		array( 'Website', 'https://axdmedia.com/', 'is-web' ),
	),
	'axd-profile__quick'
);

$axd_rows  = axd_b_p( '<a href="tel:+13059293239"><span class="axd-pc__label">Phone</span>(305) 929-3239</a>', 'axd-pc is-phone' );
$axd_rows .= axd_b_p( '<a href="mailto:info@axdmedia.com"><span class="axd-pc__label">Email</span>info@axdmedia.com</a>', 'axd-pc is-email' );
$axd_rows .= axd_b_p( '<a href="https://axdmedia.com/"><span class="axd-pc__label">Website</span>axdmedia.com</a>', 'axd-pc is-web' );
$axd_rows .= axd_b_p( '<a href="/support/"><span class="axd-pc__label">Client Support</span>Open a support case</a>', 'axd-pc is-support' );
$axd_card .= axd_b_group( $axd_rows, 'axd-profile__contacts' );

$axd_card .= axd_b_p( 'How I Can Help', 'axd-profile__eyebrow' );
$axd_card .= axd_b_list(
	array(
		'<a href="/data-analytics-dashboards/">Data Analytics &amp; Dashboards</a>',
		'<a href="/custom-applications-ai/">Custom Applications &amp; AI</a>',
		'<a href="/website-development/">Website Development</a>',
		'<a href="/it-support-consulting/">IT Support &amp; Consulting</a>',
	),
	'axd-profile__services'
);
$axd_card .= axd_b_buttons(
	array(
		array( 'Schedule a Consultation', '/contact/', 'has-arrow' ),
		array( 'Open Support Case', '/support/', 'is-style-outline' ),
	),
	'axd-profile__cta'
);

$axd_out  = axd_b_group( $axd_card, 'axd-profile__card' );
$axd_out .= axd_b_p( '<a href="https://axdmedia.com/">axdmedia.com</a>', 'axd-profile__foot' );

echo axd_b_group( $axd_out, 'axd-profile' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
