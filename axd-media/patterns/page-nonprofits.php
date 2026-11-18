<?php
/**
 * Title: Page: Nonprofits
 * Slug: axd-media/page-nonprofits
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Nonprofit and community health technology page.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.

echo axd_s_hero(
	array(
		'eyebrow'   => 'Nonprofits & Community Health',
		'title'     => 'Technology That Keeps<br>Your Team <span class="axd-grad">Mission-Focused.</span>',
		'lead'      => 'IT solutions designed to improve efficiency and drive impact, so your staff spends less time fighting systems and more time serving your community.',
		'buttons'   => array(
			array( 'Schedule a Consultation', '/contact/', 'has-arrow' ),
			array( 'How We Help', '#help', 'is-style-outline' ),
		),
		'img'       => 'nonprofits-hero.webp',
		'alt'       => 'Hands stacked together in front of paper cut-out figures holding hands around a heart',
		'img_class' => 'is-framed',
	)
);

$axd_left  = axd_b_p( 'Our Experience', 'axd-eyebrow has-rule' );
$axd_left .= axd_b_h( 2, 'We Understand<br><span class="axd-grad">the Nonprofit World</span>' );
$axd_left .= axd_s_statcards(
	array(
		array(
			'num'  => '20+',
			'text' => 'Years working with nonprofits and community health centers',
		),
		array(
			'num'  => 'Many',
			'text' => 'Federally Qualified Health Centers (FQHCs) partnered with nationwide',
		),
	)
);
$axd_right  = axd_b_p( 'For more than 20 years, our team has worked alongside nonprofit organizations and community health centers, partnering with many FQHCs across the country. That experience gives us a deep understanding of the challenges nonprofits face: limited resources, complex reporting requirements, and the need for reliable, cost-effective technology that truly supports the mission.', 'axd-body' );
$axd_right .= axd_b_p( 'We work closely with executive leadership, clinical teams, and administrative staff to address real operational challenges. From in-depth assessments to practical, budget-conscious solutions, every approach is rooted in improving efficiency while staying aligned with your organization’s goals and mission.', 'axd-body' );
echo axd_s_section( axd_b_cols( array( $axd_left, $axd_right ), 'axd-split is-top' ) );

echo axd_s_section(
	axd_s_head( 'How We Help', 'Solutions for <span class="axd-grad">Real Challenges</span>', 'Technology that supports people, not complicates their work.' )
	. axd_s_tiles(
		array(
			array( 'dollar', 'blue', 'Limited Resources', 'Budget-conscious solutions that make the most of every dollar and every staff hour, without cutting corners on reliability.' ),
			array( 'file-chart', 'purple', 'Complex Reporting', 'Grant, funder, and compliance reporting built on structured, reliable data your leadership and staff can trust.' ),
			array( 'shield-check', 'blue', 'Reliable Technology', 'Stable infrastructure and responsive support, so technology never stands between your team and the people you serve.' ),
		)
	),
	'is-alt',
	'help'
);

echo axd_s_quote_split(
	array(
		'quote'   => 'Technology should support people, not complicate their work.',
		'small'   => 'Every solution is designed to reduce friction, increase productivity, and let teams focus on what matters most: serving their communities.',
		'eyebrow' => 'Collaboration',
		'title'   => 'Built With Your<br><span class="axd-grad">Front-Line Staff</span>',
		'body'    => 'The people who use your systems every day see the pain points that often go unseen. By involving them in design and implementation, we create solutions that don’t just work on paper; they work in real-world environments.',
		'checks'  => array( 'Improving workflows', 'Strengthening infrastructure', 'Enhancing reporting capabilities', 'Implementing new systems' ),
	)
);

echo axd_s_feature(
	array(
		'eyebrow' => 'Data & Reporting',
		'title'   => 'Data That Tells<br><span class="axd-accent">Your Story.</span>',
		'paras'   => array(
			'Funding, grants, and program success depend on the ability to capture, track, and report meaningful data. Your data is more than numbers. It’s the story of the patients served, the services delivered, and the impact made in your community.',
			'We build structured, reliable data repositories, integrate systems, and develop dashboards and reports that create a single source of truth for leadership and staff.',
		),
		'pills'   => array( 'Data repositories', 'System integrations', 'Dashboards & reports', 'Grant reporting' ),
		'buttons' => array( array( 'Explore Data Analytics', '/data-analytics-dashboards/', 'is-style-white has-arrow' ) ),
		'img'     => 'home-hero-laptop.webp',
		'alt'     => 'Laptop showing an AXD dashboard',
	)
);
