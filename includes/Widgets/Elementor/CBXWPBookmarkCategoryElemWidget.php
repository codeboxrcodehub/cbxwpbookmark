<?php

namespace CBXWPBookmark\Widgets\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CBX Bookmark Category Elementor Widget
 */
class CBXWPBookmarkCategoryElemWidget extends \Elementor\Widget_Base {

	/**
	 * Retrieve category widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'cbxwpbookmarkcategory';
	}//end method get_name

	/**
	 * Retrieve category widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'CBX Bookmark Categories', 'cbxwpbookmark' );
	}//end method get_title

	/**
	 * Get widget categories.
	 *
	 * Retrieve the widget categories.
	 *
	 * @return array Widget categories.
	 * @since  1.0.10
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'cbxwpbookmark' ];
	}//end method get_categories

	/**
	 * Retrieve category widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'cbxwpbookmars-category-icon';
	}//end method get_icon

	protected function register_controls() {
		$this->start_controls_section(
			'section_cbxwpbookmarkcategory',
			[
				'label' => esc_html__( 'Bookmark Categories Settings', 'cbxwpbookmark' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'cbxwpbookmark' ),
				'description' => esc_html__( 'Keep empty to hide', 'cbxwpbookmark' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Bookmark Categories', 'cbxwpbookmark' ),
			]
		);

		$this->add_control(
			'order',
			[
				'label'       => esc_html__( 'Display order', 'cbxwpbookmark' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'ASC',
				'placeholder' => esc_html__( 'Select order', 'cbxwpbookmark' ),
				'options'     => [
					'ASC'  => esc_html__( 'Ascending', 'cbxwpbookmark' ),
					'DESC' => esc_html__( 'Descending', 'cbxwpbookmark' ),
				]
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'       => esc_html__( 'Display order by', 'cbxwpbookmark' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'cat_name',
				'placeholder' => esc_html__( 'Select order by', 'cbxwpbookmark' ),
				'options'     => [
					'cat_name' => esc_html__( 'Category Name', 'cbxwpbookmark' ),
					'id'       => esc_html__( 'Category Id', 'cbxwpbookmark' ),
					'privacy'  => esc_html__( 'Privacy', 'cbxwpbookmark' ),
				]
			]
		);

		$this->add_control(
			'privacy',
			[
				'label'       => esc_html__( 'Privacy', 'cbxwpbookmark' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 2,
				'placeholder' => esc_html__( 'Select privacy', 'cbxwpbookmark' ),
				'options'     => [
					'2' => esc_html__( 'Ignore privacy', 'cbxwpbookmark' ),
					'1' => esc_html__( 'Public', 'cbxwpbookmark' ),
					'0' => esc_html__( 'Private', 'cbxwpbookmark' ),
				]
			]
		);

		$this->add_control(
			'display',
			[
				'label'       => esc_html__( 'Display method', 'cbxwpbookmark' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 0,
				'placeholder' => esc_html__( 'Select display', 'cbxwpbookmark' ),
				'options'     => [
					'0' => esc_html__( 'List', 'cbxwpbookmark' ),
					'1' => esc_html__( 'Dropdown', 'cbxwpbookmark' )
				]
			]
		);

		$this->add_control(
			'show_count',
			[
				'label'        => esc_html__( 'Show count', 'cbxwpbookmark' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cbxwpbookmark' ),
				'label_off'    => esc_html__( 'No', 'cbxwpbookmark' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'allowedit',
			[
				'label'        => esc_html__( 'Allow Edit', 'cbxwpbookmark' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cbxwpbookmark' ),
				'label_off'    => esc_html__( 'No', 'cbxwpbookmark' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);


		$this->add_control(
			'show_bookmarks',
			[
				'label'        => esc_html__( 'Show bookmarks as sublist', 'cbxwpbookmark' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cbxwpbookmark' ),
				'label_off'    => esc_html__( 'No', 'cbxwpbookmark' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);


		$this->add_control(
			'base_url',
			[
				'label'       => esc_html__( 'My Bookmark Page url(Base Url)', 'cbxwpbookmark' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => cbxwpbookmarks_mybookmark_page_url(),
				'label_block' => true
			]
		);

		$this->end_controls_section();
	}//end method register_controls


	/**
	 * Convert yes/no to boolean on/off
	 *
	 * @param  string  $value
	 *
	 * @return string
	 */
	public static function yes_no_to_on_off( $value = '' ) {
		if ( $value === 'yes' ) {
			return 'on';
		}

		return 'off';
	}//end yes_no_to_on_off

	/**
	 * Convert yes/no switch to boolean 1/0
	 *
	 * @param  string  $value
	 *
	 * @return int
	 */
	public static function yes_no_to_1_0( $value = '' ) {
		if ( $value === 'yes' ) {
			return 1;
		}

		return 0;
	}//end yes_no_to_1_0

	/**
	 * Render category widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		$attr = [];

		$attr['title']          = esc_attr( $settings['title'] );
		$attr['order']          = strtoupper( esc_attr( $settings['order'] ) );
		$attr['orderby']        = esc_attr( $settings['orderby'] );
		$attr['privacy']        = absint( $settings['privacy'] );
		$attr['display']        = absint( $settings['display'] );
		$attr['show_count']     = $this->yes_no_to_1_0( $settings['show_count'] );
		$attr['show_bookmarks'] = $this->yes_no_to_1_0( $settings['show_bookmarks'] );
		$attr['allowedit']      = $this->yes_no_to_1_0( $settings['allowedit'] );
		$attr['base_url']       = esc_url( $settings['base_url'] );


		//take care some fields
		$order    = $attr['order'];
		$order_by = $attr['orderby'];

		$order_keys = cbxwpbookmarks_get_order_keys();
		if ( ! in_array( $order, $order_keys ) ) {
			$order = 'ASC';
		}
		$attr['order'] = $order;

		$cat_sortable_keys = cbxwpbookmarks_cat_sortable_keys();
		if ( ! in_array( $order_by, $cat_sortable_keys ) ) {
			$order_by = 'cat_name';
		}
		$attr['orderby'] = $order_by;

		$attr = apply_filters( 'cbxwpbookmark_elementor_shortcode_builder_attr', $attr, $settings, 'cbxwpbookmark-mycat' );

		$attr_html = '';

		foreach ( $attr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
		}

		echo do_shortcode( '[cbxwpbookmark-mycat ' . $attr_html . ']' );
	}//end method render
}//end method CBXWPBookmarkCategoryElemWidget