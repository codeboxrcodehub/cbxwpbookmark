<?php

namespace CBXWPBookmark_ElemWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CBX Bookmark - My Bookmarks elementor widget
 *
 * Class CBXWPBookmarkMyBookmark_ElemWidget
 *
 * @package CBXWPBookmark_ElemWidget\Widgets
 */
class CBXWPBookmarkMyBookmark_ElemWidget extends \Elementor\Widget_Base {

	/**
	 * Retrieve my bookmarks widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'cbxwpbookmarks';
	}//end method get_name

	/**
	 * Retrieve my bookmarks widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'CBX My Bookmarked Posts', 'cbxwpbookmark' );
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
	 * Retrieve my bookmarks widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'cbxwpbookmars-post-list-icon';
	}//end method get_icon

	protected function register_controls() {
		$this->start_controls_section(
			'section_cbxwpbookmarks',
			[
				'label' => esc_html__( 'CBX My Bookmarks Settings', 'cbxwpbookmark' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'cbxwpbookmark' ),
				'description' => esc_html__( 'Keep empty to hide', 'cbxwpbookmark' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'All Bookmarks', 'cbxwpbookmark' ),
			]
		);

		$this->add_control(
			'order',
			[
				'label'       => esc_html__( 'Display order', 'cbxwpbookmark' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'DESC',
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
				'default'     => 'id',
				'placeholder' => esc_html__( 'Select order by', 'cbxwpbookmark' ),
				'options'     => [
					'id'          => esc_html__( 'Bookmark id', 'cbxwpbookmark' ),
					'object_id'   => esc_html__( 'Post ID', 'cbxwpbookmark' ),
					'object_type' => esc_html__( 'Post Type', 'cbxwpbookmark' ),
					'title'       => esc_html__( 'Post Title', 'cbxwpbookmark' ),
				]
			]
		);

		$this->add_control(
			'limit',
			[
				'label'   => esc_html__( 'Limit', 'cbxwpbookmark' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'min'     => 1,
				'step'    => 1
			]
		);

		$object_types = \CBXWPBookmarkHelper::object_types( true );

		$this->add_control(
			'type',
			[
				'label'       => esc_html__( 'Post type(s)', 'cbxwpbookmark' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'default'     => [],
				'placeholder' => esc_html__( 'Select post type(s)', 'cbxwpbookmark' ),
				'options'     => $object_types,
				'multiple'    => true,
				'label_block' => true

			]
		);

		$this->add_control(
			'loadmore',
			[
				'label'        => esc_html__( 'Show load more', 'cbxwpbookmark' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cbxwpbookmark' ),
				'label_off'    => esc_html__( 'No', 'cbxwpbookmark' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'catid',
			[
				'label'   => esc_html__( 'Category ID', 'cbxwpbookmark' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => ''
			]
		);

		$this->add_control(
			'cattitle',
			[
				'label'        => esc_html__( 'Show category title', 'cbxwpbookmark' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cbxwpbookmark' ),
				'label_off'    => esc_html__( 'No', 'cbxwpbookmark' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);


		$this->add_control(
			'catcount',
			[
				'label'        => esc_html__( 'Show category count', 'cbxwpbookmark' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cbxwpbookmark' ),
				'label_off'    => esc_html__( 'No', 'cbxwpbookmark' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'allowdelete',
			[
				'label'        => esc_html__( 'Allow Delete', 'cbxwpbookmark' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cbxwpbookmark' ),
				'label_off'    => esc_html__( 'No', 'cbxwpbookmark' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);


		$this->add_control(
			'allowdeleteall',
			[
				'label'        => esc_html__( 'Allow Delete All', 'cbxwpbookmark' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cbxwpbookmark' ),
				'label_off'    => esc_html__( 'No', 'cbxwpbookmark' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'showshareurl',
			[
				'label'        => esc_html__( 'Show Share url', 'cbxwpbookmark' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'cbxwpbookmark' ),
				'label_off'    => esc_html__( 'No', 'cbxwpbookmark' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
	 * Render my bookmarks widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		$attr = [];

		$type = $settings['type'];
		if ( is_array( $type ) ) {
			$type = array_filter( $type );
			$type = implode( ',', $type );
		} else {
			$type = '';
		}


		$attr['title']          = esc_attr( $settings['title'] );
		$attr['order']          = esc_attr( $settings['order'] );
		$attr['orderby']        = esc_attr( $settings['orderby'] );
		$attr['limit']          = absint( $settings['limit'] );
		$attr['type']           = esc_attr( $type );
		$attr['catid']          = $settings['catid'];
		$attr['loadmore']       = $this->yes_no_to_1_0( $settings['loadmore'] );
		$attr['cattitle']       = $this->yes_no_to_1_0( $settings['cattitle'] );
		$attr['catcount']       = $this->yes_no_to_1_0( $settings['catcount'] );
		$attr['allowdelete']    = $this->yes_no_to_1_0( $settings['allowdelete'] );
		$attr['allowdeleteall'] = $this->yes_no_to_1_0( $settings['allowdeleteall'] );
		$attr['showshareurl']   = $this->yes_no_to_1_0( $settings['showshareurl'] );
		$attr['base_url']       = esc_attr( $settings['base_url'] );

		$attr = apply_filters( 'cbxwpbookmark_elementor_shortcode_builder_attr', $attr, $settings, 'cbxwpbookmark' );

		$attr_html = '';

		foreach ( $attr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
		}

		echo do_shortcode( '[cbxwpbookmark ' . $attr_html . ']' );
	}//end method render
}//end method CBXWPBookmarks_ElemWidget
