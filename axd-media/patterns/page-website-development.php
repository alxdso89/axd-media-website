<?php
/**
 * Title: Page: Website Development
 * Slug: axd-media/page-website-development
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Website Development solution page.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

echo axd_s_solution_page( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
	array(
		'hero'             => array(
			'eyebrow' => 'Web Solutions',
			'title'   => 'Websites That Make a <span class="axd-grad">Strong First Impression.</span>',
			'lead'    => 'Your website should represent your organization clearly and make it easy for people to find what they need. We build modern, responsive websites that balance design, performance, accessibility, and easy content management.',
			'buttons' => array(
				array( 'Schedule a Consultation', '/contact/', 'has-arrow' ),
				array( 'View Our Work', '/our-work/', 'is-style-outline' ),
			),
			'img'     => 'hero-web.webp',
			'alt'     => 'Responsive AXD Media website shown on a desktop monitor and a phone',
		),
		'approach'         => array(
			'eyebrow' => 'Our Approach',
			'title'   => 'More Than a<br><span class="axd-grad">Good-Looking Website</span>',
			'img'     => 'approach-web.webp',
			'alt'     => 'Illustration of a layered site structure with search, team, settings and analytics icons',
			'body'    => 'We learn about your audience, goals, and content before planning the layout. Every page is designed to guide visitors, support your message, and work well across desktop and mobile devices.',
			'steps'   => array( 'Learn your audience, goals, and content', 'Plan a layout that guides visitors', 'Design for desktop and mobile' ),
		),
		'challenges_title' => 'Solutions for Real <span class="axd-grad">Website Challenges</span>',
		'challenges'       => array(
			array( 'sparkle', 'blue', 'Outdated Design', 'Give your organization a modern and credible online presence.' ),
			array( 'compass', 'purple', 'Difficult Navigation', 'Help visitors find services, information, and next steps.' ),
			array( 'pencil', 'blue', 'Hard-to-Manage Content', 'Make routine updates easier for your staff.' ),
		),
		'collab'           => array(
			'quote'   => 'Your website should make it easy for people to find what they need.',
			'eyebrow' => 'Collaboration',
			'title'   => 'Your Brand,<br><span class="axd-grad">Your Voice</span>',
			'body'    => 'We work with your team to shape the site structure, visuals, and messaging so the finished website feels like your organization.',
			'checks'  => array( 'Site structure', 'Visuals', 'Messaging', 'Desktop and mobile ready' ),
		),
		'feature'          => array(
			'eyebrow'   => 'Beyond Launch',
			'title'     => 'Designed to Work<br><span class="axd-accent">Beyond Launch.</span>',
			'paras'     => array( 'From WordPress websites to contact forms and digital intake experiences, we focus on practical tools that support your visitors and staff.' ),
			'pills'     => array( 'WordPress', 'Responsive Design', 'Performance', 'SEO Foundations' ),
			'img'       => 'feature-web.webp',
			'alt'       => 'Web developer beside code, mobile, cloud and checklist panels',
			'img_class' => 'is-fade',
		),
	)
);
