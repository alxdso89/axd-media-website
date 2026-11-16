<?php
/**
 * Title: Page: Our Work
 * Slug: axd-media/page-our-work
 * Categories: axd-pages
 * Post Types: page
 * Block Types: core/post-content
 * Viewport Width: 1400
 * Description: Portfolio page with filterable project grid.
 *
 * @package AXD_Media
 */

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.

echo axd_s_hero(
	array(
		'eyebrow' => 'Our Work',
		'title'   => 'Real Solutions. <span class="axd-grad">Measurable Results.</span>',
		'lead'    => 'Explore how we’ve helped organizations improve operations, enhance service delivery, and make data-driven decisions, from dashboards and custom applications to websites and integrations.',
		'buttons' => array(
			array( 'Browse Projects', '#projects', 'has-arrow' ),
			array( 'Schedule a Consultation', '/contact/', 'is-style-outline' ),
		),
		'img'       => 'our-work-hero.webp',
		'alt'       => 'Glowing light bulb with gears beside glass blocks showing a target, an org chart, a growth chart and a rocket',
		'img_class' => 'is-wide',
	)
);

$axd_query = '<!-- wp:query {"queryId":7,"query":{"perPage":24,"pages":0,"offset":0,"postType":"axd_project","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"axd-projects"} -->
<div class="wp-block-query axd-projects"><!-- wp:post-template -->
<!-- wp:post-featured-image {"sizeSlug":"axd-card"} /-->

<!-- wp:group {"className":"axd-project__body","layout":{"type":"default"}} -->
<div class="wp-block-group axd-project__body"><!-- wp:post-terms {"term":"axd_project_type"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-terms {"term":"axd_industry"} /-->

<!-- wp:post-excerpt {"excerptLength":30} /-->

<!-- wp:paragraph {"className":"axd-more"} -->
<p class="axd-more">View project →</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">New projects are coming soon. In the meantime, <a href="/contact/">tell us about yours</a>.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query -->

';

echo axd_s_section(
	axd_s_head( 'Projects', 'Solutions <span class="axd-grad">We’ve Built</span>', 'Browse by type of work. Select a project to see the challenge, approach, and what we delivered.' )
	. axd_b_shortcode( '[axd_project_filters]' )
	. $axd_query,
	'',
	'projects'
);

echo axd_s_cta();
