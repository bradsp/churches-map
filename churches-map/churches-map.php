<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://blog.thepowers.us
 * @since             1.0.0
 * @package           Churches_Map
 *
 * @wordpress-plugin
 * Plugin Name:       Churches Map
 * Plugin URI:        http://www.mccbaptists.org
 * Description:       Allows placing a Google Map on a page to show churches in the area. Gets the list from a Pods.io advanced custom post type. This Pods.IO custom type must be built for this plugin to work. Based on Boilerplate.
 * Version:           1.0.0
 * Author:            Bradley Powers
 * Author URI:        http://blog.thepowers.us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       churches-map
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-churches-map-activator.php
 */
function activate_churches_map() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-churches-map-activator.php';
	Churches_Map_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-churches-map-deactivator.php
 */
function deactivate_churches_map() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-churches-map-deactivator.php';
	Churches_Map_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_churches_map' );
register_deactivation_hook( __FILE__, 'deactivate_churches_map' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-churches-map.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_churches_map() {

	$plugin = new Churches_Map();
	$plugin->run();

}
run_churches_map();
