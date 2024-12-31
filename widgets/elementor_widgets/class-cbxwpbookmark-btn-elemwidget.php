<?php

namespace CBXWPBookmark_ElemWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CBX Bookmark - Bookmark button Elementor Widget
 *
 * Class CBXWPBookmarkBtn_ElemWidget
 *
 * @package CBXWPBookmark_ElemWidget\Widgets
 */
class CBXWPBookmarkBtn_ElemWidget extends \Elementor\Widget_Base {

	/**
	 * Retrieve bookmark button widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'cbxwpbookmarkbtn';
	}//end method get_name

	/**
	 * Retrieve bookmark button widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'CBX Bookmark Button', 'cbxwpbookmark' );
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
	 * Retrieve bookmark button widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'cbxwpbookmars-btn-icon';
	}//end method get_icon

	protected function register_controls() {
		$this->start_controls_section(
			'section_cbxwpbookmarkbtn',
			[
				'label' => esc_html__( 'CBX Bookmark Button Settings', 'cbxwpbookmark' ),
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
				'default'      => 'yes',
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
		$attr     = [];

		$attr['show_count'] = $this->yes_no_to_1_0( $settings['show_count'] );


		$attr = apply_filters( 'cbxwpbookmark_elementor_shortcode_builder_attr', $attr, $settings, 'cbxwpbookmarkbtn' );

		$attr_html = '';

		foreach ( $attr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
		}

		echo do_shortcode( '[cbxwpbookmarkbtn ' . $attr_html . ']' );
	}//end method render
}//end method CBXWPBookmarkBtn_ElemWidget
