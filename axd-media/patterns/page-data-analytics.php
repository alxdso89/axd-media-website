<?php
/**
 * Title: Page: Data Analytics & Dashboards
 * Slug: axd-media/page-data-analytics
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Data Analytics & Dashboards solution page.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

echo axd_s_solution_page( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.
	array(
		'hero'             => array(
			'eyebrow' => 'Data & Reporting',
			'title'   => 'Turn Your Data Into <span class="axd-grad">Decisions That Matter.</span>',
			'lead'    => 'Bring information from different systems together and make it easier to understand. We build dashboards and reports that help your team monitor performance, identify trends, and take action with confidence.',
			'buttons' => array(
				array( 'Schedule a Consultation', '/contact/', 'has-arrow' ),
				array( 'View Our Work', '/our-work/', 'is-style-outline' ),
			),
			'img'     => 'hero-da.webp',
			'alt'     => 'Analytics dashboard with KPI cards, trend charts and reports',
		),
		'approach'         => array(
			'eyebrow' => 'Our Approach',
			'title'   => 'Clarity Behind<br><span class="axd-grad">Every Number</span>',
			'img'     => 'approach-da.webp',
			'alt'     => 'Illustration of a dashboard with rising charts, data and goal icons',
			'body'    => 'We start by understanding your operations, the questions your leadership needs answered, and where your data lives. Then we organize the information and build reports that are useful in day-to-day decisions.',
			'steps'   => array( 'Understand your operations and questions', 'Organize information from your systems', 'Build reports for day-to-day decisions' ),
		),
		'challenges_title' => 'Solutions for Real <span class="axd-grad">Reporting Challenges</span>',
		'challenges'       => array(
			array( 'database', 'blue', 'Disconnected Data', 'Bring information from multiple systems into a more reliable reporting view.' ),
			array( 'table', 'purple', 'Manual Reporting', 'Reduce repetitive spreadsheets and time spent preparing reports.' ),
			array( 'eye', 'blue', 'Limited Visibility', 'Make key measures easier to track across teams and programs.' ),
		),
		'collab'           => array(
			'quote'   => 'Reports should answer real questions and help your team take action.',
			'eyebrow' => 'Collaboration',
			'title'   => 'Built Around<br><span class="axd-grad">Your Questions</span>',
			'body'    => 'We work with leadership and frontline staff to define meaningful KPIs, validate results, and build reporting tools people can actually use.',
			'checks'  => array( 'Leadership input', 'Frontline staff input', 'Meaningful KPIs', 'Validated results' ),
		),
		'feature'          => array(
			'eyebrow'   => 'Dashboards & Reporting',
			'title'     => 'Reporting That<br><span class="axd-accent">Tells Your Story.</span>',
			'paras'     => array( 'From operational dashboards to financial and grant reporting, we help your organization turn complex information into clear, useful insights.' ),
			'pills'     => array( 'Power BI Dashboards', 'KPI Tracking', 'Data Repositories', 'Grant Reporting' ),
			'img'       => 'feature-da.webp',
			'alt'       => 'Operations dashboard with appointments, calls answered, no-show rate and satisfaction metrics',
			'img_class' => 'is-fade-late',
		),
	)
);
