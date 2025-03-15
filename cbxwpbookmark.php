<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              codeboxr.com
 * @since             1.0.0
 * @package           Cbxwpbookmark
 *
 * @wordpress-plugin
 * Plugin Name:       CBX Bookmark & Favorite
 * Plugin URI:        https://codeboxr.com/product/cbx-wordpress-bookmark
 * Description:       List/category based bookmark for WordPress, create your own private or public list of favorite posts, page, custom object
 * Version:           1.9.5
 * Author:            Codeboxr Team
 * Author URI:        https://codeboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbxwpbookmark
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


defined( 'CBXWPBOOKMARK_PLUGIN_NAME' ) or define( 'CBXWPBOOKMARK_PLUGIN_NAME', 'cbxwpbookmark' );
defined( 'CBXWPBOOKMARK_PLUGIN_VERSION' ) or define( 'CBXWPBOOKMARK_PLUGIN_VERSION', '1.9.5' );
defined( 'CBXWPBOOKMARK_BASE_NAME' ) or define( 'CBXWPBOOKMARK_BASE_NAME', plugin_basename( __FILE__ ) );
defined( 'CBXWPBOOKMARK_ROOT_PATH' ) or define( 'CBXWPBOOKMARK_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CBXWPBOOKMARK_ROOT_URL' ) or define( 'CBXWPBOOKMARK_ROOT_URL', plugin_dir_url( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cbxwpbookmark-activator.php
 */
function activate_cbxwpbookmark() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpbookmark-activator.php';
	CBXWPBookmark_Activator::activate();                 //db table creates

	CBXWPBookmarkHelper::customizer_default_adjust( true );
}//end memthod activate_cbxwpbookmark

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbxwpbookmark-deactivator.php
 */
function deactivate_cbxwpbookmark() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpbookmark-deactivator.php';
	CBXWPBookmark_Deactivator::deactivate();
}//end method deactivate_cbxwpbookmark


register_activation_hook( __FILE__, 'activate_cbxwpbookmark' );
register_deactivation_hook( __FILE__, 'deactivate_cbxwpbookmark' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpbookmark.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.7.13
 */
function run_cbxwpbookmark() {
	return CBXWPBookmark::instance();
}//end function run_cbxwpbookmark

$GLOBALS['cbxwpbookmark_core'] = run_cbxwpbookmark();