<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxwpbookmark
 * @subpackage Cbxwpbookmark/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cbxwpbookmark
 * @subpackage Cbxwpbookmark/includes
 * @author     CBX Team  <info@codeboxr.com>
 */
class CBXWPBookmark {
	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.7.13
	 */
	private static $instance = null;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = CBXWPBOOKMARK_PLUGIN_NAME;
		$this->version     = CBXWPBOOKMARK_PLUGIN_VERSION;

		$this->load_dependencies();

		$this->define_common_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$plugin_blocks     = new CBXWPBookmark_Blocks( $this->get_plugin_name(), $this->get_version() );
		$plugin_customizer = new CBXWPBookmark_Customizer( $this->get_plugin_name(), $this->get_version() );
		$plugin_shortcodes = new CBXWPBookmark_Shortcodes( $this->get_plugin_name(), $this->get_version() );
	}//end of constructor

	/**
	 * Singleton Instance.
	 * @return CBXWPBookmark|self|null
	 * @since 1.8.13
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}//end method instance

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.8.13
	 */
	public function __clone() {
		cbxwpbookmark_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'cbxwpbookmark' ), '1.8.13' );
	}//end method clone

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.8.13
	 */
	public function __wakeup() {
		cbxwpbookmark_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'cbxwpbookmark' ), '1.8.13' );
	}//end method wakeup

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - CBXWPBookmark_Loader. Orchestrates the hooks of the plugin.
	 * - CBXWPBookmark_i18n. Defines internationalization functionality.
	 * - CBXWPBookmark_Admin. Defines all hooks for the admin area.
	 * - CBXWPBookmark_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxwpbookmark-tpl-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxwpbookmark-setting.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxwpbookmark-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxwpbookmark-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxwpbookmark-category.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxwpbookmark-blocks.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxwpbookmark-shortcodes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cbxwpbookmark-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cbxwpbookmark-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/customizer/class-cbxwpbookmark-customizer.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxwpbookmark-functions.php';
	}//end method load_dependencies

	/**
	 * All the common hooks
	 *
	 * @since    1.1.1
	 * @access   private
	 */
	private function define_common_hooks() {
		//add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ] );
		add_action( 'init', [ $this, 'load_plugin_textdomain' ] );
	}//end method define_common_hooks

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.1
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'cbxwpbookmark', false, CBXWPBOOKMARK_ROOT_PATH . 'languages/' );
	}//end method load_plugin_textdomain

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		global $wp_version;

		$plugin_admin = new CBXWPBookmark_Admin( $this->get_plugin_name(), $this->get_version() );

		//add/edit category submission
		add_action( 'admin_init', [ $plugin_admin, 'add_edit_category' ] );

		//admin menus
		add_action( 'admin_menu', [ $plugin_admin, 'admin_pages' ] );

		//screen options for admin item listing
		add_filter( 'set-screen-option', [ $plugin_admin, 'cbxwpbookmark_bookmark_list_per_page' ], 10, 3 );
		add_filter( 'set-screen-option', [ $plugin_admin, 'cbxwpbookmark_bookmark_category_per_page' ], 10, 3 );

		add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueue_scripts' ] );


		//adding the setting action
		add_action( 'admin_init', [ $plugin_admin, 'setting_init' ] );
		add_action( 'admin_init', [ $plugin_admin, 'on_bookmarkpost_delete' ] );
		add_filter( 'cbxwpbookmark_setting_sections', [ $plugin_admin, 'extend_sections' ], 10 );


		//plugin notices, active, upgrade, deactivation
		add_filter( 'plugin_action_links_' . CBXWPBOOKMARK_BASE_NAME, [ $plugin_admin, 'plugin_action_links' ] );
		add_filter( 'plugin_row_meta', [ $plugin_admin, 'plugin_row_meta' ], 10, 4 );

		add_action( 'plugins_loaded', [ $plugin_admin, 'plugin_upgrader_process_complete' ] );
		add_action( 'admin_notices', [ $plugin_admin, 'plugin_activate_upgrade_notices' ] );
		add_action( 'after_plugin_row_cbxwpbookmarkaddon/cbxwpbookmarkaddon.php', [
			$plugin_admin,
			'custom_message_after_plugin_row_proaddon'
		], 10, 2 );
		add_action( 'after_plugin_row_cbxwpbookmarkmycred/cbxwpbookmarkmycred.php', [
			$plugin_admin,
			'custom_message_after_plugin_row_mycredaddon'
		], 10, 2 );

		//page auto created
		add_action( 'wp_ajax_cbxwpbookmark_autocreate_page', [ $plugin_admin, 'cbxwpbookmark_autocreate_page' ] );

		//for bookmark log listing screens
		add_filter( 'manage_cbx-bookmark_page_cbxwpbookmark_columns', [ $plugin_admin, 'log_listing_screen_cols' ] );
		add_filter( 'manage_cbx-bookmark_page_cbxwpbookmarkcats_columns', [
			$plugin_admin,
			'category_listing_screen_cols'
		] );

		//ajax plugin reset
		add_action( 'wp_ajax_cbxwpbookmark_settings_reset_load', [ $plugin_admin, 'settings_reset_load' ] );
		add_action( 'wp_ajax_cbxwpbookmark_settings_reset', [ $plugin_admin, 'plugin_reset' ] );
		add_action( 'cbxwpbookmark_plugin_reset', [ $plugin_admin, 'plugin_reset_extend' ] );
	}//end define_admin_hooks


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new CBXWPbookmark_Public( $this->get_plugin_name(), $this->get_version() );

		add_action( 'wp_enqueue_scripts', [ $plugin_public, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $plugin_public, 'enqueue_scripts' ] );


		//add_filter( 'the_content', $plugin_public, "bookmark_auto_integration" );
		add_filter( 'the_content', [ $plugin_public, 'the_content_auto_integration' ] );
		add_filter( 'the_excerpt', [ $plugin_public, 'the_excerpt_auto_integration' ] );

		add_filter( 'the_content', [ $plugin_public, 'the_content_customizer_method' ] );

		add_filter( 'body_class', [ $plugin_public, 'add_theme_class' ] );


		add_action( 'wp_ajax_cbx_add_bookmark_category', [ $plugin_public, 'add_category' ] );         //from popup
		add_action( 'wp_ajax_cbx_add_bookmark_category_std', [
			$plugin_public,
			'add_category_std'
		] ); //from category listing
		add_action( 'wp_ajax_cbx_edit_bookmark_category', [ $plugin_public, 'edit_category' ] );


		// Delete Category from Front Admin
		add_action( 'wp_ajax_cbx_delete_bookmark_category', [ $plugin_public, 'delete_bookmark_category' ] );
		//add_action('wp_ajax_nopriv_cbx_delete_bookmark_category', [$plugin_public, 'delete_bookmark_category']);

		// Update Category from Front User Admin
		add_action( 'wp_ajax_cbx_update_bookmark_category', [ $plugin_public, 'update_bookmark_category' ] );


		// Delete Category from Front Admin (delete_bookmark_post)
		add_action( 'wp_ajax_cbx_delete_bookmark_post', [ $plugin_public, 'delete_bookmark_post' ] );


		//find all bookmark category by loggedin user ajax hook
		add_action( 'wp_ajax_cbx_find_category', [ $plugin_public, 'find_category' ] );


		//add bookmark for logged-in user ajax hook
		add_action( 'wp_ajax_cbx_add_bookmark', [ $plugin_public, 'add_bookmark' ] );


		//load more bookmark ajax
		add_action( 'wp_ajax_cbx_bookmark_loadmore', [ $plugin_public, 'bookmark_loadmore' ] );

		//classic widget
		add_action( 'widgets_init', [ $plugin_public, 'init_widgets' ] );

		add_action( 'init', [ $plugin_public, 'init_misc' ] );


		//visual composer widget
		//add_action( 'vc_before_init',[ $plugin_public, 'vc_before_init_actions'], 12 );//priority 12 works for both old and new version of vc
		add_action( 'vc_before_init', [
			$plugin_public,
			'vc_before_init_actions'
		] );                  //priority 12 works for both old and new version of vc

		//load bookmarks on click on category
		add_action( 'wp_ajax_cbx_load_bookmarks_sublist', [ $plugin_public, 'load_bookmarks_sublist' ] );
		add_action( 'wp_ajax_nopriv_cbx_load_bookmarks_sublist', [ $plugin_public, 'load_bookmarks_sublist' ] );


		//add_action('admin_init', [$plugin_public,  'admin_init_ajax_lang']);

		//delete all bookmarks of any user by user from frontend
		add_action( 'wp_ajax_cbxwpbkmark_delete_all_bookmarks_by_user', [
			$plugin_public,
			'delete_all_bookmarks_by_user'
		] );

		//bbpress
		//add_filter('bbp_get_single_forum_description', [$plugin_public, 'bbp_get_single_forum_description'], 10, 3);
		add_filter( 'bbp_template_before_single_forum', [ $plugin_public, 'bbp_template_before_single_forum' ] );
		add_filter( 'bbp_template_after_single_forum', [ $plugin_public, 'bbp_template_after_single_forum' ] );
		add_action( 'bbp_template_before_single_topic', [ $plugin_public, 'bbp_template_before_single_topic' ] );
		add_action( 'bbp_template_after_single_topic', [ $plugin_public, 'bbp_template_after_single_topic' ] );
	}//end define_public_hooks

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}//end method get_plugin_name


	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}//end method get_version
}//end class CBXWPBookmark
