<?php
namespace CBXWPBookmark;

use CBXWPBookmark\Widgets\Block\CBXWPBookmarkBtnBlock;
use CBXWPBookmark\Widgets\Block\CBXWPBookmarkCategoryBlock;
use CBXWPBookmark\Widgets\Block\CBXWPBookmarkMostBlock;
use CBXWPBookmark\Widgets\Block\CBXWPBookmarkMyBookmarkBlock;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The customizer specific functionality of the plugin.
 *
 * @link       codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxwpbookmark
 * @subpackage Cbxwpbookmark/includes
 */


/**
 * This class handles the gutenberg blocks
 *
 * Class CBXWPBookmarkBlocks
 */
class CBXWPBookmarkBlocks {
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


		if ( version_compare( $wp_version, '5.8' ) >= 0 ) {
			add_filter( 'block_categories_all', [ $this, 'gutenberg_block_categories' ], 10, 2 );
		} else {
			add_filter( 'block_categories', [ $this, 'gutenberg_block_categories' ], 10, 2 );
		}

		add_action( 'init', [ $this, 'gutenberg_blocks' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );//Hook: Editor assets.
	}

	/**
	 * Register New Gutenberg block Category if need
	 *
	 * @param $categories
	 * @param $post
	 *
	 * @return mixed
	 */
	public function gutenberg_block_categories( $categories, $post ) {
		$found = false;
		foreach ( $categories as $category ) {
			if ( $category['slug'] == 'cbxwpbookmark' ) {
				$found = true;
				break;
			}
		}

		if ( ! $found ) {
			return array_merge(
				$categories,
				[
					[
						'slug'  => 'cbxwpbookmark',
						'title' => esc_html__( 'CBX Bookmark Blocks', 'cbxwpbookmark' ),
					],
				]
			);
		}

		return $categories;
	}//end gutenberg_block_categories

	/**
	 * Init all gutenberg blocks
	 */
	public function gutenberg_blocks() {
		if ( ! function_exists( 'register_block_type' ) ) {
			// Gutenberg is not active.
			return;
		}


		$plugin_name = $this->plugin_name;
		$version     = $this->version;


		new CBXWPBookmarkBtnBlock( $plugin_name, $version );

		new CBXWPBookmarkMyBookmarkBlock( $plugin_name, $version );

		new CBXWPBookmarkMostBlock( $plugin_name, $version );

		new CBXWPBookmarkCategoryBlock( $plugin_name, $version );
	}//end gutenberg_blocks


	/**
	 * Enqueue style for block editor
	 */
	public function enqueue_block_editor_assets() {
		do_action( 'cbxwpbookmark_css_start' );

		wp_register_style( 'cbxwpbookmarkpublic-css', CBXWPBOOKMARK_ROOT_URL . 'assets/css/cbxwpbookmark-public.css', [], $this->version, 'all' );
		wp_enqueue_style( 'cbxwpbookmarkpublic-css' );

		do_action( 'cbxwpbookmark_css_end' );
	}//end enqueue_block_editor_assets
}//end class CBXWPBookmarkBlocks