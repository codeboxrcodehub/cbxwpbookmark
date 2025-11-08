<?php
namespace Cbx\Bookmark;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//use Cbx\Bookmark\Helpers\CBXWPBookmarkHelper;

/**
 * Class CBXWPBookmarkMisc
 * @package Cbx\Bookmark
 * @since 1.0.0
 */
class CBXWPBookmarkMisc {

	/**
	 * The ID of this plugin.
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * @var CBXWPBookmarkSettings
	 * @since    1.0.0
	 * @access   private
	 */
	private $settings;

	/**
	 * @var string
	 * @since 1.0.0
	 * @access private
	 */
	private $plugin_basename;

	public function __construct() {
		$this->plugin_name = CBXWPBOOKMARK_PLUGIN_NAME;
		$this->version     = CBXWPBOOKMARK_PLUGIN_VERSION;
		$this->settings    = new CBXWPBookmarkSettings();

		if ( defined( CBXWPBOOKMARK_DEV_MODE ) ) {
			$this->version = time();
		}

		//get plugin base file name
		$this->plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
	}

	/**
	 * Add module attribute to script loader
	 *
	 * @param $tag
	 * @param $handle
	 * @param $src
	 *
	 * @return mixed|string
	 */
	public function add_module_to_script( $tag, $handle, $src ) {
		$handles = [
			'cbxwpbookmark_vue_main',
			'cbxwpbookmark_vue_dev',
			'cbxwpbookmark_category_vue_dev',
			'cbxwpbookmark_category_vue_main',
			'cbxwpbookmark_tools_vue_dev',
			'cbxwpbookmark_tools_vue_main',
			'cbxwpbookmark_dashboard_vue_dev',
			'cbxwpbookmark_dashboard_vue_main'
		];

		if ( in_array( $handle, $handles ) ) {
			$tag = '<script type="module" id="' . esc_attr( $handle ) . '" src="' . esc_url( $src ) . '"></script>';
		}

		return $tag;
	}//end method add_module_to_script
}//end class CBXWPBookmarkMisc