<?php
/**
 * Title: Page: Home
 * Slug: axd-media/page-home
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: The AXD Media home page.
 *
 * @package AXD_Media
 */

// Hero.
echo axd_s_hero( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
	array(
		'eyebrow' => 'Technology Solutions for Business',
		'title'   => 'Simplifying Tech<br>So Clients Can <span class="axd-grad">Thrive.</span>',
		'lead'    => 'Custom solutions, data insights, modern websites, and reliable IT support to help your organization work smarter, serve better, and grow with confidence.',
		'buttons' => array(
			array( 'Explore Solutions', '#solutions', 'has-arrow' ),
			array( 'View Our Work', '/our-work/', 'is-style-outline' ),
		),
		'img'     => 'hero-home.webp',
		'alt'     => 'AI hub connected to cloud, analytics dashboard, support, security, settings and database icons',
	)
);

// Solutions cards.
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
	axd_s_head( 'Our Solutions', 'How We Help <span class="axd-grad">Your Organization</span>', 'Practical, results-driven technology solutions tailored to your goals.' )
	. axd_b_group( $axd_grid, 'axd-grid-4' ),
	'',
	'solutions'
);

// Why AXD.
$axd_stats = '';
foreach ( array(
	array( 'target-arrow', 'Experienced', '25+ years' ),
	array( 'team', 'Collaborative', 'A true partner' ),
	array( 'shield-check', 'Results-Driven', 'Measurable impact' ),
) as $axd_s ) {
	$axd_stat   = axd_b_img( axd_icon( $axd_s[0], 'purple' ), '' );
	$axd_stat  .= axd_b_p( esc_html( $axd_s[1] ), 'axd-stat__title' );
	$axd_stat  .= axd_b_p( esc_html( $axd_s[2] ), 'axd-stat__text' );
	$axd_stats .= axd_b_group( $axd_stat, 'axd-stat' );
}
$axd_why  = axd_b_p( 'Why AXD', 'axd-eyebrow has-rule' );
$axd_why .= axd_b_h( 2, 'A Technology Partner<br><span class="axd-grad">You Can Count On</span>' );
$axd_why .= axd_b_p( 'With more than 25 years of technology industry experience behind our approach, AXD Media brings together technical expertise, industry knowledge, and a practical understanding of business operations.', 'axd-body' );
$axd_why .= axd_b_p( 'We work closely with clients to understand their challenges, identify opportunities, and develop technology solutions that deliver meaningful results.', 'axd-body' );
$axd_why .= axd_b_group( $axd_stats, 'axd-stats' );
echo axd_s_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	axd_b_cols(
		array(
			axd_b_img( axd_img( 'axd-wall.webp' ), 'AXD logo mounted on an office wall', 'axd-brandart' ),
			$axd_why,
		),
		'axd-split'
	),
	'is-tight-top'
);

// Featured work.
echo axd_s_feature( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	array(
		'eyebrow' => 'Featured Work',
		'title'   => 'Real Solutions.<br><span class="axd-accent">Measurable Results.</span>',
		'paras'   => array( 'Explore how we’ve helped organizations improve operations, enhance service delivery, and make data-driven decisions.' ),
		'buttons' => array( array( 'View Our Work', '/our-work/', 'is-style-white has-arrow' ) ),
		'img'     => 'featured-bulb.webp',
		'alt'     => 'Glowing light bulb with a network of connected points, beside a laptop and a plant',
		'home'    => true,
	)
);

// CTA.
echo axd_s_cta(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
