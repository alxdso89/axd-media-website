<?php
/**
 * Title: Page: Legal
 * Slug: axd-media/page-legal
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Technology solutions for legal organizations.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.

echo axd_s_hero(
	array(
		'eyebrow'   => 'Technology Solutions for Legal Organizations',
		'title'     => 'Technology That Helps Your Team <span class="axd-grad">Focus on Every Case.</span>',
		'lead'      => 'From client intake and case management to reporting and IT support, AXD Media helps legal organizations simplify their daily operations and build technology solutions that support their work.',
		'buttons'   => array(
			array( 'Schedule a Consultation', '/contact/', 'has-arrow' ),
			array( 'How We Help', '#help', 'is-style-outline' ),
		),
		'img'       => 'legal-hero-2.webp',
		'alt'       => 'Laptop showing a legal case manager, with scales of justice, gavel and law books',
		'img_class' => 'is-wide',
	)
);

$axd_left  = axd_b_p( 'Our Approach', 'axd-eyebrow has-rule' );
$axd_left .= axd_b_h( 2, 'We Understand the Importance of <span class="axd-grad">Reliable Technology.</span>' );
$axd_left .= axd_s_statcards(
	array(
		array(
			'icon'  => 'client-check',
			'color' => 'blue',
			'title' => 'Client Focused',
			'text'  => 'Technology that supports better client service',
		),
		array(
			'icon'  => 'wrench',
			'color' => 'purple',
			'title' => 'Practical Solutions',
			'text'  => 'Designed around real operational needs',
		),
	)
);
$axd_right  = axd_b_p( 'Legal organizations depend on secure, organized, and accessible information to serve their clients effectively.', 'axd-body' );
$axd_right .= axd_b_p( 'We work with leadership, attorneys, and administrative teams to understand existing systems, identify workflow challenges, and develop practical technology solutions that reduce manual tasks and improve access to information. Our approach considers the needs of both the people managing the organization and those working directly with clients.', 'axd-body' );
echo axd_s_section( axd_b_cols( array( $axd_left, $axd_right ), 'axd-split is-top' ) );

echo axd_s_section(
	axd_s_head( 'How We Help', 'Solutions for Real <span class="axd-grad">Legal Challenges</span>', 'Technology that supports your cases, clients, and staff.' )
	. axd_s_tiles(
		array(
			array( 'intake', 'blue', 'Client Intake & Case Workflows', 'Digital intake forms, application integrations, and automated workflows that help organize information and reduce repetitive administrative tasks.' ),
			array( 'grant', 'purple', 'Grant & Program Reporting', 'Data dashboards and reporting solutions that help legal nonprofits track services, monitor funding, and demonstrate program impact.' ),
			array( 'shield-check', 'blue', 'Reliable IT & Security', 'Helpdesk support, system administration, access management, and security guidance that help protect sensitive information and keep teams productive.' ),
		)
	),
	'is-alt',
	'help'
);

echo axd_s_quote_split(
	array(
		'quote'   => 'Technology should make serving clients easier.',
		'small'   => 'Practical systems and clear information help legal teams spend more time on the work that matters.',
		'eyebrow' => 'Collaboration',
		'title'   => 'Designed With the<br><span class="axd-grad">People Who Use It.</span>',
		'body'    => 'We collaborate with attorneys, intake specialists, program managers, and administrative teams to understand their workflows and build solutions that make their jobs easier.',
		'checks'  => array( 'Client intake', 'Case management integrations', 'Document workflows', 'IT support' ),
	)
);

echo axd_s_feature(
	array(
		'eyebrow'   => 'Applications & Reporting',
		'title'     => 'Make Your Data Work<br><span class="axd-accent">for Your Mission.</span>',
		'paras'     => array(
			'Legal service organizations need more than spreadsheets to understand their impact.',
			'We develop custom applications, data repositories, and dashboards that bring information together from different systems, simplify reporting, and help organizations track services, grants, and operational performance.',
		),
		'pills'     => array( 'Case management integrations', 'Grant dashboards', 'Custom applications', 'Workflow automation' ),
		'img'       => 'legal-team.webp',
		'alt'       => 'Legal team reviewing case and reporting data on a transparent display in front of a courthouse',
		'img_class' => 'is-photo-wide is-fade-soft',
	)
);
