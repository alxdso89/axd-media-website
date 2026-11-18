<?php
/**
 * Title: Project case study
 * Slug: axd-media/project-case-study
 * Categories: axd-sections
 * Post Types: axd_project
 * Viewport Width: 1400
 * Description: Challenge / Approach / Delivered layout for a single project. Inserted automatically in new projects.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- block markup built from escaped parts.

$axd_cs = '';
foreach ( array(
	array( 'Challenge', '[Describe the client’s challenge in one or two sentences.]' ),
	array( 'Approach', '[Summarize how AXD worked with the team to understand workflows and data sources.]' ),
	array( 'Delivered', '[List what was built, such as dashboards, integrations, or training.]' ),
	array( 'Results', '[Optional: describe the outcome. Only include results the client has confirmed.]' ),
) as $axd_row ) {
	$axd_cs .= axd_b_group( axd_b_p( esc_html( $axd_row[0] ), 'axd-cs__label' ) . axd_b_p( esc_html( $axd_row[1] ) ) );
}

echo axd_s_section(
	axd_b_p( 'Case Study', 'axd-eyebrow has-rule' )
	. axd_b_h( 2, 'Project <span class="axd-grad">Overview</span>' )
	. axd_b_group( $axd_cs, 'axd-cs' )
);
