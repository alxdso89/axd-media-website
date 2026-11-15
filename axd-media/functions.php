<?php
/**
 * AXD Media theme functions.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

define( 'AXD_THEME_VERSION', '1.0.1' );

require_once get_theme_file_path( 'inc/helpers.php' );
require_once get_theme_file_path( 'inc/pattern-helpers.php' );
require_once get_theme_file_path( 'inc/projects.php' );
require_once get_theme_file_path( 'inc/shortcodes.php' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once get_theme_file_path( 'inc/cli.php' );
}

/**
 * Theme setup.
 */
function axd_setup() {
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 283,
			'width'       => 1758,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	// Keep the inserter focused on AXD patterns.
	remove_theme_support( 'core-block-patterns' );

	add_editor_style( 'assets/css/theme.css' );

	add_image_size( 'axd-card', 800, 600, false );
}
add_action( 'after_setup_theme', 'axd_setup' );

/**
 * Front-end styles and scripts.
 */
function axd_enqueue_assets() {
	wp_enqueue_style(
		'axd-theme',
		get_theme_file_uri( 'assets/css/theme.css' ),
		array(),
		axd_asset_version( 'assets/css/theme.css' )
	);

	wp_enqueue_script(
		'axd-theme',
		get_theme_file_uri( 'assets/js/theme.js' ),
		array(),
		axd_asset_version( 'assets/js/theme.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'axd_enqueue_assets' );

/**
 * Editor-only script: adds the "Gradient text" and "Accent text" formats
 * used in AXD headings.
 */
function axd_enqueue_editor_assets() {
	wp_enqueue_script(
		'axd-editor-formats',
		get_theme_file_uri( 'assets/js/editor-formats.js' ),
		array( 'wp-rich-text', 'wp-block-editor', 'wp-element' ),
		axd_asset_version( 'assets/js/editor-formats.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'axd_enqueue_editor_assets' );

/**
 * Preload the two variable fonts used above the fold.
 */
function axd_preload_fonts() {
	foreach ( array( 'plus-jakarta-sans-var.woff2', 'inter-var.woff2' ) as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( 'assets/fonts/' . $font ) )
		);
	}
}
add_action( 'wp_head', 'axd_preload_fonts', 1 );

/**
 * Block styles.
 */
function axd_register_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'  => 'white',
			'label' => __( 'White', 'axd-media' ),
		)
	);
	register_block_style(
		'core/button',
		array(
			'name'  => 'support',
			'label' => __( 'Support (solid blue)', 'axd-media' ),
		)
	);
	register_block_style(
		'core/button',
		array(
			'name'  => 'glass',
			'label' => __( 'Glass (on navy)', 'axd-media' ),
		)
	);
}
add_action( 'init', 'axd_register_block_styles' );

/**
 * Pattern categories.
 */
function axd_register_pattern_categories() {
	register_block_pattern_category(
		'axd-pages',
		array(
			'label'       => __( 'AXD: Full pages', 'axd-media' ),
			'description' => __( 'Complete page layouts for the AXD Media site.', 'axd-media' ),
		)
	);
	register_block_pattern_category(
		'axd-sections',
		array(
			'label'       => __( 'AXD: Sections', 'axd-media' ),
			'description' => __( 'Reusable AXD Media page sections.', 'axd-media' ),
		)
	);
}
add_action( 'init', 'axd_register_pattern_categories' );
