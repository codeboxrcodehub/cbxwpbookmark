<?php

/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxwpbookmark
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * The code that runs during plugin uninstall.
 */
function uninstall_cbxwpbookmark() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpbookmark-setting.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpbookmark-helper.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpbookmark-uninstall.php';

	cbxwpbookmark_Uninstall::uninstall();
}//end function uninstall_cbxwpbookmark

if ( ! defined( 'CBXWPBOOKMARK_PLUGIN_NAME' ) ) {
	uninstall_cbxwpbookmark();
}