<?php
/**
 * Title: Page: Healthcare
 * Slug: axd-media/page-healthcare
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Healthcare technology solutions page.
 *
 * @package AXD_Media
 */

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.

echo axd_s_hero(
	array(
		'eyebrow'   => 'Healthcare Technology Solutions',
		'title'     => 'Technology That Supports<br><span class="axd-grad">Better Care.</span>',
		'lead'      => 'Healthcare technology should help providers focus on patients, not create additional work. AXD Media helps healthcare organizations improve workflows, connect systems, develop applications, and turn data into meaningful insights.',
		'buttons'   => array(
			array( 'Schedule a Consultation', '/contact/', 'has-arrow' ),
			array( 'Explore Solutions', '/#solutions', 'is-style-outline' ),
		),
		'img'       => 'healthcare-hero-2.webp',
		'alt'       => 'Healthcare analytics dashboard on a tablet with a clipboard, stethoscope and AXD Media branding',
		'img_class' => 'is-wide',
	)
);

$axd_left  = axd_b_p( 'Our Experience', 'axd-eyebrow has-rule' );
$axd_left .= axd_b_h( 2, 'We Understand the<br><span class="axd-grad">Healthcare Environment.</span>' );
$axd_left .= axd_s_statcards(
	array(
		array(
			'icon'  => 'pulse',
			'color' => 'blue',
			'title' => 'Care-Team Focused',
			'text'  => 'Solutions designed around clinical and front-desk workflows',
		),
		array(
			'icon'  => 'disconnected',
			'color' => 'purple',
			'title' => 'Connected Systems',
			'text'  => 'EHRs, applications, and reporting working together',
		),
	)
);
$axd_right  = axd_b_p( 'With more than 20 years of experience in healthcare technology and work across many Federally Qualified Health Centers, our team understands the operational, clinical, and reporting challenges healthcare organizations face.', 'axd-body' );
$axd_right .= axd_b_p( 'We work alongside executive leadership, clinical teams, and administrative staff to identify opportunities that improve efficiency, support patient engagement, and make technology easier to use.', 'axd-body' );
echo axd_s_section( axd_b_cols( array( $axd_left, $axd_right ), 'axd-split is-top' ) );

echo axd_s_section(
	axd_s_head( 'How We Help', 'Solutions for Real <span class="axd-grad">Healthcare Challenges</span>', 'Technology that supports your staff and the patients they serve.' )
	. axd_s_tiles(
		array(
			array( 'disconnected', 'blue', 'Disconnected Systems', 'Connecting EHR platforms, applications, and data sources to improve information flow and reduce manual work.' ),
			array( 'clinical-reporting', 'purple', 'Complex Clinical Reporting', 'Dashboards and reporting solutions that support quality improvement, operational performance, and leadership decisions.' ),
			array( 'staff', 'blue', 'Staff Productivity', 'Custom applications, AI-assisted workflows, and technology support that reduce repetitive tasks and help staff work more efficiently.' ),
		)
	),
	'is-alt',
	'help'
);

echo axd_s_quote_split(
	array(
		'quote'   => 'Technology should support care teams, not slow them down.',
		'small'   => 'We focus on practical tools that reduce administrative work and make information easier to access.',
		'eyebrow' => 'Collaboration',
		'title'   => 'Built Around Your<br><span class="axd-grad">Clinical Workflows.</span>',
		'body'    => 'The best healthcare solutions begin with understanding how care teams actually work. We collaborate with providers, medical assistants, operational leaders, and support staff to develop practical solutions that fit their environment.',
		'checks'  => array( 'Clinical workflows', 'EHR integrations', 'Patient engagement', 'Reporting and analytics' ),
	)
);

echo axd_s_feature(
	array(
		'eyebrow' => 'Data & Reporting',
		'title'   => 'Better Data.<br><span class="axd-accent">Better Decisions.</span>',
		'paras'   => array(
			'From patient access and appointment trends to clinical quality measures and operational performance, healthcare organizations generate valuable data every day.',
			'We help turn that information into dashboards, reporting tools, and integrated applications that give leadership and staff a clearer understanding of performance.',
		),
		'pills'   => array( 'EHR integrations', 'Clinical dashboards', 'Quality reporting', 'Operational analytics' ),
		'img'       => 'healthcare-care-team.webp',
		'alt'       => 'Nurse and doctor reviewing patient data on a tablet beside a health analytics display',
		'img_class' => 'is-photo-wide',
	)
);
