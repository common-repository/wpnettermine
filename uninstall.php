<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   WPNetTermine
 * @author    Marc Nilius <mail@marcnilius.de>
 * @license   GPL-2.0+
 * @link      http://www.marcnilius.de
 * @copyright 2014 Marc Nilius
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// make sure,  the options are definetly removed
delete_option('wpnettermine_settings');
