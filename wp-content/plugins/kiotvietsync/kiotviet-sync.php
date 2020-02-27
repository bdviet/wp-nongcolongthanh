<?php

/**
 *
 *
 * @wordpress-plugin
 * Plugin Name:       KiotVietSync
 * Plugin URI:        https://kiotviet.vn
 * Description:       Đây là plugin hỗ trợ đồng bộ sản phẩm, đơn hàng giữa website Wordpress với Kiotviet
 * Version:           1.1.0
 * Author:            Kiotviet
 * Author URI:        https://kiotviet.vn
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kiotviet-sync
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define('KIOTVIET_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

define('KIOTVIET_PLUGIN_URL', plugin_dir_url( __FILE__ ));

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'KIOTVIET_PLUGIN_VERSION', '1.1.0' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kiotviet-sync-activator.php
 */

include_once "bootstrap.php";

function activate_kiotviet_sync() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kiotviet-sync-activator.php';
	Kiotviet_Sync_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kiotviet-sync-deactivator.php
 */
function deactivate_kiotviet_sync() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kiotviet-sync-deactivator.php';
	Kiotviet_Sync_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kiotviet_sync' );
register_deactivation_hook( __FILE__, 'deactivate_kiotviet_sync' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kiotviet-sync.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kiotviet_sync() {

	$plugin = new Kiotviet_Sync();
	$plugin->run();

}
run_kiotviet_sync();
