<?php
/**
 * Title: Page: Support
 * Slug: axd-media/page-support
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Client support page with case form and remote assistance.
 *
 * @package AXD_Media
 */

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.

echo axd_s_hero(
	array(
		'eyebrow' => 'Client Support',
		'title'   => 'Get Help<br><span class="axd-grad">When You Need It.</span>',
		'lead'    => 'Open a support case and we’ll automatically generate a case number, so we can track your request and get you the right assistance as quickly as possible.',
		'buttons' => array(
			array( 'Open a Support Case', '#case', 'has-arrow' ),
			array( 'Start Remote Session', '#remote', 'is-style-outline' ),
		),
		'img'     => 'support-hero.webp',
		'alt'     => 'Support specialist with a headset at a laptop, surrounded by email, phone, chat, calendar and location icons',
	)
);

$axd_how = '';
foreach ( array(
	array( 'Submit your request', 'Tell us what’s happening in a few words.' ),
	array( 'Get your case number', 'Generated instantly and emailed to you, with email updates as we work your case.' ),
	array( 'We connect', 'A technician follows up and, if needed, connects securely to your computer.' ),
) as $axd_h ) {
	$axd_how .= axd_b_group( axd_b_group( axd_b_p( esc_html( $axd_h[0] ), 'axd-howto__title' ) . axd_b_p( esc_html( $axd_h[1] ), 'axd-howto__text' ) ) );
}

$axd_form  = axd_b_h( 2, 'Open a Support Case' );
$axd_form .= axd_b_p( 'Please include a brief description of your issue so we can get you the right assistance quickly.', 'axd-note' );
$axd_form .= axd_b_shortcode( '[axd_support_form]' );

$axd_remote  = axd_b_p( 'Remote Assistance', 'axd-eyebrow is-lavender' );
$axd_remote .= axd_b_h( 2, 'On-Demand Secure Remote Assistance' );
$axd_remote .= axd_b_p( 'Allow an authorized AXD technician to securely access your computer and resolve the issue with you.' );
$axd_remote .= axd_b_list(
	array(
		'Download the app for your device below.',
		'Launch the downloaded program. It may be in your Downloads folder.',
		'Give the 9-digit code to your AXD technician.',
	),
	'axd-next',
	true
);
$axd_remote .= axd_b_buttons(
	array(
		array( 'Windows', 'https://sos.splashtop.com', 'is-style-glass' ),
		array( 'Mac', 'https://sos.splashtop.com', 'is-style-glass' ),
		array( 'iPhone / iPad', 'https://sos.splashtop.com', 'is-style-glass' ),
		array( 'Android', 'https://sos.splashtop.com', 'is-style-glass' ),
	),
	'is-grid'
);
$axd_remote .= axd_b_p( 'Secure remote sessions powered by Splashtop SOS. Only start a session when an AXD technician asks you to.', 'axd-navy__small' );

$axd_side  = axd_b_group( $axd_remote, 'axd-navy', 'div', 'default', 'remote' );
$axd_side .= axd_b_group( axd_b_icon( 'phone', 'blue' ) . axd_b_group( axd_b_p( 'Urgent issue? Call', 'axd-info__label' ) . axd_b_p( '<a href="tel:+13059293239">(305) 929-3239</a>', 'axd-info__value' ) ), 'axd-info' );
$axd_side .= axd_b_group( axd_b_icon( 'mail', 'purple' ) . axd_b_group( axd_b_p( 'Email support', 'axd-info__label' ) . axd_b_p( '<a href="mailto:info@axdmedia.com">info@axdmedia.com</a>', 'axd-info__value' ) ), 'axd-info' );

echo axd_s_section(
	axd_b_group( $axd_how, 'axd-howto' )
	. axd_b_cols(
		array(
			axd_b_group( $axd_form, 'axd-formcard', 'div', 'default', 'case' ),
			axd_b_group( $axd_side, 'axd-side' ),
		),
		'axd-layout'
	)
);
