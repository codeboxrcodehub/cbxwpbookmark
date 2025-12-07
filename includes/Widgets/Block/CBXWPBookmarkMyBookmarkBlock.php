<?php
namespace CBXWPBookmark\Widgets\Block;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



/**
 * CBX Bookmarks - My Bookmark Block Widget
 */
class CBXWPBookmarkMyBookmarkBlock {
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

		global $wp_version;

		$this->init_post_block();

	}//end of construct

	/**
	 * Register bookmark posts block
	 */
	public function init_post_block() {
		$version      = $this->version;
		$css_url_part = CBXWPBOOKMARK_ROOT_URL . 'assets/css/';
		$js_url_part  = CBXWPBOOKMARK_ROOT_URL . 'assets/js/';

		$css_url_path = CBXWPBOOKMARK_ROOT_PATH . 'assets/css/';
		$js_url_path  = CBXWPBOOKMARK_ROOT_PATH . 'assets/js/';

		$order_options = [];

		$order_options[] = [
			'label' => esc_html__( 'Descending Order', 'cbxwpbookmark' ),
			'value' => 'DESC',
		];

		$order_options[] = [
			'label' => esc_html__( 'Ascending Order', 'cbxwpbookmark' ),
			'value' => 'ASC',
		];

		$orderby_options   = [];
		$orderby_options[] = [
			'label' => esc_html__( 'Post Type', 'cbxwpbookmark' ),
			'value' => 'object_type',
		];

		$orderby_options[] = [
			'label' => esc_html__( 'Post ID', 'cbxwpbookmark' ),
			'value' => 'object_id',
		];

		$orderby_options[] = [
			'label' => esc_html__( 'Bookmark ID', 'cbxwpbookmark' ),
			'value' => 'id',
		];

		$orderby_options[] = [
			'label' => esc_html__( 'Post Title', 'cbxwpbookmark' ),
			'value' => 'title',
		];

		$type_options   = [];
		$post_types     = \CBXWPBookmarkHelper::post_types_plain();
		$type_options[] = [
			'label' => esc_html__( 'Select Post Type', 'cbxwpbookmark' ),
			'value' => '',
		];

		foreach ( $post_types as $type_slug => $type_name ) {
			$type_options[] = [
				'label' => $type_name,
				'value' => $type_slug,
			];
		}


		// phpcs:disable
		wp_register_style( 'cbxwpbookmark-block', $css_url_part . 'cbxwpbookmark-block.css', [], filemtime( $css_url_path . 'cbxwpbookmark-block.css' ) );

		wp_register_script( 'cbxwpbookmark-post-block', $js_url_part . 'blocks/cbxwpbookmark-post-block.js',
			[
				'wp-blocks',
				'wp-element',
				'wp-components',
				'wp-editor',
				//'jquery',
				//'codeboxrflexiblecountdown-public'
			],
			filemtime( $js_url_path . 'blocks/cbxwpbookmark-post-block.js' ) );

		// phpcs:enable

		$js_vars = apply_filters( 'cbxwpbookmark_post_block_js_vars',
			[
				'block_title'      => esc_html__( 'CBX My Bookmarked Posts', 'cbxwpbookmark' ),
				'block_category'   => 'cbxwpbookmark',
				'block_icon'       => 'universal-access-alt',
				'general_settings' => [
					'heading'         => esc_html__( 'Block Settings', 'cbxwpbookmark' ),
					'title'           => esc_html__( 'Title', 'cbxwpbookmark' ),
					'title_desc'      => esc_html__( 'Leave empty to hide', 'cbxwpbookmark' ),
					'order'           => esc_html__( 'Order', 'cbxwpbookmark' ),
					'order_options'   => $order_options,
					'orderby'         => esc_html__( 'Order By', 'cbxwpbookmark' ),
					'orderby_options' => $orderby_options,
					'type'            => esc_html__( 'Post Type(s)', 'cbxwpbookmark' ),
					'type_options'    => $type_options,
					'limit'           => esc_html__( 'Number of Posts', 'cbxwpbookmark' ),
					'loadmore'        => esc_html__( 'Show Load More', 'cbxwpbookmark' ),
					'catid'           => esc_html__( 'Categories(Comma Separated)', 'cbxwpbookmark' ),
					'catid_note'      => esc_html__( 'This is practically useful if category mode = global category', 'cbxwpbookmark' ),
					'cattitle'        => esc_html__( 'Show Category Title', 'cbxwpbookmark' ),
					'catcount'        => esc_html__( 'Show Category Count', 'cbxwpbookmark' ),
					'allowdelete'     => esc_html__( 'Allow Delete', 'cbxwpbookmark' ),
					'allowdeleteall'  => esc_html__( 'Allow Delete All', 'cbxwpbookmark' ),
					'showshareurl'    => esc_html__( 'Display Share Url', 'cbxwpbookmark' ),
					'base_url'        => esc_html__( 'My Bookmark Page Url(Base Url)', 'cbxwpbookmark' ),
				],
			] );

		wp_localize_script( 'cbxwpbookmark-post-block', 'cbxwpbookmark_post_block', $js_vars );

		register_block_type( 'codeboxr/cbxwpbookmark-post-block',
			[
				'editor_script'   => 'cbxwpbookmark-post-block',
				'editor_style'    => 'cbxwpbookmark-block',
				'attributes'      => apply_filters( 'cbxwpbookmark_post_block_attributes',
					[
						'title'          => [
							'type'    => 'string',
							'default' => esc_html__( 'All Bookmarks', 'cbxwpbookmark' ),
						],
						'order'          => [
							'type'    => 'string',
							'default' => 'DESC',
						],
						'orderby'        => [
							'type'    => 'string',
							'default' => 'id',
						],
						'type'           => [
							'type'    => 'array',
							'default' => [],
							'items'   => [
								'type' => 'string',
							],
						],
						'catid'          => [
							'type'    => 'string',
							'default' => '',
						],
						'limit'          => [
							'type'    => 'integer',
							'default' => 10,
						],
						'loadmore'       => [
							'type'    => 'boolean',
							'default' => true,
						],
						'cattitle'       => [
							'type'    => 'boolean',
							'default' => true,
						],
						'catcount'       => [
							'type'    => 'boolean',
							'default' => true,
						],
						'allowdelete'    => [
							'type'    => 'boolean',
							'default' => false,
						],
						'allowdeleteall' => [
							'type'    => 'boolean',
							'default' => false,
						],
						'showshareurl'   => [
							'type'    => 'boolean',
							'default' => true,
						],
						'base_url'       => [
							'type'    => 'string',
							'default' => cbxwpbookmarks_mybookmark_page_url(),
						],

					] ),
				'render_callback' => [ $this, 'post_block_render' ],
			] );
	}//end init_post_block

	/**
	 * Getenberg server side render for my bookmark post block
	 *
	 * @param $attr
	 *
	 * @return string
	 */
	public function post_block_render( $attr ) {
		$arr = [];

		$arr['title']    = isset( $attr['title'] ) ? esc_attr( $attr['title'] ) : '';
		$arr['base_url'] = isset( $attr['base_url'] ) ? esc_url( $attr['base_url'] ) : cbxwpbookmarks_mybookmark_page_url();
		$arr['order']    = isset( $attr['order'] ) ? strtoupper( esc_attr( $attr['order'] ) ) : 'DESC';
		$arr['orderby']  = isset( $attr['orderby'] ) ? esc_attr( $attr['orderby'] ) : 'id';
		$arr['limit']    = isset( $attr['limit'] ) ? intval( $attr['limit'] ) : 10;


		$type        = isset( $attr['type'] ) ? wp_unslash( $attr['type'] ) : [];
		$type        = array_filter( $type );
		$arr['type'] = implode( ',', $type );

		$attr['catid'] = isset( $attr['catid'] ) ? wp_unslash( $attr['catid'] ) : '';


		$arr['loadmore'] = isset( $attr['loadmore'] ) ? $attr['loadmore'] : 'true';
		$arr['loadmore'] = ( $arr['loadmore'] == 'true' ) ? 1 : 0;


		$arr['cattitle'] = isset( $attr['cattitle'] ) ? $attr['cattitle'] : 'true';
		$arr['cattitle'] = ( $arr['cattitle'] == 'true' ) ? 1 : 0;

		$arr['catcount'] = isset( $attr['catcount'] ) ? $attr['catcount'] : 'true';
		$arr['catcount'] = ( $arr['catcount'] == 'true' ) ? 1 : 0;

		$arr['allowdelete'] = isset( $attr['allowdelete'] ) ? $attr['allowdelete'] : 'false';
		$arr['allowdelete'] = ( $arr['allowdelete'] == 'true' ) ? 1 : 0;

		$arr['allowdeleteall'] = isset( $attr['allowdeleteall'] ) ? $attr['allowdeleteall'] : 'false';
		$arr['allowdeleteall'] = ( $arr['allowdeleteall'] == 'true' ) ? 1 : 0;

		$arr['showshareurl'] = isset( $attr['showshareurl'] ) ? $attr['showshareurl'] : 'true';
		$arr['showshareurl'] = ( $arr['showshareurl'] == 'true' ) ? 1 : 0;

		//take care some fields
		$order    = $attr['order'];
		$order_by = $attr['orderby'];

		$order_keys = cbxwpbookmarks_get_order_keys();
		if ( ! in_array( $order, $order_keys ) ) {
			$order = 'DESC';
		}
		$attr['order'] = $order;

		$bookmark_sortable_keys = cbxwpbookmarks_bookmark_sortable_keys();
		if ( ! in_array( $order_by, $bookmark_sortable_keys ) ) {
			$order_by = 'id';
		}
		$attr['orderby'] = $order_by;

		$attr_html = '';
		foreach ( $arr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
		}

		return do_shortcode( '[cbxwpbookmark ' . $attr_html . ']' );
	}//end post_block_render
}//end method CBXWPBookmarkMyBookmarkBlock