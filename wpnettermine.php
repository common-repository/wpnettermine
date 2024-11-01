<?php
/**
 * WPNetTermine
 *
 * WordPress integration for the NetTermine module of HausManager (www.hausmanager.de).
 *
 * @package   WPNetTermine
 * @author    Marc Nilius <mail@marcnilius.de>
 * @license   GPL-2.0+
 * @link      http://www.marcnilius.de
 * @copyright 2014 Marc Nilius
 *
 * @wordpress-plugin
 * Plugin Name:       WPNetTermine
 * Plugin URI:        http://www.marcnilius.de
 * Description:       WordPress integration for the NetTermine module of HausManager (www.hausmanager.de).
 * Version:           0.0.3
 * Author:            Marc Nilius
 * Author URI:
 * Text Domain:       wpnettermine
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-wpnettermine.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'WPNetTermine', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WPNetTermine', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'WPNetTermine', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wpnettermine-admin.php' );
	add_action( 'plugins_loaded', array( 'WPNetTermine_Admin', 'get_instance' ) );

}
