<?php
/**
 * Title: Page: About Us
 * Slug: axd-media/page-about
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Company story, mission, vision, approach and industries.
 *
 * @package AXD_Media
 */

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.

echo axd_s_hero(
	array(
		'eyebrow'   => 'About AXD Media',
		'title'     => 'Technology That Works<br>for <span class="axd-grad">Your Organization.</span>',
		'lead'      => 'AXD Media helps businesses, nonprofits, and healthcare organizations solve operational challenges through technology, removing complexity so teams can work with clarity, confidence, and purpose.',
		'buttons'   => array(
			array( 'Schedule a Consultation', '/contact/', 'has-arrow' ),
			array( 'Our Approach', '#approach', 'is-style-outline' ),
		),
		'img'       => 'axd-wall.webp',
		'alt'       => 'AXD logo mounted on an office wall',
		'img_class' => 'is-photo',
	)
);

// Our story.
$axd_values = '';
foreach ( array(
	array( 'Experienced', '25+ years in technology' ),
	array( 'Reliable', 'Professionals you can trust' ),
	array( 'Collaborative', 'A true partner' ),
) as $axd_v ) {
	$axd_values .= axd_b_group( axd_b_p( esc_html( $axd_v[0] ), 'axd-value__title' ) . axd_b_p( esc_html( $axd_v[1] ), 'axd-value__text' ) );
}
$axd_left  = axd_b_p( 'Our Story', 'axd-eyebrow has-rule' );
$axd_left .= axd_b_h( 2, 'More Than an<br><span class="axd-grad">IT Support Company</span>' );
$axd_left .= axd_b_group( $axd_values, 'axd-values' );
$axd_right  = axd_b_p( 'Our approach is built on a simple idea: technology should make work easier, not harder. With more than 25 years of technology industry experience behind us, we bring together technical expertise, industry knowledge, and a practical understanding of how organizations actually operate.', 'axd-body' );
$axd_right .= axd_b_p( 'We don’t start with a product. We start by understanding your challenges and workflows, then design practical solutions, from dashboards and custom applications to websites and day-to-day IT support, that deliver meaningful results.', 'axd-body' );
$axd_right .= axd_b_p( 'Our experienced professionals bring deep technical knowledge and a passion for helping others. Every project we take on is guided by collaboration, reliability, and a commitment to real results.', 'axd-body' );
echo axd_s_section( axd_b_cols( array( $axd_left, $axd_right ), 'axd-split is-top' ) );

// Mission and vision.
$axd_mission  = axd_b_icon( 'target', 'blue' );
$axd_mission .= axd_b_p( 'Our Mission', 'axd-eyebrow is-blue' );
$axd_mission .= axd_b_h( 3, 'Make Technology Simple and Reliable' );
$axd_mission .= axd_b_p( 'We are committed to removing complexity from technology, providing dependable support and practical solutions so your team can work with clarity, confidence, and purpose, and stay focused on what matters most.' );
$axd_vision  = axd_b_icon( 'eye', 'white' );
$axd_vision .= axd_b_p( 'Our Vision', 'axd-eyebrow is-lavender' );
$axd_vision .= axd_b_h( 3, 'Support When Technology Becomes a Barrier' );
$axd_vision .= axd_b_p( 'We envision a future where every organization, large or small, has the technology support it needs, especially when technology stands in the way of serving customers, patients, and communities.' );
echo axd_s_section(
	axd_b_group( axd_b_group( $axd_mission, 'axd-mv is-light' ) . axd_b_group( $axd_vision, 'axd-mv is-dark' ), 'axd-grid-2' ),
	'is-alt'
);

// Approach.
$axd_steps = '';
foreach ( array(
	array( '01', 'Listen &amp; Assess', 'We meet with leadership and front-line staff to understand your goals, workflows, and the everyday pain points that often go unseen.' ),
	array( '02', 'Design', 'We recommend practical, budget-conscious solutions that fit the way your team actually works.' ),
	array( '03', 'Build &amp; Implement', 'We develop, integrate, and roll out the solution, involving the people who will use it every day.' ),
	array( '04', 'Support &amp; Improve', 'We stay with you after launch with reliable support, guidance, and ongoing improvements.' ),
) as $axd_st ) {
	$axd_tile   = axd_b_p( $axd_st[0], 'axd-step-num' );
	$axd_tile  .= axd_b_p( '', 'axd-step-bar' );
	$axd_tile  .= axd_b_h( 3, $axd_st[1] );
	$axd_tile  .= axd_b_p( esc_html( $axd_st[2] ) );
	$axd_steps .= axd_b_group( $axd_tile, 'axd-tile axd-step-tile' );
}
echo axd_s_section(
	axd_s_head( 'Our Approach', 'How We <span class="axd-grad">Work With You</span>', 'A practical, collaborative process from first conversation to ongoing support.' )
	. axd_b_group( $axd_steps, 'axd-grid-4' ),
	'',
	'approach'
);

// Industries.
echo axd_s_section(
	axd_s_head( 'Industry Expertise', 'Organizations <span class="axd-grad">We Serve</span>', 'Experience across healthcare, nonprofits, and business operations of every size.' )
	. axd_s_tiles(
		array(
			array( 'heart-plus', 'blue', 'Healthcare & Community Health', 'Technology, data, and reporting solutions that help care teams spend less time on systems and more time with patients.', '/healthcare/' ),
			array( 'heart', 'purple', 'Nonprofits', 'Efficient, cost-effective technology and clear reporting that keep your team mission-focused.', '/nonprofits/' ),
			array( 'building', 'blue', 'Small & Growing Businesses', 'Reliable IT, modern websites, and practical tools that grow with you, without the overhead of an in-house department.' ),
			array( 'grid', 'purple', 'Established Organizations', 'Integrations, dashboards, and custom applications that connect complex operations and support better decisions.' ),
		),
		4
	),
	'is-alt'
);

// Platforms.
echo axd_s_section(
	axd_s_head( 'Platforms', 'Tools Your Team <span class="axd-grad">Already Relies On</span>', 'We design, support, and integrate solutions across the platforms you use every day.' )
	. axd_b_list( array( 'Microsoft 365', 'Google Workspace', 'Power BI', 'Microsoft Azure', 'WordPress', 'WooCommerce', 'Cisco', 'Ubiquiti' ), 'axd-chips' )
);
