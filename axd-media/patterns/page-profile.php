<?php
/**
 * Title: Page: Profile Card
 * Slug: axd-media/page-profile
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Digital business card page for a team member (use with the Profile Card template).
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

// Hero: name, contact actions, photo.
$axd_left  = axd_b_p( 'AXD Media', 'axd-eyebrow is-dark has-rule' );
$axd_left .= axd_b_h( 1, 'Alex <span class="axd-grad">Dueso.</span>' );
$axd_left .= axd_b_p( 'Technology Solutions for Business', 'axd-profile__tag' );
$axd_left .= axd_b_p( 'Custom solutions, data insights, modern websites, and reliable IT support to help your organization work smarter, serve better, and grow with confidence.', 'axd-lead' );
$axd_left .= axd_b_buttons(
	array(
		array( 'Save Contact', '/team/alex-dueso/?vcard=1', 'axd-profile__save' ),
		array( 'Schedule a Consultation', '/contact/', 'is-style-outline' ),
	)
);
$axd_left .= axd_b_buttons(
	array(
		array( 'Call', 'tel:+13059293239', 'is-call' ),
		array( 'Email', 'mailto:info@axdmedia.com', 'is-email' ),
		array( 'Website', 'https://axdmedia.com/', 'is-web' ),
	),
	'axd-profile__quick'
);
$axd_right = axd_b_img( axd_img( 'profile-alex-dueso.webp' ), 'Alex Dueso', 'axd-hero__img is-portrait' );
echo axd_b_group( axd_b_group( axd_b_cols( array( $axd_left, $axd_right ) ), 'axd-hero axd-hero--profile', 'div', 'constrained' ), 'axd-hero-shell' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

// Contact cards.
$axd_rows  = axd_b_p( '<a href="tel:+13059293239"><span class="axd-pc__label">Phone</span>(305) 929-3239</a>', 'axd-pc is-phone' );
$axd_rows .= axd_b_p( '<a href="mailto:info@axdmedia.com"><span class="axd-pc__label">Email</span>info@axdmedia.com</a>', 'axd-pc is-email' );
$axd_rows .= axd_b_p( '<a href="https://axdmedia.com/"><span class="axd-pc__label">Website</span>axdmedia.com</a>', 'axd-pc is-web' );
$axd_rows .= axd_b_p( '<a href="/support/"><span class="axd-pc__label">Client Support</span>Open a support case</a>', 'axd-pc is-support' );
echo axd_s_section( axd_b_group( $axd_rows, 'axd-profile__contacts' ), 'is-tight-pad' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

// How I can help: same solution cards as the home page.
$axd_cards = array(
	array( 'card-da.webp', 'Data Analytics &amp; Dashboards', 'Turn your data into clear, actionable insights.', '/data-analytics-dashboards/' ),
	array( 'card-apps.webp', 'Custom Applications &amp; AI', 'Build secure, effective tools that automate work and improve results.', '/custom-applications-ai/' ),
	array( 'card-web.webp', 'Website Development', 'Modern, responsive websites that engage and inform.', '/website-development/' ),
	array( 'card-it.webp', 'IT Support &amp; Consulting', 'Reliable IT support and strategic guidance to keep you running smoothly.', '/it-support-consulting/' ),
);
$axd_grid = '';
foreach ( $axd_cards as $axd_c ) {
	$axd_body  = axd_b_h( 3, '<a href="' . esc_url( $axd_c[3] ) . '">' . $axd_c[1] . '</a>' );
	$axd_body .= axd_b_p( esc_html( $axd_c[2] ) );
	$axd_body .= axd_b_p( '', 'axd-go' );
	$axd_card  = axd_b_img( axd_img( $axd_c[0] ), '', 'axd-card__img' );
	$axd_card .= axd_b_group( $axd_body, 'axd-card__body' );
	$axd_grid .= axd_b_group( $axd_card, 'axd-card' );
}
echo axd_s_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	axd_s_head( 'How I Can Help', 'Technology That Works <span class="axd-grad">for You</span>' )
	. axd_b_group( $axd_grid, 'axd-grid-4' ),
	'is-alt'
);
