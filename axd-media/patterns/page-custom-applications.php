<?php
/**
 * Title: Page: Custom Applications & AI
 * Slug: axd-media/page-custom-applications
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Custom Applications & AI solution page.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

echo axd_s_solution_page( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
	array(
		'hero'             => array(
			'eyebrow' => 'Custom Solutions',
			'title'   => 'Applications Built Around <span class="axd-grad">the Way You Work.</span>',
			'lead'    => 'When off-the-shelf software does not fit your workflow, we develop practical applications that help your staff work more effectively. From internal portals to automation and thoughtful AI features, each solution starts with a real business need.',
			'buttons' => array(
				array( 'Schedule a Consultation', '/contact/', 'has-arrow' ),
				array( 'View Our Work', '/our-work/', 'is-style-outline' ),
			),
			'img'     => 'hero-apps.webp',
			'alt'     => 'AI chip connected to settings, code and cloud application windows',
		),
		'approach'         => array(
			'eyebrow' => 'Our Approach',
			'title'   => 'Start With<br><span class="axd-grad">the Workflow</span>',
			'img'     => 'approach-apps.webp',
			'alt'     => 'Illustration of an AI hub connected to workflow, code, checklist and reporting windows',
			'body'    => 'We meet with the people who use your systems, map out the process, and identify what is slowing the team down. We then design solutions that fit your operations and connect with existing tools where practical.',
			'steps'   => array( 'Meet the people who use your systems', 'Map the process and find what slows it down', 'Design solutions that connect with your tools' ),
		),
		'challenges_title' => 'Solutions for Real <span class="axd-grad">Workflow Challenges</span>',
		'challenges'       => array(
			array( 'repeat', 'blue', 'Manual Processes', 'Replace repetitive tasks with easier digital workflows.' ),
			array( 'link', 'purple', 'Disconnected Systems', 'Connect applications and data to reduce duplicate work.' ),
			array( 'sliders', 'blue', 'One-Size-Fits-All Tools', 'Create tools designed around the way your organization operates.' ),
		),
		'collab'           => array(
			'quote'   => 'Every solution starts with a real business need.',
			'eyebrow' => 'Collaboration',
			'title'   => 'Designed With<br><span class="axd-grad">Your Team</span>',
			'body'    => 'We involve the people who will use the application, test real scenarios, and improve the experience based on feedback.',
			'checks'  => array( 'Real users involved', 'Real-scenario testing', 'Feedback-driven improvements', 'Connected to existing tools' ),
		),
		'feature'          => array(
			'eyebrow'   => 'Applications & AI',
			'title'     => 'Tools That Solve<br><span class="axd-accent">Real Problems.</span>',
			'paras'     => array( 'We create staff portals, scheduling tools, workflow applications, and integrations, with AI capabilities where they provide clear value and appropriate safeguards.' ),
			'pills'     => array( 'Internal Portals', 'Workflow Automation', 'System Integrations', 'Practical AI' ),
			'img'       => 'feature-apps.webp',
			'alt'       => 'AI brain connected to reporting, automation, code, cloud and workflow panels',
			'img_class' => 'is-fade-soft',
		),
	)
);
