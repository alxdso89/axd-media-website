<?php
/**
 * Title: Page: IT Support & Consulting
 * Slug: axd-media/page-it-support
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: IT Support & Consulting solution page.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

echo axd_s_solution_page( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
	array(
		'hero'             => array(
			'eyebrow' => 'Technology Support',
			'title'   => 'Reliable IT Support. <span class="axd-grad">Practical Guidance.</span>',
			'lead'    => 'Technology should help your business move forward, not slow it down. We provide responsive support, technology planning, and vendor coordination to keep your systems dependable and your team productive.',
			'buttons' => array(
				array( 'Schedule a Consultation', '/contact/', 'has-arrow' ),
				array( 'Open Support Case', '/support/', 'is-style-outline' ),
			),
			'img'     => 'hero-it.webp',
			'alt'     => 'IT support specialist with a headset working at dual monitors',
		),
		'approach'         => array(
			'eyebrow' => 'Our Approach',
			'title'   => 'Support That Understands<br><span class="axd-grad">Your Business</span>',
			'img'     => 'approach-it.webp',
			'alt'     => 'Illustration of a continuous support workflow connecting team, planning, checklists and reporting',
			'body'    => 'We look beyond individual technical issues to understand your environment, daily operations, and long-term needs. Our approach combines hands-on assistance with clear recommendations and proactive planning.',
			'steps'   => array( 'Understand your environment and operations', 'Provide hands-on assistance', 'Recommend and plan proactively' ),
		),
		'challenges_title' => 'Solutions for Real <span class="axd-grad">IT Challenges</span>',
		'challenges'       => array(
			array( 'wrench', 'blue', 'Recurring IT Issues', 'Address everyday problems and identify underlying causes.' ),
			array( 'staff', 'purple', 'Limited Internal Resources', 'Give your team access to experienced technical support and guidance.' ),
			array( 'network', 'blue', 'Complex Vendor Relationships', 'Coordinate providers and help ensure services meet your needs.' ),
		),
		'collab'           => array(
			'quote'   => 'Technology should help your business move forward, not slow it down.',
			'eyebrow' => 'Collaboration',
			'title'   => 'A Partner Your<br><span class="axd-grad">Team Can Reach</span>',
			'body'    => 'We work with staff, leadership, and technology vendors to resolve issues, plan improvements, and keep everyone informed.',
			'checks'  => array( 'Staff support', 'Leadership planning', 'Vendor coordination', 'Everyone kept informed' ),
		),
		'feature'          => array(
			'eyebrow'   => 'Ongoing Support',
			'title'     => 'Keeping Your<br><span class="axd-accent">Technology Working.</span>',
			'paras'     => array( 'From help desk support to infrastructure oversight, system reviews, and security coordination, we help your organization maintain reliable technology.' ),
			'pills'     => array( 'Help Desk', 'IT Planning', 'Network Oversight', 'Security Coordination' ),
			'img'       => 'feature-it.webp',
			'alt'       => 'IT support specialist with a headset beside support, security, cloud and settings icons',
			'img_class' => 'is-fade',
		),
	)
);
