<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://clarknikdelpowell.com/agency/people/glenn/
 * @since             1.0.0
 * @package           Render_Unblocker
 *
 * @wordpress-plugin
 * Plugin Name:       Render Unblocker
 * Plugin URI:        https://github.com/Clark-Nikdel-Powell/render-unblocker
 * Description:       Plugin for optimized loading of styles and scripts.
 * Version:           1.5
 * Author:            Glenn Welser
 * Author URI:        https://clarknikdelpowell.com/agency/people/glenn/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       render-unblocker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-render-unblocker-activator.php
 */
function activate_render_unblocker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-render-unblocker-activator.php';
	Render_Unblocker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-render-unblocker-deactivator.php
 */
function deactivate_render_unblocker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-render-unblocker-deactivator.php';
	Render_Unblocker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_render_unblocker' );
register_deactivation_hook( __FILE__, 'deactivate_render_unblocker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-render-unblocker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_render_unblocker() {

	$plugin = new Render_Unblocker();
	$plugin->run();

}
run_render_unblocker();
