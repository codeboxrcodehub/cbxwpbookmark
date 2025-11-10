<?php
namespace CBXWPBookmark\Customizer;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use CBXWPBookmark\Customizer\Fields\CustomizerControlCheckbox;
use CBXWPBookmark\Customizer\Fields\CustomizerControlSwitch;
use CBXWPBookmark\Customizer\Fields\CustomizerControlSelect2;

/**
 * The customizer specific functionality of the plugin.
 *
 * @link       codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxwpbookmark
 * @subpackage Cbxwpbookmark/includes
 */


/**
 * The customizer specific functionality of the plugin.
 *
 * This class is used to register the customizer sections, panel, setting and control
 *
 * @package    Cbxwpbookmark
 * @subpackage Cbxwpbookmark/includes
 * @author     CBX Team  <info@codeboxr.com>
 */
class CBXWPBookmarkCustomizer {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Constructor.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'customize_register', [ $this, 'add_sections' ] );


		add_action( 'customize_controls_print_styles', [ $this, 'add_styles' ] );
		add_action( 'customize_controls_print_scripts', [ $this, 'add_scripts' ], 30 );
		add_action( 'wp_enqueue_scripts', [ $this, 'add_frontend_scripts' ] ); //for frontend
	}

	/**
	 * Add settings to the customizer.
	 *
	 * @param  WP_Customize_Manager  $wp_customize  Theme Customizer object.
	 */
	public function add_sections( $wp_customize ) {
		//load custom controls
		// require_once CBXWPBOOKMARK_ROOT_PATH . 'includes/customizer/fields/class-cbxwpbookmark-customizer-select.php';
		// require_once CBXWPBOOKMARK_ROOT_PATH . 'includes/customizer/fields/class-cbxwpbookmark-customizer-checkbox.php';
		// require_once CBXWPBOOKMARK_ROOT_PATH . 'includes/customizer/fields/class-cbxwpbookmark-customizer-switch.php';

		$wp_customize->add_panel( 'cbxwpbookmark', [
			'priority'       => 200,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'CBX Bookmark & Favorite', 'cbxwpbookmark' ),
		] );


		$this->add_section_shortcodes( $wp_customize );
	}//end add_sections

	/**
	 * Bookmark shortcodes
	 *
	 * @param  WP_Customize_Manager  $wp_customize  Theme Customizer object.
	 */
	public function add_section_shortcodes( $wp_customize ) {
		$wp_customize->add_section(
			'cbxwpbookmark_customizer_shortcodes',
			[
				'title'    => esc_html__( 'Shortcodes/Functionalities', 'cbxwpbookmark' ),
				'priority' => 10,
				'panel'    => 'cbxwpbookmark',
			]
		);


		//shortcode fields
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[shortcodes]',
			[
				'default'    => 'cbxwpbookmark-mycat,cbxwpbookmark',
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);

		$wp_customize->add_control(
			new CustomizerControlCheckbox(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcodes',
				[
					'label'       => esc_html__( 'Select Bookmark Shortcodes', 'cbxwpbookmark' ),
					'description' => esc_html__( 'Select which bookmark shortcodes', 'cbxwpbookmark' ),
					'section'     => 'cbxwpbookmark_customizer_shortcodes',
					'settings'    => 'cbxwpbookmark_customizer[shortcodes]',
					'type'        => 'cbxwpbookmark_checkbox',
					'default'     => 'cbxwpbookmark-mycat,cbxwpbookmark',
					'choices'     => apply_filters( 'cbxwpbookmark_customizer_shortcodes_choices', [
						'cbxwpbookmark-mycat' => esc_html__( 'Bookmark Categories', 'cbxwpbookmark' ),
						'cbxwpbookmark'       => esc_html__( 'Bookmark List', 'cbxwpbookmark' ),
						//'cbxwpbookmarkgrid'   => esc_html__( 'Bookmark Grid', 'cbxwpbookmark' ),
					] ),
					'input_attrs' => [
						'placeholder' => esc_html__( 'Please select shortcode(s)', 'cbxwpbookmark' ),
						'sortable'    => true,
						'fullwidth'   => true,
					]
				]
			)
		);
		//end shortcode fields

		//category shortcode
		$wp_customize->add_section(
			'cbxwpbookmark_customizer_shortcode_category',
			[
				'title'    => esc_html__( 'Shortcode Params: Bookmark Categories', 'cbxwpbookmark' ),
				'priority' => 10,
				'panel'    => 'cbxwpbookmark',
			]
		);

		//title
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark-mycat][title]',
			[
				'default'           => esc_html__( 'Bookmark Categories', 'cbxwpbookmark' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_category_title',
			[
				'label'    => esc_html__( 'Title', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_category',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark-mycat][title]',
				'type'     => 'text',
				'default'  => esc_html__( 'Bookmark Categories', 'cbxwpbookmark' ),
			]
		);

		//order
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark-mycat][order]',
			[
				'default'           => 'ASC',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_category_order',
			[
				'label'    => esc_html__( 'Order', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_category',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark-mycat][order]',
				'type'     => 'select',
				'default'  => 'ASC',
				'choices'  => [
					'ASC'  => esc_html__( 'Ascending', 'cbxwpbookmark' ),
					'DESC' => esc_html__( 'Descending', 'cbxwpbookmark' )
				]
			]
		);

		//orderby
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark-mycat][orderby]',
			[
				'default'           => 'cat_name',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_category_orderby',
			[
				'label'    => esc_html__( 'Order By', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_category',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark-mycat][orderby]',
				'type'     => 'select',
				'default'  => 'cat_name',
				'choices'  => [
					'cat_name' => esc_html__( 'Category Title', 'cbxwpbookmark' ),
					'id'       => esc_html__( 'Category ID', 'cbxwpbookmark' ),
					'privacy'  => esc_html__( 'Category Privacy', 'cbxwpbookmark' )
				]
			]
		);

		//privacy
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark-mycat][privacy]',
			[
				'default'           => '2',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'absint'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_category_privacy',
			[
				'label'    => esc_html__( 'Privacy', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_category',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark-mycat][privacy]',
				'type'     => 'select',
				'default'  => '2',
				'choices'  => [
					'2' => esc_html__( 'Ignore Privacy', 'cbxwpbookmark' ),
					'1' => esc_html__( 'Public', 'cbxwpbookmark' ),
					'0' => esc_html__( 'Privacy', 'cbxwpbookmark' )
				]
			]
		);

		//display format
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark-mycat][display]',
			[
				'default'           => '0',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'absint'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_category_display',
			[
				'label'    => esc_html__( 'Display Format', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_category',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark-mycat][display]',
				'type'     => 'select',
				'default'  => '0',
				'choices'  => [
					'0' => esc_html__( 'List', 'cbxwpbookmark' ),
					'1' => esc_html__( 'Dropdown', 'cbxwpbookmark' ),
				]
			]
		);

		//show_count
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark-mycat][show_count]',
			[
				'default'           => '0',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'absint'
			]
		);

		$wp_customize->add_control(
			new CustomizerControlSwitch(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_category_show_count',
				[
					'label'    => esc_html__( 'Show Count', 'cbxwpbookmark' ),
					'section'  => 'cbxwpbookmark_customizer_shortcode_category',
					'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark-mycat][show_count]',
					'type'     => 'cbxwpbookmark_switch',
					'default'  => '0'
				]
			)
		);

		//show_bookmarks
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark-mycat][show_bookmarks]',
			[
				'default'           => '0',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'absint'
			]
		);

		$wp_customize->add_control(
			new CustomizerControlSwitch(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_category_show_bookmarks',
				[
					'label'    => esc_html__( 'Show Bookmark Sublist', 'cbxwpbookmark' ),
					'section'  => 'cbxwpbookmark_customizer_shortcode_category',
					'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark-mycat][show_bookmarks]',
					'type'     => 'cbxwpbookmark_switch',
					'default'  => '0'
				]
			)
		);


		//allowedit
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark-mycat][allowedit]',
			[
				'default'           => '0',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'absint'
			]
		);

		$wp_customize->add_control(
			new CustomizerControlSwitch(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_category_allowedit',
				[
					'label'    => esc_html__( 'Allow Edit', 'cbxwpbookmark' ),
					'section'  => 'cbxwpbookmark_customizer_shortcode_category',
					'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark-mycat][allowedit]',
					'type'     => 'cbxwpbookmark_switch',
					'default'  => '0'
				]
			)
		);


		//honorauthor
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark-mycat][honorauthor]',
			[
				'default'           => '0',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'absint'
			]
		);

		$wp_customize->add_control(
			new CustomizerControlSwitch(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_category_honorauthor',
				[
					'label'    => esc_html__( 'In Author Archive Show for Author', 'cbxwpbookmark' ),
					'section'  => 'cbxwpbookmark_customizer_shortcode_category',
					'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark-mycat][honorauthor]',
					'type'     => 'cbxwpbookmark_switch',
					'default'  => '0'
				]
			)
		);

		//base_url
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark-mycat][base_url]',
			[
				'default'           => cbxwpbookmarks_mybookmark_page_url(),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_category_base_url',
			[
				'label'    => esc_html__( 'My Bookmark Page url(Base Url)', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_category',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark-mycat][base_url]',
				'type'     => 'text',
				'default'  => cbxwpbookmarks_mybookmark_page_url(),
			]
		);

		//cbxwpbookmark shortcode
		$wp_customize->add_section(
			'cbxwpbookmark_customizer_shortcode_bookmarks',
			[
				'title'    => esc_html__( 'Shortcode Params: Bookmark List', 'cbxwpbookmark' ),
				'priority' => 10,
				'panel'    => 'cbxwpbookmark'
			]
		);

		//title
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][title]',
			[
				'default'    => esc_html__( 'All Bookmarks', 'cbxwpbookmark' ),
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_bookmarks_title',
			[
				'label'    => esc_html__( 'Title', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_bookmarks',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark][title]',
				'type'     => 'text',
				'default'  => esc_html__( 'All Bookmarks', 'cbxwpbookmark' )
			]
		);

		//order
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][order]',
			[
				'default'    => 'DESC',
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_bookmarks_order',
			[
				'label'    => esc_html__( 'Order', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_bookmarks',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark][order]',
				'type'     => 'select',
				'default'  => 'DESC',
				'choices'  => [
					'DESC' => esc_html__( 'Descending', 'cbxwpbookmark' ),
					'ASC'  => esc_html__( 'Ascending', 'cbxwpbookmark' )
				]
			]
		);

		//orderby
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][orderby]',
			[
				'default'    => 'id',
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_bookmarks_orderby',
			[
				'label'    => esc_html__( 'Order By', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_bookmarks',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark][orderby]',
				'type'     => 'select',
				'default'  => 'id',
				'choices'  => [
					'id'          => esc_html__( 'ID', 'cbxwpbookmark' ),
					'object_id'   => esc_html__( 'Post ID', 'cbxwpbookmark' ),
					'object_type' => esc_html__( 'Post Type', 'cbxwpbookmark' ),
					'title'       => esc_html__( 'Post Title', 'cbxwpbookmark' ),
				]
			]
		);

		//limit
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][limit]',
			[
				'default'           => '10',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => [ 'CBXWPBookmarkHelper', 'sanitize_number_field' ]
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_bookmarks_limit',
			[
				'label'       => esc_html__( 'Limit', 'cbxwpbookmark' ),
				'section'     => 'cbxwpbookmark_customizer_shortcode_bookmarks',
				'settings'    => 'cbxwpbookmark_customizer[cbxwpbookmark][limit]',
				'type'        => 'number',
				'default'     => '10',
				'input_attrs' => [
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				]
			]
		);

		//https://github.com/maddisondesigns/customizer-custom-controls/blob/master/inc/customizer.php
		//solution https://raw.githubusercontent.com/maddisondesigns/customizer-custom-controls/master/inc/custom-controls.php

		//type
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][type]',
			[
				'default'           => '',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => [ $this, 'text_sanitization' ]
			]
		);


		$object_types = \CBXWPBookmarkHelper::object_types_customizer_format();


		$wp_customize->add_control(
			new CustomizerControlSelect2(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_bookmarks_type',
				[
					'label'       => esc_html__( 'Post Type(s)', 'cbxwpbookmark' ),
					'section'     => 'cbxwpbookmark_customizer_shortcode_bookmarks',
					'settings'    => 'cbxwpbookmark_customizer[cbxwpbookmark][type]',
					'type'        => 'cbxwpbookmark_select2',
					'default'     => '',
					'choices'     => $object_types,
					'input_attrs' => [
						'placeholder' => esc_html__( 'Please select post type(s)', 'cbxwpbookmark' ),
						'multiselect' => true,
					]
				]
			)
		);

		//loadmore
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][loadmore]',
			[
				'default'    => '1',
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);

		$wp_customize->add_control(
			new CustomizerControlSwitch(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_bookmarks_loadmore',
				[
					'label'             => esc_html__( 'Show Load More', 'cbxwpbookmark' ),
					'section'           => 'cbxwpbookmark_customizer_shortcode_bookmarks',
					'settings'          => 'cbxwpbookmark_customizer[cbxwpbookmark][loadmore]',
					'type'              => 'cbxwpbookmark_switch',
					'default'           => '1',
					'sanitize_callback' => 'absint'
				]
			)
		);

		//catid
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][catid]',
			[
				'default'    => '',
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_bookmarks_catid',
			[
				'label'    => esc_html__( 'Category ID', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_bookmarks',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark][catid]',
				'type'     => 'text',
				'default'  => ''
			]
		);

		//cattitle
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][cattitle]',
			[
				'default'    => '1',
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);

		$wp_customize->add_control(
			new CustomizerControlSwitch(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_bookmarks_cattitle',
				[
					'label'             => esc_html__( 'Show category title', 'cbxwpbookmark' ),
					'section'           => 'cbxwpbookmark_customizer_shortcode_bookmarks',
					'settings'          => 'cbxwpbookmark_customizer[cbxwpbookmark][cattitle]',
					'type'              => 'cbxwpbookmark_switch',
					'default'           => '1',
					'sanitize_callback' => 'absint'
				]
			)
		);

		//catcount
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][catcount]',
			[
				'default'    => '1',
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);

		$wp_customize->add_control(
			new CustomizerControlSwitch(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_bookmarks_catcount',
				[
					'label'             => esc_html__( 'Show item count per category', 'cbxwpbookmark' ),
					'section'           => 'cbxwpbookmark_customizer_shortcode_bookmarks',
					'settings'          => 'cbxwpbookmark_customizer[cbxwpbookmark][catcount]',
					'type'              => 'cbxwpbookmark_switch',
					'default'           => '1',
					'sanitize_callback' => 'absint'
				]
			)
		);

		//allowdelete
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][allowdelete]',
			[
				'default'    => '0',
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);


		$wp_customize->add_control(
			new CustomizerControlSwitch(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_bookmarks_allowdelete',
				[
					'label'             => esc_html__( 'Allow Delete', 'cbxwpbookmark' ),
					'section'           => 'cbxwpbookmark_customizer_shortcode_bookmarks',
					'settings'          => 'cbxwpbookmark_customizer[cbxwpbookmark][allowdelete]',
					'type'              => 'cbxwpbookmark_switch',
					'default'           => '0',
					'sanitize_callback' => 'absint'
				]
			)
		);

		//allowdeleteall
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][allowdeleteall]',
			[
				'default'    => '0',
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);


		$wp_customize->add_control(
			new CustomizerControlSwitch(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_bookmarks_allowdeleteall',
				[
					'label'             => esc_html__( 'Allow Delete All', 'cbxwpbookmark' ),
					'section'           => 'cbxwpbookmark_customizer_shortcode_bookmarks',
					'settings'          => 'cbxwpbookmark_customizer[cbxwpbookmark][allowdeleteall]',
					'type'              => 'cbxwpbookmark_switch',
					'default'           => '0',
					'sanitize_callback' => 'absint'
				]
			)
		);

		//showshareurl
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][showshareurl]',
			[
				'default'    => '1',
				'type'       => 'option',
				'capability' => 'manage_options'
			]
		);


		$wp_customize->add_control(
			new CustomizerControlSwitch(
				$wp_customize,
				'cbxwpbookmark_customizer_shortcode_bookmarks_showshareurl',
				[
					'label'             => esc_html__( 'Show Share Option', 'cbxwpbookmark' ),
					'section'           => 'cbxwpbookmark_customizer_shortcode_bookmarks',
					'settings'          => 'cbxwpbookmark_customizer[cbxwpbookmark][showshareurl]',
					'type'              => 'cbxwpbookmark_switch',
					'default'           => '1',
					'sanitize_callback' => 'absint'
				]
			)
		);

		//base_url
		$wp_customize->add_setting(
			'cbxwpbookmark_customizer[cbxwpbookmark][base_url]',
			[
				'default'           => cbxwpbookmarks_mybookmark_page_url(),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_text_field'
			]
		);

		$wp_customize->add_control(
			'cbxwpbookmark_customizer_shortcode_bookmarks_base_url',
			[
				'label'    => esc_html__( 'My Bookmark Page url(Base Url)', 'cbxwpbookmark' ),
				'section'  => 'cbxwpbookmark_customizer_shortcode_bookmarks',
				'settings' => 'cbxwpbookmark_customizer[cbxwpbookmark][base_url]',
				'type'     => 'text',
				'default'  => cbxwpbookmarks_mybookmark_page_url(),
			]
		);

		do_action( 'cbxwpbookmark_customizer_shortcode_controls', $wp_customize, $this );
	}//end add_section_shortcodes

	/**
	 * Number field sanitization
	 *
	 * @param $number
	 * @param $settings
	 *
	 * @return int
	 */
	public function sanitize_number_field( $number, $settings ) {
		return \CBXWPBookmarkHelper::sanitize_number_field( $number, $settings );
	}//end sanitize_number_field

	public function text_sanitization( $input ) {
		return \CBXWPBookmarkHelper::text_sanitization( $input );
	}//end text_sanitization

	/**
	 * Post type sanitization
	 *
	 * @param $number
	 * @param $settings
	 *
	 * @return int
	 */
	public function sanitize_post_types( $types, $settings ) {
		return wp_unslash( $types );
	}//end sanitize_post_types

	/**
	 * Frontend CSS styles.
	 */
	public function add_frontend_scripts() {
		if ( ! is_customize_preview() ) {
			return;
		}
	}//end add_frontend_scripts

	/**
	 * Styles to improve our form.
	 */
	public function add_styles() {
		$version          = $this->version;
		$css_url_part     = CBXWPBOOKMARK_ROOT_URL . 'assets/css/';
		$vendors_url_part = CBXWPBOOKMARK_ROOT_URL . 'assets/vendors/';

		wp_register_style( 'select2', $vendors_url_part . 'select2/select2.min.css', [], $version );
		wp_register_style( 'cbxwpbookmark-customizer', $css_url_part . 'cbxwpbookmark-customizer.css', [ 'select2' ], $version );
		wp_enqueue_style( 'cbxwpbookmark-customizer' );
	}//end add_styles

	/**
	 * Scripts to improve our form.
	 */
	public function add_scripts() {
		$version          = $this->version;
		$js_url_part      = CBXWPBOOKMARK_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBXWPBOOKMARK_ROOT_URL . 'assets/vendors/';

		wp_register_script( 'select2', $vendors_url_part . 'select2/select2.min.js', [ 'jquery' ], $version, true );
		wp_register_script( 'cbxwpbookmark-customizer', $js_url_part . 'cbxwpbookmark-customizer.js', [
			'jquery',
			'select2'
		], $this->version, true );

		$cbxwpbookmark_customizer_js_vars = apply_filters( 'cbxwpbookmark_customizer_js_vars',
			[
				'please_select'           => esc_html__( 'Please Select', 'cbxwpbookmark' ),
				'please_select_shortcode' => esc_html__( 'Please Select Shortcodes', 'cbxwpbookmark' ),
				'upload_title'            => esc_html__( 'Window Title', 'cbxwpbookmark' ),
				//'cbxbookmark_lang'        => get_user_locale(),
			] );

		wp_localize_script( 'cbxwpbookmark-customizer', 'cbxwpbookmark_customizer', $cbxwpbookmark_customizer_js_vars );
		wp_enqueue_script( 'cbxwpbookmark-customizer' );
	}//end add_scripts
}//end class CBXWPBookmarkCustomizer