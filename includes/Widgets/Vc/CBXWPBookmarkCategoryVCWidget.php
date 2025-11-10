<?php
namespace CBXWPBookmark\Widgets\Vc;

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * My Bookmark Category widget for vc
 *
 * Class CBXWPBookmarkCategoryVCWidget
 */
class CBXWPBookmarkCategoryVCWidget extends \WPBakeryShortCode {
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
			"name"        => esc_html__( "CBX Bookmark Categories", 'cbxwpbookmark' ),
			"description" => esc_html__( "This widget shows bookmark categories from a logged in user.", 'cbxwpbookmark' ),
			"base"        => "cbxwpbookmark-mycat",
			"icon"        => CBXWPBOOKMARK_ROOT_URL . 'assets/img/widget_icons/icon_category.png',
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
					"std"         => esc_html__( 'Bookmark Categories', 'cbxwpbookmark' ),
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
						esc_html__( 'Category Name', 'cbxwpbookmark' ) => 'cat_name',
						esc_html__( 'Category Id', 'cbxwpbookmark' )   => 'id',
						esc_html__( 'Privacy', 'cbxwpbookmark' )       => 'privacy',
					],
					'std'         => 'cat_name',
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Privacy", 'cbxwpbookmark' ),
					"param_name"  => "privacy",
					'value'       => [
						esc_html__( 'Ignore privacy', 'cbxwpbookmark' ) => 2,
						esc_html__( 'Public', 'cbxwpbookmark' )         => 1,
						esc_html__( 'Private', 'cbxwpbookmark' )        => 0,
					],
					'std'         => 2,
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Display method", 'cbxwpbookmark' ),
					"param_name"  => "display",
					'value'       => [
						esc_html__( 'List', 'cbxwpbookmark' )     => 0,
						esc_html__( 'Dropdown', 'cbxwpbookmark' ) => 1
					],
					'std'         => 0,
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( 'Show count', 'cbxwpbookmark' ),
					"param_name"  => "show_count",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0
					],
					'std'         => 0,
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Allow Edit", 'cbxwpbookmark' ),
					"param_name"  => "allowedit",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0,
					],
					'std'         => 0,
				],
				[
					"type"        => "textfield",
					"holder"      => "div",
					"class"       => "",
					'admin_label' => false,
					"heading"     => esc_html__( "My Bookmark Page Url(Base Url)", 'cbxwpbookmark' ),
					"param_name"  => "base_url",
					"std"         => cbxwpbookmarks_mybookmark_page_url(),
				],
			]
		] );
	}//end bakery_shortcode_mapping
}//end class CBXWPBookmarkCategoryVCWidget