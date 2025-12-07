<?php
namespace CBXWPBookmark\Widgets\Block;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



/**
 * CBX Bookmark - Bookmark button Block Widget
 */
class CBXWPBookmarkBtnBlock {
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

		$this->init_btn_block();
	}//end of construct

	/**
	 * Register bookmark button block
	 */
	public function init_btn_block() {
		$version      = $this->version;
		$css_url_part = CBXWPBOOKMARK_ROOT_URL . 'assets/css/';
		$js_url_part  = CBXWPBOOKMARK_ROOT_URL . 'assets/js/';

		$css_url_path = CBXWPBOOKMARK_ROOT_PATH . 'assets/css/';
		$js_url_path  = CBXWPBOOKMARK_ROOT_PATH . 'assets/js/';

		// phpcs:disable
		wp_register_style( 'cbxwpbookmark-block', $css_url_part . 'cbxwpbookmark-block.css', [], filemtime( $css_url_path . 'cbxwpbookmark-block.css' ) );
		wp_register_script( 'cbxwpbookmark-btn-block',
			$js_url_part . 'blocks/cbxwpbookmark-btn-block.js',
			[
				'wp-blocks',
				'wp-element',
				'wp-components',
				'wp-editor'
			],
			filemtime( $js_url_path . 'blocks/cbxwpbookmark-btn-block.js' ) );
		// phpcs:enable

		$js_vars = apply_filters( 'cbxwpbookmark_btn_block_js_vars',
			[
				//'cbxbookmark_lang'        => get_user_locale(),
				'block_title'      => esc_html__( 'CBX Bookmark Button', 'cbxwpbookmark' ),
				'block_category'   => 'cbxwpbookmark',
				'block_icon'       => 'universal-access-alt',
				'general_settings' => [
					'title'      => esc_html__( 'Block Settings', 'cbxwpbookmark' ),
					'show_count' => esc_html__( 'Show Count', 'cbxwpbookmark' ),
				],
			] );

		wp_localize_script( 'cbxwpbookmark-btn-block', 'cbxwpbookmark_btn_block', $js_vars );

		register_block_type( 'codeboxr/cbxwpbookmark-btn-block',
			[
				'editor_script'   => 'cbxwpbookmark-btn-block',
				'editor_style'    => 'cbxwpbookmark-block',
				'attributes'      => apply_filters( 'cbxwpbookmark_btn_block_attributes',
					[
						//general
						'show_count' => [
							'type'    => 'boolean',
							'default' => true,
						],

					] ),
				'render_callback' => [ $this, 'btn_block_render' ],
			] );
	}//end init_btn_block

	/**
	 * Getenberg server side render
	 *
	 * @param $attr
	 *
	 * @return string
	 */
	public function btn_block_render( $attr ) {
		$arr['show_count'] = isset( $attr['show_count'] ) ? $attr['show_count'] : 'true';
		$arr['show_count'] = ( $arr['show_count'] == 'true' ) ? 1 : 0;

		$attr_html = '';
		foreach ( $arr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
		}

		return '[cbxwpbookmarkbtn ' . $attr_html . ']';
	}//end init_cbxwpbookmark_post_block
}//end class CBXWPBookmarkBtnBlock