<?php
// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * My Bookmarked posts widget for vc
 *
 * Class CBXWPBookmarks_VCWidget
 */
class CBXWPBookmarkMyBookmark_VCWidget extends WPBakeryShortCode {
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
		//icon path: https://www.flaticon.com/free-icon/list_151917

		// Map the block with vc_map()
		vc_map( [
			"name"        => esc_html__( "CBX My Bookmarks", 'cbxwpbookmark' ),
			"description" => esc_html__( "This widget shows bookmarked posts from a user", 'cbxwpbookmark' ),
			"base"        => "cbxwpbookmark",
			"icon"        => CBXWPBOOKMARK_ROOT_URL . 'assets/img/widget_icons/icon_post_list.png',
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
					"std"         => esc_html__( 'All Bookmarks', 'cbxwpbookmark' ),
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
						esc_html__( 'Bookmark id', 'cbxwpbookmark' ) => 'id',
						esc_html__( 'Post ID', 'cbxwpbookmark' )     => 'object_id',
						esc_html__( 'Post Type', 'cbxwpbookmark' )   => 'object_type',
						esc_html__( 'Post Title', 'cbxwpbookmark' )  => 'title',
					],
					'std'         => 'id',
				],
				[
					"type"        => "textfield",
					"holder"      => "div",
					"class"       => "",
					'admin_label' => true,
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
					'value'       => CBXWPBookmarkHelper::post_types_plain_r(),
					'std'         => [],
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Show load more", 'cbxwpbookmark' ),
					"param_name"  => "loadmore",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0,
					],
					'std'         => 1,
				],
				[
					"type"        => "textfield",
					"holder"      => "div",
					"class"       => "",
					'admin_label' => false,
					"heading"     => esc_html__( "Category ID", 'cbxwpbookmark' ),
					"param_name"  => "catid",
					"std"         => '',
				],
				[
					"type"        => "dropdown",
					'admin_label' => false,
					"heading"     => esc_html__( "Show category title", 'cbxwpbookmark' ),
					"param_name"  => "cattitle",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0
					],
					'std'         => 1,
				],
				[
					"type"        => "dropdown",
					'admin_label' => false,
					"heading"     => esc_html__( "Show category count", 'cbxwpbookmark' ),
					"param_name"  => "catcount",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0
					],
					'std'         => 1,
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Allow Delete", 'cbxwpbookmark' ),
					"param_name"  => "allowdelete",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0,
					],
					'std'         => 0,
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Allow Delete All", 'cbxwpbookmark' ),
					"param_name"  => "allowdeleteall",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0,
					],
					'std'         => 0,
				],
				[
					"type"        => "dropdown",
					'admin_label' => true,
					"heading"     => esc_html__( "Show Share url", 'cbxwpbookmark' ),
					"param_name"  => "showshareurl",
					'value'       => [
						esc_html__( 'Yes', 'cbxwpbookmark' ) => 1,
						esc_html__( 'No', 'cbxwpbookmark' )  => 0,
					],
					'std'         => 1,
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
}//end class CBXWPBookmarkMyBookmark_VCWidget