<?php
/**
 * Plugin Name:       AXD Support Desk
 * Plugin URI:        https://axdmedia.com
 * Description:       Lightweight support tickets and contact inquiries for AXD Media. Generates case numbers, emails clients on every update, and keeps a full case history in the WordPress dashboard.
 * Version:           1.0.0
 * Requires at least: 6.6
 * Requires PHP:      8.1
 * Author:            AXD Media
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       axd-support-desk
 *
 * @package AXD_Support_Desk
 */

defined( 'ABSPATH' ) || exit;

define( 'AXD_SD_VERSION', '1.0.0' );
define( 'AXD_SD_FILE', __FILE__ );
define( 'AXD_SD_DIR', plugin_dir_path( __FILE__ ) );
define( 'AXD_SD_URL', plugin_dir_url( __FILE__ ) );

require_once AXD_SD_DIR . 'includes/class-axd-sd-settings.php';
require_once AXD_SD_DIR . 'includes/class-axd-sd-tickets.php';
require_once AXD_SD_DIR . 'includes/class-axd-sd-mailer.php';
require_once AXD_SD_DIR . 'includes/class-axd-sd-forms.php';
require_once AXD_SD_DIR . 'includes/class-axd-sd-admin.php';

register_activation_hook( __FILE__, array( 'AXD_SD_Tickets', 'activate' ) );

add_action(
	'plugins_loaded',
	static function () {
		AXD_SD_Settings::init();
		AXD_SD_Tickets::init();
		AXD_SD_Forms::init();
		if ( is_admin() ) {
			AXD_SD_Admin::init();
		}
	}
);
