<?php
/**
 * WP-CLI command: `wp axd setup`
 *
 * Creates (or, with --overwrite, refreshes) the AXD pages from the theme's
 * full-page patterns and sets the static front page. Safe by default:
 * existing pages are never changed unless --overwrite is passed.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

/**
 * Manage AXD Media site content.
 */
class AXD_CLI_Command {

	/**
	 * Page map: slug => [ title, pattern slug, template ].
	 *
	 * @return array
	 */
	private function pages() {
		return array(
			'home'                       => array( 'Home', 'axd-media/page-home', '' ),
			'about-us'                   => array( 'About Us', 'axd-media/page-about', '' ),
			'our-work'                   => array( 'Our Work', 'axd-media/page-our-work', '' ),
			'nonprofits'                 => array( 'Nonprofits', 'axd-media/page-nonprofits', '' ),
			'healthcare'                 => array( 'Healthcare', 'axd-media/page-healthcare', '' ),
			'legal'                      => array( 'Legal', 'axd-media/page-legal', '' ),
			'data-analytics-dashboards'  => array( 'Data Analytics & Dashboards', 'axd-media/page-data-analytics', '' ),
			'custom-applications-ai'     => array( 'Custom Applications & AI', 'axd-media/page-custom-applications', '' ),
			'website-development'        => array( 'Website Development', 'axd-media/page-website-development', '' ),
			'it-support-consulting'      => array( 'IT Support & Consulting', 'axd-media/page-it-support', '' ),
			'contact'                    => array( 'Contact', 'axd-media/page-contact', '' ),
			'support'                    => array( 'Support', 'axd-media/page-support', '' ),
		);
	}

	/**
	 * Create the AXD pages from the theme patterns.
	 *
	 * ## OPTIONS
	 *
	 * [--overwrite]
	 * : Replace the content of pages that already exist (a revision is kept).
	 *
	 * [--dry-run]
	 * : Show what would happen without changing anything.
	 *
	 * [--sample-projects]
	 * : Also add placeholder projects (for local testing only).
	 *
	 * ## EXAMPLES
	 *
	 *     wp axd setup --dry-run
	 *     wp axd setup
	 *     wp axd setup --overwrite
	 *
	 * @param array $args       Positional args.
	 * @param array $assoc_args Flags.
	 */
	public function setup( $args, $assoc_args ) {
		$overwrite = ! empty( $assoc_args['overwrite'] );
		$dry       = ! empty( $assoc_args['dry-run'] );
		$registry  = WP_Block_Patterns_Registry::get_instance();

		foreach ( $this->pages() as $slug => $def ) {
			list( $title, $pattern_slug ) = $def;
			$pattern = $registry->get_registered( $pattern_slug );
			if ( ! $pattern ) {
				WP_CLI::warning( "Pattern {$pattern_slug} not found; skipping {$slug}." );
				continue;
			}

			$existing = get_page_by_path( $slug, OBJECT, 'page' );
			if ( $existing && ! $overwrite ) {
				WP_CLI::log( "Exists, left unchanged: /{$slug}/ (use --overwrite to replace)" );
				continue;
			}

			if ( $dry ) {
				WP_CLI::log( ( $existing ? 'Would overwrite' : 'Would create' ) . ": /{$slug}/" );
				continue;
			}

			$postarr = array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $pattern['content'],
			);
			if ( $existing ) {
				$postarr['ID'] = $existing->ID;
				wp_update_post( wp_slash( $postarr ) );
				WP_CLI::log( "Updated: /{$slug}/" );
			} else {
				wp_insert_post( wp_slash( $postarr ) );
				WP_CLI::log( "Created: /{$slug}/" );
			}
		}

		if ( ! $dry ) {
			$home = get_page_by_path( 'home', OBJECT, 'page' );
			if ( $home ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $home->ID );
				WP_CLI::log( 'Front page set to "Home".' );
			}
			if ( ! empty( $assoc_args['sample-projects'] ) ) {
				$this->sample_projects();
			}
			flush_rewrite_rules();
		}

		WP_CLI::success( $dry ? 'Dry run complete.' : 'Setup complete.' );
	}

	/**
	 * Placeholder projects so the Our Work grid can be previewed locally.
	 */
	private function sample_projects() {
		axd_create_default_terms();
		$items = array(
			array( 'Healthcare Operations Dashboard', 'dashboards-reporting', 'healthcare', 'Appointment, no-show, and visit trends brought together in one interactive dashboard for leadership and clinic managers.', 'healthcare-laptop.webp' ),
			array( 'Staff Scheduling Application', 'custom-applications', 'healthcare', 'A custom scheduling tool that replaces spreadsheets with a shared weekly view of staff, locations, and shifts.', 'scheduling-dashboard.webp' ),
			array( 'Grant & Program Reporting', 'dashboards-reporting', 'nonprofit', 'Funding, services, and program measures tracked in one place to simplify grant and board reporting.', 'legal-dashboard.webp' ),
			array( 'Digital Intake Website', 'websites', 'nonprofit', 'A responsive WordPress website with a guided, step-by-step digital intake form for new clients.', 'legal-intake.webp' ),
			array( 'Internal Request Portal', 'custom-applications', 'business', 'A staff portal for requests, approvals, and tasks, connected to the tools the team already uses.', 'featured-devices.webp' ),
			array( 'Connected Reporting Platform', 'integrations', 'business', 'Data from separate systems connected into a single, reliable reporting view for leadership.', 'healthcare-hero.webp' ),
		);
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		foreach ( $items as $item ) {
			list( $title, $type, $industry, $excerpt, $image ) = $item;
			$found = get_posts(
				array(
					'post_type'   => 'axd_project',
					'title'       => $title,
					'post_status' => 'any',
					'numberposts' => 1,
					'fields'      => 'ids',
				)
			);
			if ( $found ) {
				continue;
			}
			$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( 'axd-media/project-case-study' );
			$id      = wp_insert_post(
				wp_slash(
					array(
						'post_type'    => 'axd_project',
						'post_status'  => 'publish',
						'post_title'   => $title,
						'post_excerpt' => $excerpt,
						'post_content' => $pattern ? $pattern['content'] : '',
					)
				)
			);
			wp_set_object_terms( $id, $type, 'axd_project_type' );
			wp_set_object_terms( $id, $industry, 'axd_industry' );

			$src = get_theme_file_path( 'assets/images/' . $image );
			$tmp = wp_tempnam( $image );
			copy( $src, $tmp );
			$att = media_handle_sideload(
				array(
					'name'     => $image,
					'tmp_name' => $tmp,
				),
				$id
			);
			if ( ! is_wp_error( $att ) ) {
				set_post_thumbnail( $id, $att );
			}
			WP_CLI::log( "Sample project: {$title}" );
		}
	}
}

WP_CLI::add_command( 'axd', 'AXD_CLI_Command' );
