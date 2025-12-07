<?php
namespace CBXWPBookmark\Widgets\Block;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



/**
 * CBX Bookmark - Most Bookmarked Post Block Widget
 */
class CBXWPBookmarkMostBlock {
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

		$this->init_most_block();

	}//end of construct

	/**
	 * Register most bookmarked posts block
	 */
	public function init_most_block() {
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
			'label' => esc_html__( 'Bookmark Count', 'cbxwpbookmark' ),
			'value' => 'object_count',
		];
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

		$daytime_options   = [];
		$daytime_options[] = [
			'label' => esc_html__( '-- All Time --', 'cbxwpbookmark' ),
			'value' => 0
		];

		$daytime_options[] = [
			'label' => esc_html__( '1 Day', 'cbxwpbookmark' ),
			'value' => 1
		];

		$daytime_options[] = [
			'label' => esc_html__( '7 Days', 'cbxwpbookmark' ),
			'value' => 7
		];

		$daytime_options[] = [
			'label' => esc_html__( '30 Days', 'cbxwpbookmark' ),
			'value' => 30
		];

		$daytime_options[] = [
			'label' => esc_html__( '6 Months', 'cbxwpbookmark' ),
			'value' => 180
		];

		$daytime_options[] = [
			'label' => esc_html__( '1 Year', 'cbxwpbookmark' ),
			'value' => 365
		];


		// phpcs:disable
		wp_register_style( 'cbxwpbookmark-block', $css_url_part . 'cbxwpbookmark-block.css', [], filemtime( $css_url_path . 'cbxwpbookmark-block.css' ) ); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
		wp_register_script( 'cbxwpbookmark-most-block',
			$js_url_part . 'blocks/cbxwpbookmark-most-block.js',
			[
				'wp-blocks',
				'wp-element',
				'wp-components',
				'wp-editor',
			],
			filemtime( $js_url_path . 'blocks/cbxwpbookmark-most-block.js' ) );
		// phpcs:enable

		$js_vars = apply_filters( 'cbxwpbookmark_most_block_js_vars',
			[
				//'cbxbookmark_lang'        => get_user_locale(),
				'block_title'      => esc_html__( 'CBX Most Bookmarked Posts', 'cbxwpbookmark' ),
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
					'daytime'         => esc_html__( 'Duration', 'cbxwpbookmark' ),
					'daytime_options' => $daytime_options,
					'show_count'      => esc_html__( 'Show Count', 'cbxwpbookmark' ),
					'show_thumb'      => esc_html__( 'Show Thumbnail', 'cbxwpbookmark' ),
				],
			] );

		wp_localize_script( 'cbxwpbookmark-most-block', 'cbxwpbookmark_most_block', $js_vars );

		register_block_type( 'codeboxr/cbxwpbookmark-most-block',
			[
				'editor_script'   => 'cbxwpbookmark-most-block',
				'editor_style'    => 'cbxwpbookmark-block',
				'attributes'      => apply_filters( 'cbxwpbookmark_most_block_attributes',
					[
						'title'      => [
							'type'    => 'string',
							'default' => esc_html__( 'Most Bookmarked Posts', 'cbxwpbookmark' ),
						],
						'order'      => [
							'type'    => 'string',
							'default' => 'DESC',
						],
						'orderby'    => [
							'type'    => 'string',
							'default' => 'object_count',
						],
						'type'       => [
							'type'    => 'array',
							'default' => [],
							'items'   => [
								'type' => 'string',
							],
						],
						'limit'      => [
							'type'    => 'integer',
							'default' => 10,
						],
						'daytime'    => [
							'type'    => 'integer',
							'default' => 0,
						],
						'show_count' => [
							'type'    => 'boolean',
							'default' => true,
						],
						'show_thumb' => [
							'type'    => 'boolean',
							'default' => true,
						]
					] ),
				'render_callback' => [ $this, 'most_block_render' ],
			] );
	}//end init_most_block

	/**
	 * Getenberg server side render for most bookmarked post block
	 *
	 * @param $attr
	 *
	 * @return string
	 */
	public function most_block_render( $attr ) {
		$arr = [];

		$arr['title']   = isset( $attr['title'] ) ? esc_attr( $attr['title'] ) : '';
		$arr['order']   = isset( $attr['order'] ) ? strtoupper( esc_attr( $attr['order'] ) ) : 'DESC';
		$arr['orderby'] = isset( $attr['orderby'] ) ? esc_attr( $attr['orderby'] ) : 'object_count';
		$arr['limit']   = isset( $attr['limit'] ) ? absint( $attr['limit'] ) : 10;


		$type        = isset( $attr['type'] ) ? wp_unslash( $attr['type'] ) : [];
		$type        = array_filter( $type );
		$arr['type'] = implode( ',', $type );

		$attr['daytime'] = isset( $attr['daytime'] ) ? absint( $attr['daytime'] ) : 0;


		$arr['show_count'] = isset( $attr['show_count'] ) ? $attr['show_count'] : 'true';
		$arr['show_count'] = ( $arr['show_count'] == 'true' ) ? 1 : 0;

		$arr['show_thumb'] = isset( $attr['show_thumb'] ) ? $attr['show_thumb'] : 'false';
		$arr['show_thumb'] = ( $arr['show_thumb'] == 'true' ) ? 1 : 0;

		//take care some fields
		$order    = $attr['order'];
		$order_by = $attr['orderby'];

		$order_keys = cbxwpbookmarks_get_order_keys();
		if ( ! in_array( $order, $order_keys ) ) {
			$order = 'DESC';
		}
		$attr['order'] = $order;

		$most_sortable_keys = cbxwpbookmarks_bookmark_most_sortable_keys();
		if ( ! in_array( $order_by, $most_sortable_keys ) ) {
			$order_by = 'object_count';
		}
		$attr['orderby'] = $order_by;

		$attr_html = '';
		foreach ( $arr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
		}

		return do_shortcode( '[cbxwpbookmark-most ' . $attr_html . ']' );
	}//end most_block_render
}//end class CBXWPBookmarkMostBlock