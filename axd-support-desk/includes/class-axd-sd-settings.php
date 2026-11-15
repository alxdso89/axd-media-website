<?php
/**
 * Settings: notification email, sender name, client IDs.
 *
 * @package AXD_Support_Desk
 */

defined( 'ABSPATH' ) || exit;

/**
 * Settings page and accessors.
 */
class AXD_SD_Settings {

	const OPTION = 'axd_sd_settings';

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ), 20 );
		add_action( 'admin_init', array( __CLASS__, 'register' ) );
	}

	/**
	 * Defaults.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'notify_email' => 'info@axdmedia.com',
			'from_name'    => 'AXD Media Support',
			'client_ids'   => '',
			'phone'        => '(305) 929-3239',
			'case_prefix'  => 'AXD',
		);
	}

	/**
	 * Get a setting.
	 *
	 * @param string $key Key.
	 * @return string
	 */
	public static function get( $key ) {
		$opts = wp_parse_args( get_option( self::OPTION, array() ), self::defaults() );
		return isset( $opts[ $key ] ) ? $opts[ $key ] : '';
	}

	/**
	 * Client IDs as an array of [ code => label ].
	 *
	 * Each line of the setting is "CODE" or "CODE | Organization name".
	 * Only the code is ever shown publicly.
	 *
	 * @return array
	 */
	public static function client_ids() {
		$out = array();
		foreach ( preg_split( '/\r\n|\r|\n/', (string) self::get( 'client_ids' ) ) as $line ) {
			$parts = array_map( 'trim', explode( '|', $line, 2 ) );
			if ( '' === $parts[0] ) {
				continue;
			}
			$code         = strtoupper( sanitize_text_field( $parts[0] ) );
			$out[ $code ] = isset( $parts[1] ) ? sanitize_text_field( $parts[1] ) : '';
		}
		return $out;
	}

	/**
	 * Add the settings page under Support Tickets.
	 */
	public static function menu() {
		add_submenu_page(
			'edit.php?post_type=axd_ticket',
			__( 'Support Desk Settings', 'axd-support-desk' ),
			__( 'Settings', 'axd-support-desk' ),
			'manage_options',
			'axd-sd-settings',
			array( __CLASS__, 'render' )
		);
	}

	/**
	 * Register the option.
	 */
	public static function register() {
		register_setting(
			'axd_sd',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				'default'           => self::defaults(),
			)
		);
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $in Raw input.
	 * @return array
	 */
	public static function sanitize( $in ) {
		$d = self::defaults();
		return array(
			'notify_email' => is_email( $in['notify_email'] ?? '' ) ? sanitize_email( $in['notify_email'] ) : $d['notify_email'],
			'from_name'    => sanitize_text_field( $in['from_name'] ?? $d['from_name'] ),
			'client_ids'   => sanitize_textarea_field( $in['client_ids'] ?? '' ),
			'phone'        => sanitize_text_field( $in['phone'] ?? $d['phone'] ),
			'case_prefix'  => strtoupper( preg_replace( '/[^A-Za-z]/', '', $in['case_prefix'] ?? '' ) ) ?: $d['case_prefix'],
		);
	}

	/**
	 * Render the settings page.
	 */
	public static function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$o = wp_parse_args( get_option( self::OPTION, array() ), self::defaults() );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Support Desk Settings', 'axd-support-desk' ); ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'axd_sd' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="axd-notify"><?php esc_html_e( 'Notification email', 'axd-support-desk' ); ?></label></th>
						<td><input type="email" id="axd-notify" class="regular-text" name="<?php echo esc_attr( self::OPTION ); ?>[notify_email]" value="<?php echo esc_attr( $o['notify_email'] ); ?>">
						<p class="description"><?php esc_html_e( 'New tickets and contact inquiries are sent here. Client emails use this as the Reply-To address.', 'axd-support-desk' ); ?></p></td>
					</tr>
					<tr>
						<th scope="row"><label for="axd-from"><?php esc_html_e( 'Sender name', 'axd-support-desk' ); ?></label></th>
						<td><input type="text" id="axd-from" class="regular-text" name="<?php echo esc_attr( self::OPTION ); ?>[from_name]" value="<?php echo esc_attr( $o['from_name'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="axd-phone"><?php esc_html_e( 'Support phone', 'axd-support-desk' ); ?></label></th>
						<td><input type="text" id="axd-phone" class="regular-text" name="<?php echo esc_attr( self::OPTION ); ?>[phone]" value="<?php echo esc_attr( $o['phone'] ); ?>">
						<p class="description"><?php esc_html_e( 'Shown in emails for urgent issues.', 'axd-support-desk' ); ?></p></td>
					</tr>
					<tr>
						<th scope="row"><label for="axd-prefix"><?php esc_html_e( 'Case number prefix', 'axd-support-desk' ); ?></label></th>
						<td><input type="text" id="axd-prefix" class="small-text" name="<?php echo esc_attr( self::OPTION ); ?>[case_prefix]" value="<?php echo esc_attr( $o['case_prefix'] ); ?>">
						<p class="description"><?php esc_html_e( 'Example: AXD-261115-0001', 'axd-support-desk' ); ?></p></td>
					</tr>
					<tr>
						<th scope="row"><label for="axd-clients"><?php esc_html_e( 'Client IDs', 'axd-support-desk' ); ?></label></th>
						<td><textarea id="axd-clients" class="large-text code" rows="8" name="<?php echo esc_attr( self::OPTION ); ?>[client_ids]"><?php echo esc_textarea( $o['client_ids'] ); ?></textarea>
						<p class="description"><?php esc_html_e( 'One per line. Optionally add the organization name after a vertical bar, for example: ABCD | Example Clinic. Only the code is shown on the website; the name is only visible to you.', 'axd-support-desk' ); ?></p></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
			<h2><?php esc_html_e( 'Email delivery', 'axd-support-desk' ); ?></h2>
			<p><?php esc_html_e( 'For reliable delivery, connect WordPress to your business email (Microsoft 365 or Google Workspace) with an SMTP plugin such as WP Mail SMTP. This plugin sends all email through WordPress, so it works with any SMTP setup.', 'axd-support-desk' ); ?></p>
		</div>
		<?php
	}
}
