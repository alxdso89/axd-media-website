<?php
/**
 * Ticket and inquiry data: post types, case numbers, message history.
 *
 * @package AXD_Support_Desk
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ticket model.
 */
class AXD_SD_Tickets {

	const TABLE_VERSION = '1';

	/**
	 * Ticket statuses.
	 *
	 * @return array
	 */
	public static function statuses() {
		return array(
			'new'         => __( 'New', 'axd-support-desk' ),
			'in_progress' => __( 'In Progress', 'axd-support-desk' ),
			'waiting'     => __( 'Waiting on Client', 'axd-support-desk' ),
			'resolved'    => __( 'Resolved', 'axd-support-desk' ),
			'closed'      => __( 'Closed', 'axd-support-desk' ),
		);
	}

	/**
	 * Ticket priorities.
	 *
	 * @return array
	 */
	public static function priorities() {
		return array(
			'low'    => __( 'Low', 'axd-support-desk' ),
			'normal' => __( 'Normal', 'axd-support-desk' ),
			'high'   => __( 'High', 'axd-support-desk' ),
			'urgent' => __( 'Urgent', 'axd-support-desk' ),
		);
	}

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ) );
		if ( get_option( 'axd_sd_db_version' ) !== self::TABLE_VERSION ) {
			self::create_table();
		}
	}

	/**
	 * Activation: table, capabilities, post types.
	 */
	public static function activate() {
		self::create_table();
		$role = get_role( 'administrator' );
		if ( $role ) {
			foreach ( self::caps() as $cap ) {
				$role->add_cap( $cap );
			}
		}
		self::register();
		flush_rewrite_rules();
	}

	/**
	 * Capabilities used by the ticket post types.
	 *
	 * @return array
	 */
	public static function caps() {
		return array(
			'edit_axd_tickets',
			'edit_others_axd_tickets',
			'delete_axd_tickets',
			'delete_others_axd_tickets',
			'read_private_axd_tickets',
			'edit_private_axd_tickets',
			'edit_published_axd_tickets',
			'delete_published_axd_tickets',
			'delete_private_axd_tickets',
		);
	}

	/**
	 * Message history table.
	 */
	public static function create_table() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$table   = $wpdb->prefix . 'axd_ticket_messages';
		$charset = $wpdb->get_charset_collate();
		dbDelta(
			"CREATE TABLE {$table} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				ticket_id bigint(20) unsigned NOT NULL,
				type varchar(20) NOT NULL DEFAULT 'reply',
				author varchar(190) NOT NULL DEFAULT '',
				body longtext NOT NULL,
				emailed tinyint(1) NOT NULL DEFAULT 0,
				created_at datetime NOT NULL,
				PRIMARY KEY  (id),
				KEY ticket_id (ticket_id)
			) {$charset};"
		);
		update_option( 'axd_sd_db_version', self::TABLE_VERSION );
	}

	/**
	 * Register private post types for tickets and inquiries.
	 */
	public static function register() {
		$common = array(
			'public'              => false,
			'show_ui'             => true,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => array( 'axd_ticket', 'axd_tickets' ),
			'map_meta_cap'        => true,
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'supports'            => array( 'title' ),
		);

		register_post_type(
			'axd_ticket',
			array_merge(
				$common,
				array(
					'labels'        => array(
						'name'          => __( 'Support Tickets', 'axd-support-desk' ),
						'singular_name' => __( 'Support Ticket', 'axd-support-desk' ),
						'menu_name'     => __( 'Support Desk', 'axd-support-desk' ),
						'all_items'     => __( 'Tickets', 'axd-support-desk' ),
						'edit_item'     => __( 'Support Ticket', 'axd-support-desk' ),
						'search_items'  => __( 'Search Tickets', 'axd-support-desk' ),
						'not_found'     => __( 'No tickets yet.', 'axd-support-desk' ),
					),
					'menu_icon'     => 'dashicons-sos',
					'menu_position' => 25,
				)
			)
		);

		register_post_type(
			'axd_inquiry',
			array_merge(
				$common,
				array(
					'labels'       => array(
						'name'          => __( 'Contact Inquiries', 'axd-support-desk' ),
						'singular_name' => __( 'Contact Inquiry', 'axd-support-desk' ),
						'all_items'     => __( 'Contact Inquiries', 'axd-support-desk' ),
						'edit_item'     => __( 'Contact Inquiry', 'axd-support-desk' ),
						'not_found'     => __( 'No inquiries yet.', 'axd-support-desk' ),
					),
					'show_in_menu' => 'edit.php?post_type=axd_ticket',
				)
			)
		);
	}

	/**
	 * Next case number, e.g. AXD-261115-0007 (sequence never repeats).
	 *
	 * @return string
	 */
	public static function next_case_number() {
		global $wpdb;
		// Atomic increment of a numeric option.
		$wpdb->query( "INSERT INTO {$wpdb->options} (option_name, option_value, autoload) VALUES ('axd_sd_case_seq', '1', 'off') ON DUPLICATE KEY UPDATE option_value = option_value + 1" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		wp_cache_delete( 'axd_sd_case_seq', 'options' );
		wp_cache_delete( 'notoptions', 'options' );
		wp_cache_delete( 'alloptions', 'options' );
		$seq = (int) $wpdb->get_var( "SELECT option_value FROM {$wpdb->options} WHERE option_name = 'axd_sd_case_seq'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		return sprintf( '%s-%s-%04d', AXD_SD_Settings::get( 'case_prefix' ), wp_date( 'ymd' ), $seq );
	}

	/**
	 * Create a ticket.
	 *
	 * @param array $data name, email, phone, client, priority, description.
	 * @return int|WP_Error Ticket ID.
	 */
	public static function create( $data ) {
		$case    = self::next_case_number();
		$summary = wp_trim_words( $data['description'], 10, '…' );
		$id      = wp_insert_post(
			array(
				'post_type'   => 'axd_ticket',
				'post_status' => 'publish',
				'post_title'  => $case . ' · ' . $summary,
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		$meta = array(
			'_axd_case'     => $case,
			'_axd_name'     => $data['name'],
			'_axd_email'    => $data['email'],
			'_axd_phone'    => $data['phone'],
			'_axd_client'   => $data['client'],
			'_axd_priority' => $data['priority'],
			'_axd_status'   => 'new',
		);
		foreach ( $meta as $k => $v ) {
			update_post_meta( $id, $k, $v );
		}
		self::add_message( $id, 'client', $data['name'], $data['description'] );
		return $id;
	}

	/**
	 * Store an inquiry from the contact form.
	 *
	 * @param array $data Form data.
	 * @return int|WP_Error
	 */
	public static function create_inquiry( $data ) {
		$id = wp_insert_post(
			array(
				'post_type'   => 'axd_inquiry',
				'post_status' => 'publish',
				'post_title'  => $data['name'] . ( $data['organization'] ? ' · ' . $data['organization'] : '' ),
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		foreach ( array( 'name', 'organization', 'email', 'phone', 'topic', 'message', 'consult' ) as $k ) {
			update_post_meta( $id, '_axd_' . $k, $data[ $k ] );
		}
		return $id;
	}

	/**
	 * Append to a ticket's history.
	 *
	 * @param int    $ticket_id Ticket.
	 * @param string $type      client|reply|note|status.
	 * @param string $author    Author name.
	 * @param string $body      Message text.
	 * @param bool   $emailed   Whether it was emailed to the client.
	 * @return int Message ID.
	 */
	public static function add_message( $ticket_id, $type, $author, $body, $emailed = false ) {
		global $wpdb;
		$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->prefix . 'axd_ticket_messages',
			array(
				'ticket_id'  => (int) $ticket_id,
				'type'       => $type,
				'author'     => $author,
				'body'       => $body,
				'emailed'    => $emailed ? 1 : 0,
				'created_at' => current_time( 'mysql', true ),
			),
			array( '%d', '%s', '%s', '%s', '%d', '%s' )
		);
		// Touch the ticket so "last updated" sorting works.
		wp_update_post(
			array(
				'ID'                => (int) $ticket_id,
				'post_modified'     => current_time( 'mysql' ),
				'post_modified_gmt' => current_time( 'mysql', true ),
			)
		);
		return (int) $wpdb->insert_id;
	}

	/**
	 * A ticket's history, oldest first.
	 *
	 * @param int $ticket_id Ticket.
	 * @return array
	 */
	public static function messages( $ticket_id ) {
		global $wpdb;
		return $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}axd_ticket_messages WHERE ticket_id = %d ORDER BY id ASC", $ticket_id )
		);
	}

	/**
	 * Ticket meta helper.
	 *
	 * @param int    $id  Ticket.
	 * @param string $key Key without the _axd_ prefix.
	 * @return string
	 */
	public static function meta( $id, $key ) {
		return (string) get_post_meta( $id, '_axd_' . $key, true );
	}
}
