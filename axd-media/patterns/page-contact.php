<?php
/**
 * Title: Page: Contact
 * Slug: axd-media/page-contact
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Contact page with consultation form and contact details.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.

echo axd_s_hero(
	array(
		'eyebrow' => 'Contact Us',
		'title'   => 'Let’s Talk About<br><span class="axd-grad">Your Goals.</span>',
		'lead'    => 'Have a project, challenge, or idea? Tell us a little about it and we’ll get back to you to schedule a consultation.',
		'img'     => 'contact-hero.webp',
		'alt'     => 'Envelope with the AXD Media name surrounded by chat, phone, calendar and location icons',
	)
);

$axd_form  = axd_b_h( 2, 'Send Us a Message' );
$axd_form .= axd_b_shortcode( '[axd_contact_form]' );

$axd_side  = axd_b_group( axd_b_icon( 'phone', 'blue' ) . axd_b_group( axd_b_p( 'Call us', 'axd-info__label' ) . axd_b_p( '<a href="tel:+13059293239">(305) 929-3239</a>', 'axd-info__value' ) ), 'axd-info' );
$axd_side .= axd_b_group( axd_b_icon( 'mail', 'purple' ) . axd_b_group( axd_b_p( 'Email us', 'axd-info__label' ) . axd_b_p( '<a href="mailto:info@axdmedia.com">info@axdmedia.com</a>', 'axd-info__value' ) ), 'axd-info' );
$axd_side .= axd_b_group( axd_b_icon( 'globe', 'blue' ) . axd_b_group( axd_b_p( 'Service area', 'axd-info__label' ) . axd_b_p( 'Serving clients worldwide', 'axd-info__value' ) ), 'axd-info' );
$axd_side .= axd_b_group(
	axd_b_h( 3, 'Already a client?' )
	. axd_b_p( 'Need help with an existing system or device? Open a support case and we’ll generate a case number to track your request.' )
	. axd_b_buttons( array( array( 'Open Support Case', '/support/', 'is-style-white has-arrow' ) ) ),
	'axd-navy'
);
$axd_side .= axd_b_group(
	axd_b_h( 3, 'What happens next' )
	. axd_b_list(
		array(
			'<strong>We review your message</strong>and learn about your goals.',
			'<strong>We schedule a consultation</strong>at a time that works for you.',
			'<strong>We recommend a path forward</strong>with practical next steps.',
		),
		'axd-next',
		true
	),
	'axd-info is-stacked'
);

echo axd_s_section(
	axd_b_cols( array( axd_b_group( $axd_form, 'axd-formcard' ), axd_b_group( $axd_side, 'axd-side' ) ), 'axd-layout' ),
	'',
	'contact-form'
);
