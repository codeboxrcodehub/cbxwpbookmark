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
 * Version:           2.0.4
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
defined( 'CBXWPBOOKMARK_PLUGIN_VERSION' ) or define( 'CBXWPBOOKMARK_PLUGIN_VERSION', '2.0.4' );
defined( 'CBXWPBOOKMARK_BASE_NAME' ) or define( 'CBXWPBOOKMARK_BASE_NAME', plugin_basename( __FILE__ ) );
defined( 'CBXWPBOOKMARK_ROOT_PATH' ) or define( 'CBXWPBOOKMARK_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CBXWPBOOKMARK_ROOT_URL' ) or define( 'CBXWPBOOKMARK_ROOT_URL', plugin_dir_url( __FILE__ ) );


defined( 'CBXWPBOOKMARK_DEV_MODE' ) or define( 'CBXWPBOOKMARK_DEV_MODE', false );

defined( 'CBXWPBOOKMARK_PHP_MIN_VERSION' ) or define( 'CBXWPBOOKMARK_PHP_MIN_VERSION', '7.4' );
defined( 'CBXWPBOOKMARK_WP_MIN_VERSION' ) or define( 'CBXWPBOOKMARK_WP_MIN_VERSION', '5.3' );
defined( 'CBXWPBOOKMARK_PRO_VERSION' ) or define( 'CBXWPBOOKMARK_PRO_VERSION', '2.0.4' );


// Include the main Bookmark class.
if ( ! class_exists( 'CBXWPBookmark', false ) ) {
	include_once CBXWPBOOKMARK_ROOT_PATH . 'includes/CBXWPBookmark.php';
}

register_activation_hook( __FILE__, 'cbxwpbookmark_activate' );
register_deactivation_hook( __FILE__, 'cbxwpbookmark_deactivate' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cbxwpbookmark-activator.php
 */
function cbxwpbookmark_activate() {
	$wp_version  = CBXWPBOOKMARK_WP_MIN_VERSION;
	$php_version = CBXWPBOOKMARK_PHP_MIN_VERSION;

	if ( ! cbxwpbookmark_compatible_wp_version( $wp_version ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		/* translators: %s: wordpress version  */
		wp_die( sprintf( esc_html__( 'CBX Bookmark plugin requires WordPress %s or higher!', 'cbxwpbookmark' ), esc_attr($wp_version) ) );
	}

	if ( ! cbxwpbookmark_compatible_php_version( $php_version ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		/* translators: %s: php version  */
		wp_die( sprintf( esc_html__( 'CBX Bookmark plugin requires PHP %s or higher!', 'cbxwpbookmark' ), esc_attr($php_version) ) );
	}

	cbxwpbookmark_core();

	//load orm
	CBXWPBookmarkHelper::load_orm();

	//do some extra on plugin activation
	CBXWPBookmarkHelper::activate();

	CBXWPBookmarkHelper::customizer_default_adjust( true );
}//end memthod cbxwpbookmark_activate

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbxwpbookmark-deactivator.php
 */
function cbxwpbookmark_deactivate() {
	//require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpbookmark-deactivator.php';
	CBXWPBookmarkHelper::deactivate();
}//end method cbxwpbookmark_deactivate

/**
 * Checking wp version
 *
 * @param $version
 *
 * @return bool
 * @since 1.0.0
 */
function cbxwpbookmark_compatible_wp_version( $version = CBXWPBOOKMARK_WP_MIN_VERSION ) {
	if ( version_compare( $GLOBALS['wp_version'], $version, '<' ) ) {
		return false;
	}

	// Add sanity checks for other version requirements here
	return true;
}//end function cbxwpbookmark_compatible_wp_version

/**
 * Checking php version
 *
 * @param $version
 *
 * @return bool
 * @since 1.0.0
 */
function cbxwpbookmark_compatible_php_version( $version = CBXWPBOOKMARK_PHP_MIN_VERSION ) {
	if ( version_compare( PHP_VERSION, $version, '<' ) ) {
		return false;
	}

	return true;
}//end function cbxwpbookmark_compatible_php_version

/**
 * Returns the main instance of CBXWPBookmark.
 *
 * @since  1.0.0
 */
function cbxwpbookmark_core() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	global $cbxwpbookmark_core;

	if ( ! isset( $cbxwpbookmark_core ) ) {
		$cbxwpbookmark_core = cbxwpbookmark_run_core();
	}

	return $cbxwpbookmark_core;
}//end method cbxwpbookmark_core



/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
//require plugin_dir_path( __FILE__ ) . 'includes/class-cbxwpbookmark.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.7.13
 */
function cbxwpbookmark_run_core() {
	return CBXWPBookmark::instance();
}//end function cbxwpbookmark_run_core

$GLOBALS['cbxwpbookmark_core'] = cbxwpbookmark_run_core();