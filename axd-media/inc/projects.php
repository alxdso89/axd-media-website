<?php
/**
 * "Projects" portfolio: custom post type, taxonomies and the filter bar
 * used on the Our Work page.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Project post type and its taxonomies.
 */
function axd_register_projects() {
	register_post_type(
		'axd_project',
		array(
			'labels'        => array(
				'name'               => __( 'Projects', 'axd-media' ),
				'singular_name'      => __( 'Project', 'axd-media' ),
				'add_new_item'       => __( 'Add New Project', 'axd-media' ),
				'edit_item'          => __( 'Edit Project', 'axd-media' ),
				'new_item'           => __( 'New Project', 'axd-media' ),
				'view_item'          => __( 'View Project', 'axd-media' ),
				'search_items'       => __( 'Search Projects', 'axd-media' ),
				'not_found'          => __( 'No projects found.', 'axd-media' ),
				'all_items'          => __( 'All Projects', 'axd-media' ),
				'featured_image'     => __( 'Project image', 'axd-media' ),
				'set_featured_image' => __( 'Set project image', 'axd-media' ),
			),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 21,
			'has_archive'   => 'projects',
			'rewrite'       => array(
				'slug'       => 'projects',
				'with_front' => false,
			),
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			// New projects start from the case-study layout.
			'template'      => array(
				array( 'core/pattern', array( 'slug' => 'axd-media/project-case-study' ) ),
			),
		)
	);

	register_taxonomy(
		'axd_project_type',
		'axd_project',
		array(
			'labels'            => array(
				'name'          => __( 'Project Types', 'axd-media' ),
				'singular_name' => __( 'Project Type', 'axd-media' ),
				'add_new_item'  => __( 'Add New Project Type', 'axd-media' ),
			),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'project-type' ),
		)
	);

	register_taxonomy(
		'axd_industry',
		'axd_project',
		array(
			'labels'            => array(
				'name'          => __( 'Industries', 'axd-media' ),
				'singular_name' => __( 'Industry', 'axd-media' ),
				'add_new_item'  => __( 'Add New Industry', 'axd-media' ),
			),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'industry' ),
		)
	);
}
add_action( 'init', 'axd_register_projects' );

/**
 * Default terms, created once when the theme is activated.
 */
function axd_create_default_terms() {
	$types = array(
		'dashboards-reporting' => __( 'Dashboards & Reporting', 'axd-media' ),
		'custom-applications'  => __( 'Custom Applications', 'axd-media' ),
		'websites'             => __( 'Websites', 'axd-media' ),
		'integrations'         => __( 'Integrations', 'axd-media' ),
	);
	foreach ( $types as $slug => $name ) {
		if ( ! term_exists( $slug, 'axd_project_type' ) ) {
			wp_insert_term( $name, 'axd_project_type', array( 'slug' => $slug ) );
		}
	}

	$industries = array(
		'healthcare' => __( 'Healthcare', 'axd-media' ),
		'nonprofit'  => __( 'Nonprofit', 'axd-media' ),
		'legal'      => __( 'Legal', 'axd-media' ),
		'business'   => __( 'Business', 'axd-media' ),
	);
	foreach ( $industries as $slug => $name ) {
		if ( ! term_exists( $slug, 'axd_industry' ) ) {
			wp_insert_term( $name, 'axd_industry', array( 'slug' => $slug ) );
		}
	}
}

/**
 * On theme activation: register types, add terms, refresh permalinks.
 */
function axd_on_activate() {
	axd_register_projects();
	axd_create_default_terms();
	axd_maybe_set_logo();
	flush_rewrite_rules();
}

/**
 * If no site logo is set yet, add the bundled AXD logo to the Media Library
 * and use it as the site logo (editable later in the Site Editor).
 */
function axd_maybe_set_logo() {
	if ( get_theme_mod( 'custom_logo' ) ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( 'axd-media-logo.png' );
	if ( ! $tmp || ! copy( get_theme_file_path( 'assets/images/logo.png' ), $tmp ) ) {
		return;
	}
	$id = media_handle_sideload(
		array(
			'name'     => 'axd-media-logo.png',
			'tmp_name' => $tmp,
		),
		0,
		'AXD Media'
	);
	if ( is_wp_error( $id ) ) {
		wp_delete_file( $tmp );
		return;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', 'AXD Media' );
	set_theme_mod( 'custom_logo', $id );
	update_option( 'site_logo', $id );
}
add_action( 'after_switch_theme', 'axd_on_activate' );

/**
 * [axd_project_filters] - filter buttons for the Our Work project grid.
 *
 * Filtering happens in the browser (assets/js/theme.js) using the
 * "axd_project_type-{slug}" classes WordPress adds to each project card.
 *
 * @return string
 */
function axd_project_filters_shortcode() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'axd_project_type',
			'hide_empty' => true,
		)
	);
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}

	$html  = '<div class="axd-filters" role="group" aria-label="' . esc_attr__( 'Filter projects', 'axd-media' ) . '">';
	$html .= '<button type="button" class="axd-filter is-active" aria-pressed="true" data-filter="all">' . esc_html__( 'All Projects', 'axd-media' ) . '</button>';
	foreach ( $terms as $term ) {
		$html .= sprintf(
			'<button type="button" class="axd-filter" aria-pressed="false" data-filter="%1$s">%2$s</button>',
			esc_attr( 'axd_project_type-' . $term->slug ),
			esc_html( $term->name )
		);
	}
	$html .= '</div>';

	return $html;
}
add_shortcode( 'axd_project_filters', 'axd_project_filters_shortcode' );
