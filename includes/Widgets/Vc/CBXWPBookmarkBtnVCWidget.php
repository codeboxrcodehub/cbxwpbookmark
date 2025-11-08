<?php
namespace Cbx\Bookmark\Widgets\Vc;

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bookmark button widget for vc
 *
 * Class CBXWPBookmarkbtn_VCWidget
 */
class CBXWPBookmarkBtnVCWidget extends \WPBakeryShortCode {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'bakery_shortcode_mapping' ], 12 );
	}//end of constructor


	/**
	 * Element Mapping
	 */
	public function bakery_shortcode_mapping() {
		// Map the block with vc_map()
		vc_map( [
			"name"        => esc_html__( "CBX Bookmark Button", 'cbxwpbookmark' ),
			"description" => esc_html__( "Bookmark button of CBX Bookmark", 'cbxwpbookmark' ),
			"base"        => "cbxwpbookmarkbtn",
			"icon"        => CBXWPBOOKMARK_ROOT_URL . 'assets/img/widget_icons/icon_btn.png',
			"category"    => esc_html__( 'CBX Bookmark Widgets', 'cbxwpbookmark' ),
			"params"      => [
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Show Count", 'cbxwpbookmark' ),
					"param_name"  => "show_count",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0,
					],
					'std'         => 1,
				]
			]
		] );
	}//end bakery_shortcode_mapping
}//end class CBXWPBookmarkBtnVCWidget