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
$axd_head .= axd_b_p( 'Founder &amp; Technology Consultant', 'axd-profile__role' );
$axd_head .= axd_b_p( 'AXD Media Technologies', 'axd-profile__company' );
$axd_head .= axd_b_p( 'Simplifying Technology. Empowering Growth.', 'axd-profile__tag' );
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
		array( 'Share This Card', '/team/alex-dueso/', 'is-style-outline axd-share' ),
	),
	'axd-profile__actions'
);

// About.
$axd_body .= axd_b_p( 'About Me', 'axd-profile__label' );
$axd_body .= axd_b_p( 'I believe technology should make work easier, not more complicated.', 'axd-profile__lead' );
$axd_body .= axd_b_p( 'With over 25 years of experience in IT and healthcare technology, I help organizations solve everyday challenges through practical solutions, better systems, and smarter use of data.' );
$axd_body .= axd_b_p( 'My approach starts with listening, understanding how people work, and finding solutions that make a meaningful difference. Whether it’s improving IT operations, building applications, or turning data into useful insights, my goal is to help organizations work more effectively and grow with confidence.' );

// Help + industries.
$axd_body .= axd_b_p( 'What I Can Help With', 'axd-profile__label' );
$axd_body .= axd_b_list(
	array(
		'<a class="is-data" href="/data-analytics-dashboards/">Data Analytics &amp; Dashboards</a>',
		'<a class="is-apps" href="/custom-applications-ai/">Custom Applications &amp; AI</a>',
		'<a class="is-web" href="/website-development/">Website Development</a>',
		'<a class="is-it" href="/it-support-consulting/">IT Support &amp; Consulting</a>',
	),
	'axd-profile__chips has-icons'
);
$axd_body .= axd_b_p( 'Industries', 'axd-profile__label' );
$axd_body .= axd_b_list(
	array(
		'<a class="is-health" href="/healthcare/">Healthcare</a>',
		'<a class="is-nonprofit" href="/nonprofits/">Nonprofits</a>',
		'<a class="is-legal" href="/legal/">Legal</a>',
	),
	'axd-profile__chips has-icons'
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
