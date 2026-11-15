<?php
/**
 * Dashboard screens: ticket list, ticket workspace (history, reply,
 * internal note, status, priority) and the inquiry viewer.
 *
 * @package AXD_Support_Desk
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin UI.
 */
class AXD_SD_Admin {

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'add_meta_boxes_axd_ticket', array( __CLASS__, 'ticket_boxes' ) );
		add_action( 'add_meta_boxes_axd_inquiry', array( __CLASS__, 'inquiry_boxes' ) );
		add_action( 'save_post_axd_ticket', array( __CLASS__, 'save_ticket' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets' ) );
		add_action( 'admin_menu', array( __CLASS__, 'menu_badge' ), 30 );
		add_action( 'admin_notices', array( __CLASS__, 'notice' ) );

		add_filter( 'manage_axd_ticket_posts_columns', array( __CLASS__, 'ticket_columns' ) );
		add_action( 'manage_axd_ticket_posts_custom_column', array( __CLASS__, 'ticket_column' ), 10, 2 );
		add_filter( 'manage_edit-axd_ticket_sortable_columns', array( __CLASS__, 'sortable' ) );
		add_filter( 'views_edit-axd_ticket', array( __CLASS__, 'status_views' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'filter_query' ) );

		add_filter( 'manage_axd_inquiry_posts_columns', array( __CLASS__, 'inquiry_columns' ) );
		add_action( 'manage_axd_inquiry_posts_custom_column', array( __CLASS__, 'inquiry_column' ), 10, 2 );

		add_filter( 'post_row_actions', array( __CLASS__, 'row_actions' ), 10, 2 );
	}

	/**
	 * Admin CSS on our screens only.
	 *
	 * @param string $hook Hook suffix.
	 */
	public static function assets( $hook ) {
		$screen = get_current_screen();
		if ( $screen && in_array( $screen->post_type, array( 'axd_ticket', 'axd_inquiry' ), true ) ) {
			wp_enqueue_style( 'axd-sd-admin', AXD_SD_URL . 'assets/admin.css', array(), AXD_SD_VERSION );
		}
	}

	/**
	 * Count of open (new) tickets beside the menu item.
	 */
	public static function menu_badge() {
		global $menu;
		$count = self::count_status( 'new' );
		if ( ! $count || ! is_array( $menu ) ) {
			return;
		}
		foreach ( $menu as $i => $item ) {
			if ( isset( $item[2] ) && 'edit.php?post_type=axd_ticket' === $item[2] ) {
				$menu[ $i ][0] .= ' <span class="awaiting-mod"><span class="pending-count">' . (int) $count . '</span></span>'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}
	}

	/**
	 * Count tickets with a status.
	 *
	 * @param string $status Status key.
	 * @return int
	 */
	private static function count_status( $status ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'axd_ticket',
				'post_status'    => 'publish',
				'meta_key'       => '_axd_status', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => $status, // phpcs:ignore WordPress.DB.SlowDBQuery
				'fields'         => 'ids',
				'posts_per_page' => 1,
				'no_found_rows'  => false,
			)
		);
		return (int) $q->found_posts;
	}

	/* ---------------------------------------------------------------- List */

	/**
	 * Ticket list columns.
	 *
	 * @return array
	 */
	public static function ticket_columns() {
		return array(
			'cb'           => '<input type="checkbox" />',
			'title'        => __( 'Ticket', 'axd-support-desk' ),
			'axd_client'   => __( 'Client', 'axd-support-desk' ),
			'axd_status'   => __( 'Status', 'axd-support-desk' ),
			'axd_priority' => __( 'Priority', 'axd-support-desk' ),
			'axd_updated'  => __( 'Last updated', 'axd-support-desk' ),
		);
	}

	/**
	 * Ticket list cell.
	 *
	 * @param string $col Column.
	 * @param int    $id  Ticket.
	 */
	public static function ticket_column( $col, $id ) {
		switch ( $col ) {
			case 'axd_client':
				echo esc_html( AXD_SD_Tickets::meta( $id, 'name' ) );
				$client = AXD_SD_Tickets::meta( $id, 'client' );
				if ( $client ) {
					echo '<br><span class="axd-sd-muted">' . esc_html( $client ) . '</span>';
				}
				echo '<br><a href="mailto:' . esc_attr( AXD_SD_Tickets::meta( $id, 'email' ) ) . '">' . esc_html( AXD_SD_Tickets::meta( $id, 'email' ) ) . '</a>';
				break;
			case 'axd_status':
				$s = AXD_SD_Tickets::meta( $id, 'status' );
				echo '<span class="axd-sd-badge is-' . esc_attr( $s ) . '">' . esc_html( AXD_SD_Tickets::statuses()[ $s ] ?? $s ) . '</span>';
				break;
			case 'axd_priority':
				$p = AXD_SD_Tickets::meta( $id, 'priority' );
				echo '<span class="axd-sd-prio is-' . esc_attr( $p ) . '">' . esc_html( AXD_SD_Tickets::priorities()[ $p ] ?? $p ) . '</span>';
				break;
			case 'axd_updated':
				echo esc_html( sprintf( /* translators: %s: time ago */ __( '%s ago', 'axd-support-desk' ), human_time_diff( get_post_modified_time( 'U', true, $id ) ) ) );
				break;
		}
	}

	/**
	 * Sortable columns.
	 *
	 * @param array $cols Columns.
	 * @return array
	 */
	public static function sortable( $cols ) {
		$cols['axd_updated'] = 'modified';
		return $cols;
	}

	/**
	 * Status filter links above the list.
	 *
	 * @param array $views Default views.
	 * @return array
	 */
	public static function status_views( $views ) {
		$current = isset( $_GET['axd_status'] ) ? sanitize_key( wp_unslash( $_GET['axd_status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$base    = admin_url( 'edit.php?post_type=axd_ticket' );
		$out     = array(
			'all' => '<a href="' . esc_url( $base ) . '"' . ( '' === $current ? ' class="current"' : '' ) . '>' . esc_html__( 'All', 'axd-support-desk' ) . '</a>',
		);
		foreach ( AXD_SD_Tickets::statuses() as $key => $label ) {
			$out[ $key ] = sprintf(
				'<a href="%s"%s>%s <span class="count">(%d)</span></a>',
				esc_url( add_query_arg( 'axd_status', $key, $base ) ),
				$current === $key ? ' class="current"' : '',
				esc_html( $label ),
				self::count_status( $key )
			);
		}
		if ( isset( $views['trash'] ) ) {
			$out['trash'] = $views['trash'];
		}
		return $out;
	}

	/**
	 * Apply the status filter and default sort (newest activity first).
	 *
	 * @param WP_Query $q Query.
	 */
	public static function filter_query( $q ) {
		if ( ! is_admin() || ! $q->is_main_query() || 'axd_ticket' !== $q->get( 'post_type' ) ) {
			return;
		}
		$status = isset( $_GET['axd_status'] ) ? sanitize_key( wp_unslash( $_GET['axd_status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $status && isset( AXD_SD_Tickets::statuses()[ $status ] ) ) {
			$q->set( 'meta_key', '_axd_status' );
			$q->set( 'meta_value', $status );
		}
		if ( ! $q->get( 'orderby' ) ) {
			$q->set( 'orderby', 'modified' );
			$q->set( 'order', 'DESC' );
		}
	}

	/**
	 * Trim row actions (no Quick Edit / View for private records).
	 *
	 * @param array   $actions Actions.
	 * @param WP_Post $post    Post.
	 * @return array
	 */
	public static function row_actions( $actions, $post ) {
		if ( in_array( $post->post_type, array( 'axd_ticket', 'axd_inquiry' ), true ) ) {
			unset( $actions['inline hide-if-no-js'], $actions['view'] );
		}
		return $actions;
	}

	/* ----------------------------------------------------- Ticket screen */

	/**
	 * Ticket edit screen boxes.
	 */
	public static function ticket_boxes() {
		remove_meta_box( 'submitdiv', 'axd_ticket', 'side' );
		add_meta_box( 'axd_sd_conversation', __( 'Case history', 'axd-support-desk' ), array( __CLASS__, 'box_conversation' ), 'axd_ticket', 'normal', 'high' );
		add_meta_box( 'axd_sd_reply', __( 'Work this case', 'axd-support-desk' ), array( __CLASS__, 'box_reply' ), 'axd_ticket', 'normal', 'high' );
		add_meta_box( 'axd_sd_details', __( 'Client details', 'axd-support-desk' ), array( __CLASS__, 'box_details' ), 'axd_ticket', 'side', 'high' );
	}

	/**
	 * Client details box.
	 *
	 * @param WP_Post $post Ticket.
	 */
	public static function box_details( $post ) {
		$id      = $post->ID;
		$client  = AXD_SD_Tickets::meta( $id, 'client' );
		$known   = AXD_SD_Settings::client_ids();
		$orgname = $known[ $client ] ?? '';
		$rows    = array(
			__( 'Case number', 'axd-support-desk' ) => '<strong class="axd-sd-casenum">' . esc_html( AXD_SD_Tickets::meta( $id, 'case' ) ) . '</strong>',
			__( 'Name', 'axd-support-desk' )        => esc_html( AXD_SD_Tickets::meta( $id, 'name' ) ),
			__( 'Email', 'axd-support-desk' )       => '<a href="mailto:' . esc_attr( AXD_SD_Tickets::meta( $id, 'email' ) ) . '">' . esc_html( AXD_SD_Tickets::meta( $id, 'email' ) ) . '</a>',
			__( 'Phone', 'axd-support-desk' )       => esc_html( AXD_SD_Tickets::meta( $id, 'phone' ) ),
			__( 'Client ID', 'axd-support-desk' )   => esc_html( $client . ( $orgname ? ' · ' . $orgname : '' ) ),
			__( 'Opened', 'axd-support-desk' )      => esc_html( get_the_date( 'M j, Y g:i a', $post ) ),
		);
		echo '<dl class="axd-sd-dl">';
		foreach ( $rows as $label => $value ) {
			if ( '' === $value ) {
				continue;
			}
			echo '<dt>' . esc_html( $label ) . '</dt><dd>' . $value . '</dd>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
		}
		echo '</dl>';
	}

	/**
	 * Conversation box.
	 *
	 * @param WP_Post $post Ticket.
	 */
	public static function box_conversation( $post ) {
		$labels = array(
			'client' => __( 'Client', 'axd-support-desk' ),
			'reply'  => __( 'Reply sent to client', 'axd-support-desk' ),
			'note'   => __( 'Internal note', 'axd-support-desk' ),
			'status' => __( 'Status change', 'axd-support-desk' ),
		);
		echo '<div class="axd-sd-thread">';
		foreach ( AXD_SD_Tickets::messages( $post->ID ) as $m ) {
			printf(
				'<div class="axd-sd-msg is-%1$s"><div class="axd-sd-msg__head"><strong>%2$s</strong> <span>%3$s · %4$s%5$s</span></div><div class="axd-sd-msg__body">%6$s</div></div>',
				esc_attr( $m->type ),
				esc_html( $m->author ),
				esc_html( $labels[ $m->type ] ?? $m->type ),
				esc_html( wp_date( 'M j, Y g:i a', strtotime( $m->created_at . ' UTC' ) ) ),
				$m->emailed ? ' · ' . esc_html__( 'emailed', 'axd-support-desk' ) : '',
				wp_kses_post( wpautop( esc_html( $m->body ) ) )
			);
		}
		echo '</div>';
	}

	/**
	 * Reply / note / status box.
	 *
	 * @param WP_Post $post Ticket.
	 */
	public static function box_reply( $post ) {
		wp_nonce_field( 'axd_sd_work_' . $post->ID, 'axd_sd_work_nonce' );
		$status   = AXD_SD_Tickets::meta( $post->ID, 'status' );
		$priority = AXD_SD_Tickets::meta( $post->ID, 'priority' );
		?>
		<div class="axd-sd-work">
			<p class="axd-sd-types">
				<label><input type="radio" name="axd_sd_type" value="reply" checked> <?php esc_html_e( 'Reply to client (emailed)', 'axd-support-desk' ); ?></label>
				<label><input type="radio" name="axd_sd_type" value="note"> <?php esc_html_e( 'Internal note (private)', 'axd-support-desk' ); ?></label>
			</p>
			<label class="screen-reader-text" for="axd_sd_message"><?php esc_html_e( 'Message', 'axd-support-desk' ); ?></label>
			<textarea id="axd_sd_message" name="axd_sd_message" rows="6" class="large-text" placeholder="<?php esc_attr_e( 'Write a reply or a note… (optional if you only change the status)', 'axd-support-desk' ); ?>"></textarea>
			<div class="axd-sd-row">
				<p>
					<label for="axd_sd_status"><strong><?php esc_html_e( 'Status', 'axd-support-desk' ); ?></strong></label><br>
					<select id="axd_sd_status" name="axd_sd_status">
						<?php foreach ( AXD_SD_Tickets::statuses() as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $status, $key ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
				<p>
					<label for="axd_sd_priority"><strong><?php esc_html_e( 'Priority', 'axd-support-desk' ); ?></strong></label><br>
					<select id="axd_sd_priority" name="axd_sd_priority">
						<?php foreach ( AXD_SD_Tickets::priorities() as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $priority, $key ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
				<p>
					<label><input type="checkbox" name="axd_sd_notify_status" value="1" checked> <?php esc_html_e( 'Email the client when the status changes', 'axd-support-desk' ); ?></label>
				</p>
			</div>
			<p><button type="submit" class="button button-primary button-large"><?php esc_html_e( 'Update ticket', 'axd-support-desk' ); ?></button></p>
		</div>
		<?php
	}

	/**
	 * Save reply / note / status.
	 *
	 * @param int     $id   Ticket.
	 * @param WP_Post $post Ticket.
	 */
	public static function save_ticket( $id, $post ) {
		if ( ! isset( $_POST['axd_sd_work_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['axd_sd_work_nonce'] ) ), 'axd_sd_work_' . $id ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $id ) || ! current_user_can( 'edit_post', $id ) ) {
			return;
		}
		// Prevent recursion from add_message() touching the post.
		remove_action( 'save_post_axd_ticket', array( __CLASS__, 'save_ticket' ), 10 );

		$user     = wp_get_current_user();
		$author   = $user->display_name ? $user->display_name : 'AXD Media';
		$type     = isset( $_POST['axd_sd_type'] ) && 'note' === $_POST['axd_sd_type'] ? 'note' : 'reply';
		$message  = isset( $_POST['axd_sd_message'] ) ? trim( sanitize_textarea_field( wp_unslash( $_POST['axd_sd_message'] ) ) ) : '';
		$status   = isset( $_POST['axd_sd_status'] ) ? sanitize_key( wp_unslash( $_POST['axd_sd_status'] ) ) : '';
		$priority = isset( $_POST['axd_sd_priority'] ) ? sanitize_key( wp_unslash( $_POST['axd_sd_priority'] ) ) : '';
		$notify   = ! empty( $_POST['axd_sd_notify_status'] );

		$old_status = AXD_SD_Tickets::meta( $id, 'status' );
		$changed    = ( $status && $status !== $old_status && isset( AXD_SD_Tickets::statuses()[ $status ] ) ) ? $status : '';

		if ( $priority && isset( AXD_SD_Tickets::priorities()[ $priority ] ) ) {
			update_post_meta( $id, '_axd_priority', $priority );
		}
		if ( $changed ) {
			update_post_meta( $id, '_axd_status', $changed );
		}

		$reply  = ( 'reply' === $type ) ? $message : '';
		$emails = ( '' !== $reply ) || ( $changed && $notify );
		$sent   = false;
		if ( $emails ) {
			$sent = AXD_SD_Mailer::ticket_updated( $id, $reply, ( $changed && $notify ) ? $changed : '' );
		}

		if ( '' !== $message ) {
			AXD_SD_Tickets::add_message( $id, $type, $author, $message, 'reply' === $type && $sent );
		}
		if ( $changed ) {
			AXD_SD_Tickets::add_message(
				$id,
				'status',
				$author,
				sprintf(
					/* translators: 1: old status, 2: new status */
					__( 'Status changed from %1$s to %2$s.', 'axd-support-desk' ),
					AXD_SD_Tickets::statuses()[ $old_status ] ?? $old_status,
					AXD_SD_Tickets::statuses()[ $changed ]
				),
				$changed && $notify && $sent
			);
		}

		if ( $emails ) {
			set_transient( 'axd_sd_notice_' . get_current_user_id(), $sent ? 'sent' : 'failed', 60 );
		}
	}

	/**
	 * Admin notice after updating a ticket.
	 */
	public static function notice() {
		$key   = 'axd_sd_notice_' . get_current_user_id();
		$state = get_transient( $key );
		if ( ! $state ) {
			return;
		}
		delete_transient( $key );
		if ( 'sent' === $state ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Ticket updated and the client was emailed.', 'axd-support-desk' ) . '</p></div>';
		} else {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Ticket updated, but the email could not be sent. Check your email (SMTP) settings.', 'axd-support-desk' ) . '</p></div>';
		}
	}

	/* ---------------------------------------------------- Inquiry screen */

	/**
	 * Inquiry list columns.
	 *
	 * @return array
	 */
	public static function inquiry_columns() {
		return array(
			'cb'          => '<input type="checkbox" />',
			'title'       => __( 'From', 'axd-support-desk' ),
			'axd_email'   => __( 'Email', 'axd-support-desk' ),
			'axd_topic'   => __( 'Topic', 'axd-support-desk' ),
			'axd_consult' => __( 'Consultation', 'axd-support-desk' ),
			'date'        => __( 'Received', 'axd-support-desk' ),
		);
	}

	/**
	 * Inquiry list cell.
	 *
	 * @param string $col Column.
	 * @param int    $id  Inquiry.
	 */
	public static function inquiry_column( $col, $id ) {
		if ( 'axd_email' === $col ) {
			$e = AXD_SD_Tickets::meta( $id, 'email' );
			echo '<a href="mailto:' . esc_attr( $e ) . '">' . esc_html( $e ) . '</a>';
		} elseif ( 'axd_topic' === $col ) {
			echo esc_html( AXD_SD_Tickets::meta( $id, 'topic' ) );
		} elseif ( 'axd_consult' === $col ) {
			echo AXD_SD_Tickets::meta( $id, 'consult' ) ? '<span class="axd-sd-badge is-new">' . esc_html__( 'Requested', 'axd-support-desk' ) . '</span>' : '—';
		}
	}

	/**
	 * Inquiry view box.
	 */
	public static function inquiry_boxes() {
		remove_meta_box( 'submitdiv', 'axd_inquiry', 'side' );
		add_meta_box(
			'axd_sd_inquiry',
			__( 'Message', 'axd-support-desk' ),
			static function ( $post ) {
				$id   = $post->ID;
				$rows = array(
					__( 'Name', 'axd-support-desk' )         => AXD_SD_Tickets::meta( $id, 'name' ),
					__( 'Organization', 'axd-support-desk' ) => AXD_SD_Tickets::meta( $id, 'organization' ),
					__( 'Email', 'axd-support-desk' )        => AXD_SD_Tickets::meta( $id, 'email' ),
					__( 'Phone', 'axd-support-desk' )        => AXD_SD_Tickets::meta( $id, 'phone' ),
					__( 'Topic', 'axd-support-desk' )        => AXD_SD_Tickets::meta( $id, 'topic' ),
					__( 'Consultation', 'axd-support-desk' ) => AXD_SD_Tickets::meta( $id, 'consult' ) ? __( 'Yes, wants to schedule', 'axd-support-desk' ) : __( 'No', 'axd-support-desk' ),
					__( 'Received', 'axd-support-desk' )     => get_the_date( 'M j, Y g:i a', $post ),
				);
				echo '<dl class="axd-sd-dl is-wide">';
				foreach ( $rows as $label => $value ) {
					if ( '' !== (string) $value ) {
						echo '<dt>' . esc_html( $label ) . '</dt><dd>' . esc_html( $value ) . '</dd>';
					}
				}
				echo '</dl><div class="axd-sd-msg is-client"><div class="axd-sd-msg__body">' . wp_kses_post( wpautop( esc_html( AXD_SD_Tickets::meta( $id, 'message' ) ) ) ) . '</div></div>';
				$mail = 'mailto:' . rawurlencode( AXD_SD_Tickets::meta( $id, 'email' ) ) . '?subject=' . rawurlencode( __( 'Re: your inquiry to AXD Media', 'axd-support-desk' ) );
				echo '<p><a class="button button-primary" href="' . esc_url( $mail ) . '">' . esc_html__( 'Reply by email', 'axd-support-desk' ) . '</a></p>';
			},
			'axd_inquiry',
			'normal',
			'high'
		);
	}
}
