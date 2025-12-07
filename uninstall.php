<?php
use CBXWPBookmark\CBXWPBookmarkUninstall;

/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       https://codeboxr.com
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
function cbxwpbookmark_uninstall() {
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

	CBXWPBookmarkUninstall::uninstall();
}//end function cbxwpbookmark_uninstall

if ( ! defined( 'CBXWPBOOKMARK_PLUGIN_NAME' ) ) {
	cbxwpbookmark_uninstall();
}