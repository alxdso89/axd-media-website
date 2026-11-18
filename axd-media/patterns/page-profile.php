<?php
/**
 * Title: Page: Profile Card
 * Slug: axd-media/page-profile
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1200
 * Description: Digital business card for a team member, centered over a full-page background (use with the Profile Card template).
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

// Card header: photo, name, tagline.
$axd_head  = axd_b_img( axd_img( 'profile-alex-dueso.webp' ), 'Alex Dueso', 'axd-profile__photo' );
$axd_head .= '<!-- wp:image {"width":"90px","sizeSlug":"full","linkDestination":"none","className":"axd-profile__logo"} -->' . "\n"
	. '<figure class="wp-block-image size-full is-resized axd-profile__logo"><img src="' . axd_img( 'logo-axd-mark.webp' ) . '" alt="AXD" style="width:90px"/></figure>' . "\n"
	. "<!-- /wp:image -->\n\n";
$axd_head .= axd_b_h( 1, 'Alex Dueso', 'axd-profile__name' );
$axd_head .= axd_b_p( 'Technology Solutions for Business', 'axd-profile__tag' );
$axd_card  = axd_b_group( $axd_head, 'axd-profile__head' );

// Actions.
$axd_body  = axd_b_buttons(
	array(
		array( 'Call', 'tel:+13059293239', 'is-call' ),
		array( 'Text', 'sms:+13059293239', 'is-text' ),
		array( 'Email', 'mailto:info@axdmedia.com', 'is-email' ),
		array( 'Website', 'https://axdmedia.com/', 'is-web' ),
	),
	'axd-profile__quick'
);
$axd_body .= axd_b_buttons(
	array(
		array( 'Save My Contact', '/team/alex-dueso/?vcard=1', 'axd-profile__save' ),
		array( 'Schedule a Consultation', '/contact/', 'axd-profile__book' ),
		array( 'Save This Contact to Your Phone', 'https://axdmedia.com/wp-content/AlexDueso-AXDMedia-CEO.vcf', 'is-style-outline axd-profile__save-alt' ),
	),
	'axd-profile__actions'
);

// About.
$axd_body .= axd_b_p( 'About', 'axd-profile__label' );
$axd_body .= axd_b_p( 'Technology Solutions for Business', 'axd-profile__lead' );
$axd_body .= axd_b_p( 'AXD Media helps businesses, nonprofits, and healthcare organizations solve operational challenges with practical technology: data, applications, websites, and IT support.' );
$axd_body .= axd_b_p( 'With more than 25 years of technology industry experience behind our approach, AXD Media brings together technical expertise, industry knowledge, and a practical understanding of business operations.' );
$axd_body .= axd_b_p( 'We work closely with clients to understand their challenges, identify opportunities, and develop technology solutions that deliver meaningful results.' );

// Help + industries.
$axd_body .= axd_b_p( 'What I Can Help With', 'axd-profile__label' );
$axd_body .= axd_b_list(
	array(
		'<a href="/data-analytics-dashboards/">Data Analytics &amp; Dashboards</a>',
		'<a href="/custom-applications-ai/">Custom Applications &amp; AI</a>',
		'<a href="/website-development/">Website Development</a>',
		'<a href="/it-support-consulting/">IT Support &amp; Consulting</a>',
	),
	'axd-profile__chips'
);
$axd_body .= axd_b_p( 'Industries', 'axd-profile__label' );
$axd_body .= axd_b_list(
	array(
		'<a href="/nonprofits/">Nonprofits</a>',
		'<a href="/healthcare/">Healthcare</a>',
		'<a href="/legal/">Legal</a>',
	),
	'axd-profile__chips'
);

// Contact details.
$axd_body .= axd_b_p( 'Contact', 'axd-profile__label' );
$axd_rows  = axd_b_p( '<a href="tel:+13059293239"><span class="axd-pc__label">Phone</span>(305) 929-3239</a>', 'axd-pc is-phone' );
$axd_rows .= axd_b_p( '<a href="mailto:info@axdmedia.com"><span class="axd-pc__label">Email</span>info@axdmedia.com</a>', 'axd-pc is-email' );
$axd_rows .= axd_b_p( '<a href="https://axdmedia.com/"><span class="axd-pc__label">Website</span>axdmedia.com</a>', 'axd-pc is-web' );
$axd_rows .= axd_b_p( '<a href="/support/"><span class="axd-pc__label">Client Support</span>Open a support case</a>', 'axd-pc is-support' );
$axd_body .= axd_b_group( $axd_rows, 'axd-profile__contacts' );

$axd_card .= axd_b_group( $axd_body, 'axd-profile__body' );

echo axd_b_group( axd_b_group( $axd_card, 'axd-profile__card' ), 'axd-profile' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
