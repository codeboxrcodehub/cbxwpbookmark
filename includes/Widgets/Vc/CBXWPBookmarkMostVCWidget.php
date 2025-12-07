<?php
namespace CBXWPBookmark\Widgets\Vc;

// Prevent direct file access


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Most Bookmarked posts widget for vc
 *
 * Class CBXWPBookmarkmost_VCWidget
 */
class CBXWPBookmarkMostVCWidget extends \WPBakeryShortCode {
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
			"name"        => esc_html__( "CBX Most Bookmarked Posts", 'cbxwpbookmark' ),
			"description" => esc_html__( "This widget shows most bookmarked post from all user within specific time limit.", 'cbxwpbookmark' ),
			"base"        => "cbxwpbookmark-most",
			"icon"        => CBXWPBOOKMARK_ROOT_URL . 'assets/img/widget_icons/icon_most_list.png',
			"category"    => esc_html__( 'CBX Bookmark Widgets', 'cbxwpbookmark' ),
			"params"      => [
				[
					"type"        => "textfield",
					"holder"      => "div",
					"class"       => "",
					'admin_label' => false,
					"heading"     => esc_html__( "Title", 'cbxwpbookmark' ),
					'description' => esc_html__( 'Leave empty to ignore', 'cbxwpbookmark' ),
					"param_name"  => "title",
					"std"         => esc_html__( 'Most Bookmarked Posts', 'cbxwpbookmark' ),
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Display order", 'cbxwpbookmark' ),
					"param_name"  => "order",
					'value'       => [
						esc_html__( 'Ascending', 'cbxwpbookmark' )  => 'ASC',
						esc_html__( 'Descending', 'cbxwpbookmark' ) => 'DESC',
					],
					'std'         => 'DESC',
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Display order by", 'cbxwpbookmark' ),
					"param_name"  => "orderby",
					'value'       => [
						esc_html__( 'Bookmark Count', 'cbxwpbookmark' ) => 'object_count',
						esc_html__( 'Bookmark id', 'cbxwpbookmark' )    => 'id',
						esc_html__( 'Post ID', 'cbxwpbookmark' )        => 'object_id',
						esc_html__( 'Post Type', 'cbxwpbookmark' )      => 'object_type',
						esc_html__( 'Post Title', 'cbxwpbookmark' )     => 'title',
					],
					'std'         => 'object_count',
				],
				[
					"type"        => "textfield",
					"holder"      => "div",
					"class"       => "",
					'admin_label' => false,
					"heading"     => esc_html__( "Limit", 'cbxwpbookmark' ),
					'description' => esc_html__( 'Need numeric value.', 'cbxwpbookmark' ),
					"param_name"  => "limit",
					"std"         => 10
				],
				[
					'type'        => 'cbxwpbookmarkdownmulti',
					"class"       => "",
					'admin_label' => false, //it must be false
					'heading'     => esc_html__( 'Post type(s)', 'cbxwpbookmark' ),
					'param_name'  => 'type',
					'value'       => \CBXWPBookmarkHelper::post_types_plain_r(),
					'std'         => [],
				],
				[
					"type"        => "textfield",
					"holder"      => "div",
					"class"       => "",
					'admin_label' => false,
					"heading"     => esc_html__( "Day(s)", 'cbxwpbookmark' ),
					'description' => esc_html__( '0 means all time, need numeric value.', 'cbxwpbookmark' ),
					"param_name"  => "daytime",
					"std"         => 0
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Show count", 'cbxwpbookmark' ),
					"param_name"  => "show_count",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0,
					],
					'std'         => 1,
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Show thumb", 'cbxwpbookmark' ),
					"param_name"  => "show_thumb",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0,
					],
					'std'         => 1,
				]
			]
		] );
	}//end bakery_shortcode_mapping
}//end class CBXWPBookmarkMostVCWidget