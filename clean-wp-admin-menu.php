<?php
/**
 *
 * @link              http://borayalcin.me
 * @since             1.0.0
 * @package           Clean_Wp_Admin_Menu
 *
 * @wordpress-plugin
 * Plugin Name:       Clean WP Admin Menu
 * Plugin URI:        http://borayalcin.me
 * Description:       You can make rarely used items in the admin menu hidden.
 * Version:           1.0.0
 * Author:            Bora Yalcin
 * Author URI:        http://borayalcin.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       clean-wp-admin-menu
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-clean-wp-admin-menu-activator.php
 */
function activate_clean_wp_admin_menu() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clean-wp-admin-menu-activator.php';
	Clean_Wp_Admin_Menu_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-clean-wp-admin-menu-deactivator.php
 */
function deactivate_clean_wp_admin_menu() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-clean-wp-admin-menu-deactivator.php';
	Clean_Wp_Admin_Menu_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_clean_wp_admin_menu' );
register_deactivation_hook( __FILE__, 'deactivate_clean_wp_admin_menu' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-clean-wp-admin-menu.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_clean_wp_admin_menu() {

	$plugin = new Clean_Wp_Admin_Menu();
	$plugin->run();

}
run_clean_wp_admin_menu();
