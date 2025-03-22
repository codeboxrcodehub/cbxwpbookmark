<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       codeboxr.com
 * @since      1.0.0
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CBXWPbookmark
 * @subpackage CBXWPbookmark/public
 * @author     CBX Team  <info@codeboxr.com>
 */
class CBXWPBookmark_Public {

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

	private $settings;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $plugin_name = '', $version = '' ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;


		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$this->version = current_time( 'timestamp' ); //for development time only
		}

		$this->settings = new CBXWPBookmark_Settings_API();
	}//end constructor

	/**
	 * Register Classic Widget
	 */
	public function init_widgets() {
		//category widget
		if ( ! class_exists( 'CBXWPBookmarkCategory_Widget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/classic_widgets/category-widget/cbxwpbookmark-category-widget.php';
		}
		register_widget( "CBXWPBookmarkCategory_Widget" );

		//my bookmark widget
		if ( ! class_exists( 'CBXWPBookmarkMyBookmark_Widget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/classic_widgets/mybookmark-widget/cbxwpbookmark-mybookmark-widget.php';
		}
		register_widget( "CBXWPBookmarkMyBookmark_Widget" );

		//Most bookmark widget
		if ( ! class_exists( 'CBXWPBookmarkMost_Widget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/classic_widgets/most-widget/cbxwpbookmark-most-widget.php';
		}

		register_widget( "CBXWPBookmarkMost_Widget" );
	}//end init_widgets

	/**
	 * Get all categories global or by user
	 *
	 * @return void
	 */
	public function find_category() {
		check_ajax_referer( 'cbxbookmarknonce', 'security' );

		global $wpdb;

		$settings      = $this->settings;
		$bookmark_mode = $settings->get_field( 'bookmark_mode', 'cbxwpbookmark_basics', 'user_cat' );


		$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';
		$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

		$user_id     = absint( get_current_user_id() ); //get the current logged in user id
		$object_id   = isset( $_POST['object_id'] ) ? absint( $_POST['object_id'] ) : 0;
		$object_type = isset( $_POST['object_type'] ) ? sanitize_text_field( wp_unslash( $_POST['object_type'] ) ) : 'post'; //post, page, user, product, any thing custom

		$cats_by_user_orderby = 'id';
		$cats_by_user_order   = 'DESC';

		$cats_by_user_orderby = apply_filters( 'cbxwpbookmark_cats_by_user_orderby', $cats_by_user_orderby );
		$cats_by_user_order   = apply_filters( 'cbxwpbookmark_cats_by_user_order', $cats_by_user_order );

		if ( $bookmark_mode == 'user_cat' ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$cats_by_user = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $category_table WHERE user_id = %d ORDER BY $cats_by_user_orderby $cats_by_user_order", $user_id ), ARRAY_A );
		} else {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$cats_by_user = $wpdb->get_results( "SELECT * FROM $category_table WHERE 1 ORDER BY $cats_by_user_orderby $cats_by_user_order", ARRAY_A );
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$post_in_cats_t = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT cat_id FROM $bookmark_table WHERE object_type = %s AND user_id = %d AND object_id = %d", [
			$object_type,
			$user_id,
			$object_id
		] ), ARRAY_A
		);

		//
		$post_in_cats = [];
		foreach ( $post_in_cats_t as $cat ) {
			$post_in_cats[] = $cat['cat_id'];
		}

		foreach ( $cats_by_user as &$row ) {
			if ( in_array( $row['id'], $post_in_cats ) ) {
				$row['incat'] = 1;
			} else {
				$row['incat'] = 0;
			}
		}


		$bookmark_total   = CBXWPBookmarkHelper::getTotalBookmark( $object_id );
		$bookmark_by_user = CBXWPBookmarkHelper::isBookmarkedByUser( $object_id );

		$message = [];
		//code 1 = category found
		//code 0 = category not found

		$cats_by_user = apply_filters( 'cbxwpbookmark_user_cats_found', $cats_by_user, $user_id, $object_id, $object_type );

		if ( $cats_by_user != null ) {

			$message['code'] = 1;

			$message['msg'] = esc_html__( 'Categories loaded', 'cbxwpbookmark' );
			if ( $cats_by_user !== false ) {
				$message['cats'] = wp_json_encode( $cats_by_user );
			}
		} else {
			$message['code'] = 0;
			$message['msg']  = esc_html__( 'Category not found, create one.', 'cbxwpbookmark' );
		}

		$message['bookmark_count']  = $bookmark_total;
		$message['bookmark_byuser'] = ( $bookmark_by_user ) ? 1 : 0;

		echo wp_json_encode( $message );

		wp_die();
	}//end find_category

	/**
	 * Auto integration for 'the_content'
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function the_content_auto_integration( $content ) {
		if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ) {
			return $content;
		}

		return $this->bookmark_auto_integration( $content );
	}//end  the_content_auto_integration

	/**
	 * Customizer method for my bookmark page
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function the_content_customizer_method( $content ) {
		if ( ! is_singular( 'page' ) ) {
			return $content;
		}

		$settings = $this->settings;

		$mybookmark_pageid = absint( $settings->get_field( 'mybookmark_pageid', 'cbxwpbookmark_basics', 0 ) );
		$mybookmark_way    = $settings->get_field( 'mybookmark_way', 'cbxwpbookmark_basics', 'shortcode' );


		global $post;
		$post_id = absint( $post->ID );

		if ( $post_id == $mybookmark_pageid && $mybookmark_way == 'customizer' ) {
			$content = CBXWPBookmarkHelper::strip_shortcode( 'cbxwpbookmark-mycat', $content );
			$content = CBXWPBookmarkHelper::strip_shortcode( 'cbxwpbookmark', $content );

			//$content = CBXWPBookmarkHelper::strip_shortcode( 'cbxwpbookmarkgrid', $content );

			$content = apply_filters( 'cbxwpbookmark_customizer_strip_shortcodes', $content );


			$customizer = CBXWPBookmarkHelper::customizer_default_adjust( false, true );


			if ( is_array( $customizer ) && sizeof( $customizer ) > 0 ) {
				$shortcodes = isset( $customizer['shortcodes'] ) ? $customizer['shortcodes'] : '';
				if ( $shortcodes != '' ) {
					$shortcodes = explode( ',', $shortcodes );
					if ( is_array( $shortcodes ) && sizeof( $shortcodes ) > 0 ) {
						foreach ( $shortcodes as $shortcode ) {
							if ( isset( $customizer[ $shortcode ] ) ) {

								$shortcode_params = $customizer[ $shortcode ];
								$attr_html        = '';
								foreach ( $shortcode_params as $shortcode_key => $shortcode_value ) {
									$attr_html .= ' ' . $shortcode_key . '="' . esc_attr( $shortcode_value ) . '" ';
								}

								$content .= do_shortcode( '[' . $shortcode . ' ' . $attr_html . ' ]' );
							}
						}
					}
				}//if any shortcode enabled
			}//if there is customizer values
		}//if customizer way


		return $content;
	}//end the_content_customizer_method

	/**
	 * Auto integration for 'the_excerpt'
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function the_excerpt_auto_integration( $content ) {
		return $this->bookmark_auto_integration( $content );
	}//end  the_excerpt_auto_integration


	/**
	 * Show Bookmark button before or after the content
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function bookmark_auto_integration( $content ) {
		//disable for woocommerce pages
		if ( function_exists( 'is_account_page' ) ) {
			if ( is_account_page() || is_cart() || is_checkout() || is_checkout_pay_page() ) {
				return $content;
			}
		}

		/**
		 * Restrict auto integrtion in admin
		 *
		 * Some 3rd party plugins implements the_content hooks in dashboard and this auto integration creates problem
		 *
		 * @since  1.6.12
		 */
		if ( is_admin() ) {
			return $content;
		}

		$settings = $this->settings;


		$mybookmark_pageid = absint( $settings->get_field( 'mybookmark_pageid', 'cbxwpbookmark_basics', 0 ) );


		$user_id = get_current_user_id();
		global $post;

		if ( ! isset( $post->ID ) ) {
			return $content;
		}

		$post_id = intval( $post->ID );


		if ( $post_id > 0 && ( $post_id == $mybookmark_pageid ) ) {
			return $content;
		}


		$post_type = $post->post_type;

		$post_types_to_show_bookmark = $settings->get_field( 'cbxbookmarkposttypes', 'cbxwpbookmark_basics', [
			'post',
			'page'
		] );
		if ( ! is_array( $post_types_to_show_bookmark ) ) {
			$post_types_to_show_bookmark = [];
		}

		$post_types_automation = $settings->get_field( 'post_types_automation', 'cbxwpbookmark_basics', [] );
		if ( ! is_array( $post_types_automation ) ) {
			$post_types_automation = [];
		}

		$position        = $settings->get_field( 'cbxbookmarkpostion', 'cbxwpbookmark_basics', 'after_content' );
		$skip_ids        = $settings->get_field( 'skip_ids', 'cbxwpbookmark_basics', '' );
		$skip_roles      = $settings->get_field( 'skip_roles', 'cbxwpbookmark_basics', '' );
		$show_in_archive = intval( $settings->get_field( 'showinarchive', 'cbxwpbookmark_basics', 0 ) );
		$show_in_home    = intval( $settings->get_field( 'showinhome', 'cbxwpbookmark_basics', 0 ) );
		$showcount       = intval( $settings->get_field( 'showcount', 'cbxwpbookmark_basics', 0 ) );


		//if disabled return content
		if ( $position == 'disable' ) {
			return $content;
		}

		//global $wp_the_query;


		/*if(is_main_query() && is_singular()){
			if($skip_ids != ''){
				$skip_ids_arr = explode( ',', $skip_ids );
				if ( sizeof( $skip_ids_arr ) > 0 ) {
					if ( in_array( $skip_ids_arr, $skip_ids_arr ) ) {
						return $content;
					}
				}
			}
		}*/

		//if(!is_main_query()) return $content;


		//if bookmark allowed for post types
		if ( ! in_array( $post_type, $post_types_to_show_bookmark ) ) {
			return $content;
		}

		//if automation allowed for post types
		if ( ! in_array( $post_type, $post_types_automation ) ) {
			return $content;
		}

		//if archive and show archive false then return content
		if ( ! $show_in_archive && is_archive() ) {
			return $content;
		}

		//if home and show in home false then return content
		if ( ! $show_in_home && ( is_home() && is_front_page() ) ) {
			return $content;
		}


		//grab bookmark button html
		if ( is_array( $skip_roles ) ) {
			$skip_roles = implode( ',', $skip_roles );
		}

		$auto_integration_ok = true;

		$bookmark_html = apply_filters( 'cbxwpbookmark_auto_integration', $auto_integration_ok, $post_id, $post_type, $showcount, $skip_ids, $skip_roles ) ? show_cbxbookmark_btn( $post_id, $post_type, $showcount, '', $skip_ids, $skip_roles ) : '';


		//attach the bookmark button html before or after the content
		if ( $position == 'after_content' ) {
			return $content . $bookmark_html;
		} elseif ( $position == 'before_content' ) {
			return $bookmark_html . $content;
		}
	}//end bookmark_auto_integration


	/**
	 * My Bookmarked posts Load more ajax hook
	 */
	public function bookmark_loadmore() {
		check_ajax_referer( 'cbxbookmarknonce', 'security' );

		$instance = [];
		$message  = [];

		if ( isset( $_POST['limit'] ) && $_POST['limit'] != null ) {
			$instance['limit'] = intval( $_POST['limit'] );
		}

		if ( isset( $_POST['offset'] ) && $_POST['offset'] != null ) {
			$instance['offset'] = intval( $_POST['offset'] );
		}

		if ( isset( $_POST['catid'] ) ) {
			$catid             = sanitize_text_field( wp_unslash( $_POST['catid'] ) );
			$instance['catid'] = array_filter( explode( ',', $catid ) );
		}

		if ( isset( $_POST['type'] ) ) {
			$type             = sanitize_text_field( wp_unslash( $_POST['type'] ) );
			$instance['type'] = array_filter( explode( ',', $type ) );
		}

		if ( isset( $_POST['userid'] ) && $_POST['userid'] != 0 ) {
			$instance['userid'] = intval( $_POST['userid'] );
		}

		if ( isset( $_POST['order'] ) && $_POST['order'] != null ) {
			$instance['order'] = sanitize_text_field( wp_unslash( $_POST['order'] ) );
		}

		if ( isset( $_POST['orderby'] ) && $_POST['orderby'] != null ) {
			$instance['orderby'] = sanitize_text_field( wp_unslash( $_POST['orderby'] ) );
		}

		$instance['allowdelete'] = isset( $_POST['allowdelete'] ) ? absint( $_POST['allowdelete'] ) : 0;

		if ( function_exists( 'cbxbookmark_post_html' ) && cbxbookmark_post_html( $instance, false ) ) {
			$message['code'] = 1;
			$message['data'] = cbxbookmark_post_html( $instance, false );
		} else {
			$message['code'] = 0;
		}

		echo wp_json_encode( $message );
		wp_die();
	}//end bookmark_loadmore


	/**
	 *  Add new category from the category listing
	 */
	public function add_category_std() {
		check_ajax_referer( 'cbxbookmarknonce', 'security' );

		$message = [];


		$settings      = $this->settings;
		$bookmark_mode = $settings->get_field( 'bookmark_mode', 'cbxwpbookmark_basics', 'user_cat' );

		if ( $bookmark_mode != 'user_cat' ) {
			$message['code'] = 0;
			$message['msg']  = esc_html__( 'Category add failed!', 'cbxwpbookmark' );
			echo wp_json_encode( $message );

			wp_die();
		}

		$user_id = get_current_user_id(); //get the current logged in user id

		$can_user_create_own_category = apply_filters( 'cbxwpbookmark_can_user_create_own_category', true, $user_id );


		if ( ! $can_user_create_own_category ) {
			$message['code'] = 0;
			$message['msg']  = esc_html__( 'Sorry, you can not create any more category, maximum allowed reached or you do not have enough permission.', 'cbxwpbookmark' );
			echo wp_json_encode( $message );
			wp_die();
		}

		global $wpdb;
		$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';
		$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

		$cat_name    = isset( $_POST['cat_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_name'] ) ) : '';
		$cat_privacy = isset( $_POST['privacy'] ) ? absint( $_POST['privacy'] ) : 1;


		$message = [];


		if ( $cat_name != '' ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$duplicate = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $category_table WHERE cat_name = %s and user_id = %d", $cat_name, $user_id ) );
		}


		if ( $cat_name == '' ) {
			$message['code'] = 0;
			$message['msg']  = esc_html__( 'Category name can not be empty', 'cbxwpbookmark' );
		} elseif ( intval( $duplicate ) > 0 ) {
			$message['code'] = 0;
			$message['msg']  = esc_html__( 'Category with same name already exists!', 'cbxwpbookmark' );
		} else {
			//create category
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$return = $wpdb->query( $wpdb->prepare( "INSERT INTO $category_table ( cat_name, user_id, privacy ) VALUES ( %s, %d, %d )", [
				$cat_name,
				$user_id,
				$cat_privacy
			] ) );

			if ( $return !== false ) {
				$cat_id = $wpdb->insert_id; //get the newly created category id or already exists one
				do_action( 'cbxbookmark_category_added', $cat_id, $user_id, $cat_name );

				$user_bookmark_page_url = cbxwpbookmarks_mybookmark_page_url();
				$cat_permalink          = $user_bookmark_page_url;
				$cat_permalink          = add_query_arg( [
					'cbxbmcatid' => $cat_id,
					'userid'     => $user_id
				], $cat_permalink );

				$message['code']      = 1;
				$message['msg']       = esc_html__( 'Category created successfully!', 'cbxwpbookmark' );
				$message['id']        = $cat_id;
				$message['userid']    = $user_id;
				$message['privacy']   = $cat_privacy;
				$message['list_html'] = '<li class="cbxbookmark-category-list-item " data-id="' . absint( $cat_id ) . '" data-userid="' . absint( $user_id ) . '" data-privacy="' . intval( $cat_privacy ) . '" data-name="' . wp_strip_all_tags( $cat_name ) . '"> <a href="' . esc_url( $cat_permalink ) . '" class="cbxlbjs-item-widget" data-privacy="' . intval( $cat_privacy ) . '">' . wp_strip_all_tags( $cat_name ) . '</a><i>(0)</i><span title="' . esc_html__( 'Click to edit',
						'cbxwpbookmark' ) . '" class="cbxbookmark-edit-btn"></span> <span title="' . esc_html__( 'Click to delete', 'cbxwpbookmark' ) . '" class="cbxbookmark-delete-btn" data-id="' . intval( $cat_id ) . '"></span></li>';
			} else {
				$message['code'] = 0;
				$message['msg']  = esc_html__( 'Category add failed!', 'cbxwpbookmark' );
			}
		}


		echo wp_json_encode( $message );

		wp_die();
	}//end add_category_std

	/**
	 *  Add new category
	 */
	public function add_category() {
		check_ajax_referer( 'cbxbookmarknonce', 'security' );

		global $wpdb;
		$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';
		$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

		$cat_id      = isset( $_POST['cat_id'] ) ? intval( $_POST['cat_id'] ) : 0;
		$cat_name    = isset( $_POST['cat_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_name'] ) ) : '';
		$cat_privacy = isset( $_POST['privacy'] ) ? absint( $_POST['privacy'] ) : 1;
		$object_id   = isset( $_POST['object_id'] ) ? absint( $_POST['object_id'] ) : 0;
		$object_type = isset( $_POST['object_type'] ) ? sanitize_text_field( wp_unslash( $_POST['object_type'] ) ) : 'post'; //post, page, user, product, any thing custom


		$user_id = get_current_user_id(); //get the current logged in user id
		$message = [];


		if ( $cat_name != '' ) {
			//category create mode
			if ( $cat_id == 0 ) {

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$duplicate = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $category_table WHERE cat_name = %s and user_id = %d", $cat_name, $user_id ) );
			} else {
				//category edit mode
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$duplicate = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $category_table WHERE cat_name = %s AND id != %d AND user_id = %d", $cat_name, $cat_id, $user_id ) );
			}
		}


		if ( $cat_name == '' ) {
			$message['code'] = 0;
			$message['msg']  = esc_html__( 'Category name can not be empty', 'cbxwpbookmark' );
		} elseif ( intval( $duplicate ) > 0 ) {
			$message['code'] = 0;
			$message['msg']  = esc_html__( 'Category with same name already exists!', 'cbxwpbookmark' );
		} else {

			if ( $cat_id == 0 ) {
				//create category
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$return = $wpdb->query( $wpdb->prepare( "INSERT INTO $category_table ( cat_name, user_id, privacy ) VALUES ( %s, %d, %d )", [
					$cat_name,
					$user_id,
					$cat_privacy
				] ) );
			} else {
				//Update category
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$return = $wpdb->update(
					$category_table, [
					'cat_name' => $cat_name,   // string
					'privacy'  => $cat_privacy // integer (number)
				], [
					'id'      => $cat_id,
					'user_id' => $user_id
				], [
					'%s', // value1
					'%d'  // value2
				], [
						'%d',
						'%d'
					]
				);
			}


			if ( $return !== false ) {

				$mode = 'update';
				if ( $cat_id == 0 ) {
					$mode   = 'add';
					$cat_id = $wpdb->insert_id; //get the newly created category id or already exists one
				}

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$cats_by_user = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $category_table WHERE user_id = %d", $user_id ), ARRAY_A );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$post_in_cats_t = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT cat_id FROM $bookmark_table WHERE object_type = %s AND  user_id = %d AND object_id = %d", [
					$object_type,
					$user_id,
					$object_id
				] ), ARRAY_A );


				$post_in_cats = [];
				foreach ( $post_in_cats_t as $cat ) {
					$post_in_cats[] = $cat['cat_id'];
				}

				foreach ( $cats_by_user as &$row ) {
					if ( in_array( $row['id'], $post_in_cats ) ) {
						$row['incat'] = 1;
					} else {
						$row['incat'] = 0;
					}
				}

				$message['code']   = 1;
				$message['msg']    = esc_html__( 'Category created/edited successfully!', 'cbxwpbookmark' );
				$message['cat_id'] = $cat_id;
				if ( $cats_by_user !== false ) {
					$message['cats'] = wp_json_encode( $cats_by_user );
				} else {
					$message['cats'] = 0;
				}

				if ( $mode == 'add' ) {
					do_action( 'cbxbookmark_category_added', $cat_id, $user_id, $cat_name );
				} else {
					do_action( 'cbxbookmark_category_edit', $cat_id, $user_id, $cat_name );
				}

			} else {
				$message['code'] = 0;
				$message['msg']  = esc_html__( 'Category add/edit failed!', 'cbxwpbookmark' );
			}

		}

		echo wp_json_encode( $message );
		wp_die();
	}//end add_category

	/**
	 *  Edit a Category (From bookmark popup panel)
	 */
	public function edit_category() {
		check_ajax_referer( 'cbxbookmarknonce', 'security' );

		global $wpdb;
		$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';
		$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';


		$cat_id      = isset( $_POST['cat_id'] ) ? intval( $_POST['cat_id'] ) : 0;
		$cat_name    = isset( $_POST['cat_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_name'] ) ) : '';
		$cat_privacy = isset( $_POST['privacy'] ) ? absint( $_POST['privacy'] ) : 1;
		$object_id   = isset( $_POST['object_id'] ) ? absint( $_POST['object_id'] ) : 0;
		$object_type = isset( $_POST['object_type'] ) ? sanitize_text_field( wp_unslash( $_POST['object_type'] ) ) : 'post'; //post, page, user, product, any thing custom

		$user_id = get_current_user_id(); //get the current logged in user id
		$message = [];


		if ( $cat_name != '' ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$duplicate = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $category_table WHERE cat_name = %s AND id != %d AND user_id = %d", $cat_name, $cat_id, $user_id ) );
		}


		if ( $cat_name == '' ) {
			$message['code'] = 0;
			$message['msg']  = esc_html__( 'Category name can not be empty', 'cbxwpbookmark' );
		} elseif ( $cat_id == 0 ) {
			$message['code'] = 0;
			$message['msg']  = esc_html__( 'Category id missing, are you cheating?', 'cbxwpbookmark' );
		} elseif ( intval( $duplicate ) > 0 ) {
			$message['code'] = 0;
			$message['msg']  = esc_html__( 'Another Category with same name already exists!', 'cbxwpbookmark' );
		} else {
			// Update Query
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$return = $wpdb->update(
				$category_table, [
				'cat_name' => $cat_name,   // string
				'privacy'  => $cat_privacy // integer (number)
			], [
				'id'      => $cat_id,
				'user_id' => $user_id
			], [
				'%s', // value1
				'%d'  // value2
			], [
					'%d',
					'%d'
				]
			);


			if ( $return !== false ) {

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$cats_by_user = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $category_table WHERE user_id = %d", $user_id ), ARRAY_A );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$post_in_cats_t = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT cat_id FROM $bookmark_table WHERE object_type = %s AND  user_id = %d AND object_id = %d", [
					$object_type,
					$user_id,
					$object_id
				] ), ARRAY_A );


				$post_in_cats = [];
				foreach ( $post_in_cats_t as $cat ) {
					$post_in_cats[] = $cat['cat_id'];
				}

				foreach ( $cats_by_user as &$row ) {
					if ( in_array( $row['id'], $post_in_cats ) ) {
						$row['incat'] = 1;
					} else {
						$row['incat'] = 0;
					}
				}

				$message['code']   = 1;
				$message['msg']    = esc_html__( 'Category updated successfully!', 'cbxwpbookmark' );
				$message['cat_id'] = $cat_id;

				if ( $cats_by_user !== false ) {
					$message['cats'] = wp_json_encode( $cats_by_user );
				} else {
					$message['cats'] = 0;
				}

				do_action( 'cbxbookmark_category_edit', $cat_id, $user_id, $cat_name );
			} else {
				$message['code'] = 0;
				$message['msg']  = esc_html__( 'Category add/edit failed!', 'cbxwpbookmark' );
			}

		}

		echo wp_json_encode( $message );
		wp_die();
	}//end edit_category


	/**
	 * Update Category(from user edit panel)
	 *
	 */
	public function update_bookmark_category() {
		check_ajax_referer( 'cbxbookmarknonce', 'security' );

		if ( isset( $_POST ) ) {
			global $wpdb;

			$data = [];

			$cat_name = isset( $_POST['catname'] ) ? sanitize_text_field( wp_unslash( $_POST['catname'] ) ) : '';
			$cat_id   = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
			$privacy  = isset( $_POST['privacy'] ) ? absint( $_POST['privacy'] ) : 1;
			$user_id  = get_current_user_id();

			// Category Table with database Prefix
			$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';

			// Update Query
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$update = $wpdb->update(
				$category_table, [
				'cat_name' => $cat_name, // string
				'privacy'  => $privacy   // integer (number)
			], [
				'id'      => $cat_id,
				'user_id' => $user_id
			], [
				'%s', // value1
				'%d'  // value2
			], [
					'%d',
					'%d'
				]
			);

			if ( $update !== false ) {

				do_action( 'cbxbookmark_category_edit', $cat_id, $user_id, $cat_name );

				$data['msg']     = esc_html__( "Data Updated Successfully", "cbxwpbookmark" );
				$data['flag']    = 1;
				$data['catname'] = $cat_name;
				$data['privacy'] = $privacy;
			} else {

				$data['msg']  = esc_html__( "Update Failed", "cbxwpbookmark" );
				$data['flag'] = 0;
			}

			echo wp_json_encode( $data );
		}
		wp_die();
	}//end update_bookmark_category

	/**
	 *
	 * Delete Category
	 */
	public function delete_bookmark_category() {

		check_ajax_referer( 'cbxbookmarknonce', 'security' );
		$message = [];

		global $wpdb;


		$settings      = $this->settings;
		$bookmark_mode = $settings->get_field( 'bookmark_mode', 'cbxwpbookmark_basics', 'user_cat' );

		if ( isset( $_POST ) && $bookmark_mode == 'user_cat' ) {
			$cat_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;


			$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';
			$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

			$user_id = get_current_user_id();

			do_action( 'cbxbookmark_category_deleted_before', $cat_id, $user_id );

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$delete_category = $wpdb->delete( $category_table, [
				'id'      => $cat_id,
				'user_id' => $user_id
			], [ '%d', '%d' ] );

			if ( $delete_category !== false ) {
				//deleted successfully
				$message['msg'] = 1;

				do_action( 'cbxbookmark_category_deleted', $cat_id, $user_id );

				//now delete any bookmark entry for that category
				//$delete_bookmark = $wpdb->delete($bookmark_table, array('cat_id' => $cat_id, 'user_id' => $user_id), array('%d', '%d'));

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$bookmarks_by_category = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $bookmark_table WHERE cat_id = %d", $cat_id ), ARRAY_A );

				if ( $bookmarks_by_category != null ) {
					foreach ( $bookmarks_by_category as $single_bookmark ) {
						cbxwpbookmarks_delete_bookmark( $single_bookmark['id'], $single_bookmark['user_id'], $single_bookmark['object_id'], $single_bookmark['object_type'] );
						/*do_action( 'cbxbookmark_bookmark_removed_before', $single_bookmark['id'], $single_bookmark['user_id'], $single_bookmark['object_id'], $single_bookmark['object_type'] );

						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $bookmark_table WHERE id=%d", intval( $single_bookmark['id'] ) ) );

						if ( $delete_status !== false ) {
							do_action( 'cbxbookmark_bookmark_removed', $single_bookmark['id'], $single_bookmark['user_id'], $single_bookmark['object_id'], $single_bookmark['object_type'] );
						}*/
					}
				}


				if ( isset( $_POST['object_id'] ) ) {
					$object_id   = intval( $_POST['object_id'] );
					$object_type = isset( $_POST['object_type'] ) ? sanitize_text_field( wp_unslash( $_POST['object_type'] ) ) : 'post'; //post, page, user, product, any thing custom

					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					$cats_by_user = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $category_table WHERE user_id = %d", $user_id ), ARRAY_A );

					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					$post_in_cats_t = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT cat_id FROM $bookmark_table WHERE object_type = %s AND  user_id = %d AND object_id = %d", [
						$object_type,
						$user_id,
						$object_id
					] ), ARRAY_A );


					$post_in_cats = [];
					foreach ( $post_in_cats_t as $cat ) {
						$post_in_cats[] = $cat['cat_id'];
					}

					foreach ( $cats_by_user as &$row ) {
						if ( in_array( $row['id'], $post_in_cats ) ) {
							$row['incat'] = 1;
						} else {
							$row['incat'] = 0;
						}
					}

					$message['cats'] = wp_json_encode( $cats_by_user );
				}

			} else {

				$message['msg'] = 0;
			}
		} else {

			$message['msg'] = esc_html__( "No data available", "cbxwpbookmark" );
		}

		echo wp_json_encode( $message );
		wp_die();
	}//end delete_bookmark_category

	/**
	 * Add Bookmark ajax request and response
	 *
	 */
	public function add_bookmark() {
		global $wpdb;

		check_ajax_referer( 'cbxbookmarknonce', 'security' );

		$alert_msg = '';

		//$settings       = new CBXWPBookmark_Settings_API();
		$settings      = $this->settings;
		$bookmark_mode = $settings->get_field( 'bookmark_mode', 'cbxwpbookmark_basics', 'user_cat' );

		$user_id   = get_current_user_id();
		$cat_id    = isset( $_POST['cat_id'] ) ? absint( $_POST['cat_id'] ) : 0;
		$object_id = isset( $_POST['object_id'] ) ? absint( $_POST['object_id'] ) : 0;

		$object_type = isset( $_POST['object_type'] ) ? sanitize_text_field( wp_unslash( $_POST['object_type'] ) ) : 'post'; //post, page or any custom post and later any object type

		$bookmark_table       = $wpdb->prefix . 'cbxwpbookmark';
		$user_bookmarks_count = cbxwpbookmarks_getTotalBookmarkByUser( $user_id );
		$category_privacy     = 1;

		if ( $bookmark_mode == 'no_cat' ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$duplicate = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $bookmark_table WHERE object_type = %s AND object_id = %d AND user_id = %d", [
				$object_type,
				$object_id,
				$user_id
			] ) );

			//$category_privacy = 1; //no category that means publicly sharable information

		} else {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$duplicate = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $bookmark_table WHERE object_type = %s AND object_id = %d AND cat_id = %d AND user_id = %d", [
				$object_type,
				$object_id,
				$cat_id,
				$user_id
			] ) );

			$single_category  = CBXWPBookmarkHelper::singleCategory( $cat_id );
			$category_privacy = $single_category['privacy'];
		}


		$message = [];


		if ( intval( $duplicate ) > 0 ) {
			if ( $bookmark_mode == 'no_cat' ) {
				//already exists, so remove
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$return = $wpdb->query( $wpdb->prepare( "DELETE FROM $bookmark_table WHERE object_type = %s AND object_id = %d AND user_id = %d", [
					$object_type,
					$object_id,
					$user_id
				] ) );
			} else {
				//already exists, so remove
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$return = $wpdb->query( $wpdb->prepare( "DELETE FROM $bookmark_table WHERE object_type = %s AND object_id = %d AND cat_id = %d AND user_id = %d", [
					$object_type,
					$object_id,
					$cat_id,
					$user_id
				] ) );
			}


			if ( $return !== false ) {
				//$user_bookmarks_count--;

				$message['code']      = 1; //operation success
				$message['msg']       = esc_html__( 'Bookmark removed!', 'cbxwpbookmark' );
				$message['operation'] = 0;
				//$message['user_bookmarks_count'] = $user_bookmarks_count;
				$bookmark_id = $duplicate;

				do_action( 'cbxbookmark_bookmark_removed', $bookmark_id, $user_id, $object_id, $object_type );
			} else {
				$message['code'] = 0; //operation failed
				$message['msg']  = esc_html__( 'Bookmark remove failed!', 'cbxwpbookmark' );
			}
		} else {
			//doesn't exists, so allow bookmark

			$gate = [
				'allow' => true,
				'msg'   => ''
			];


			$gate      = apply_filters( 'cbxwpbookmark_user_bookmark_allow', $gate, $user_id, $user_bookmarks_count );
			$allow     = isset( $gate['allow'] ) ? boolval( $gate['allow'] ) : false;
			$alert_msg = isset( $gate['msg'] ) ? $gate['msg'] : '';

			if ( $allow ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$return = $wpdb->query( $wpdb->prepare( "INSERT INTO $bookmark_table ( object_id, object_type, cat_id, user_id ) VALUES ( %d,%s, %d, %d )", [
					$object_id,
					$object_type,
					$cat_id,
					$user_id
				] ) );

				if ( $return !== false ) {
					//$user_bookmarks_count++;

					$message['code']      = 1; //db operation success
					$message['msg']       = esc_html__( 'Bookmark added!', 'cbxwpbookmark' );
					$message['operation'] = 1;
					//$message['user_bookmarks_count'] = $user_bookmarks_count;


					$bookmark_id = $wpdb->insert_id;

					do_action( 'cbxbookmark_bookmark_added', $bookmark_id, $user_id, $object_id, $object_type, $category_privacy );

				} else {
					$message['code'] = 0; //db operation failed
					$message['msg']  = esc_html__( 'Bookmark add failed', 'cbxwpbookmark' );
				}
			} else {
				$message['code'] = 0;
				$message['msg']  = esc_html__( 'Bookmark add failed', 'cbxwpbookmark' );
			}
		}//end allow to bookmark block

		$bookmark_total   = absint( CBXWPBookmarkHelper::getTotalBookmark( $object_id ) );
		$bookmark_by_user = CBXWPBookmarkHelper::isBookmarkedByUser( $object_id );

		$message['bookmark_count']  = $bookmark_total;
		$message['bookmark_byuser'] = ( $bookmark_by_user ) ? 1 : 0;

		if ( $alert_msg != '' ) {
			$message['alert_msg'] = $alert_msg;
		}

		echo wp_json_encode( $message );
		wp_die();
	}//end add_bookmark

	/**
	 * Delete bookmarked Post
	 */
	public function delete_bookmark_post() {
		global $wpdb;
		$data = [];

		check_ajax_referer( 'cbxbookmarknonce', 'security' );

		if ( isset( $_POST ) ) {
			$bookmark_id = isset( $_POST['bookmark_id'] ) ? absint( $_POST['bookmark_id'] ) : 0;
			$object_id   = isset( $_POST['object_id'] ) ? absint( $_POST['object_id'] ) : 0;
			$object_type = isset( $_POST['object_type'] ) ? sanitize_text_field( wp_unslash( $_POST['object_type'] ) ) : 'post'; //post, page or any custom post and later any object type


			//$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

			$user_id = get_current_user_id();

			//$single_bookmark = CBXWPBookmarkHelper::singleBookmarkByObjectUser( $object_id, $user_id );

			$delete_status = cbxwpbookmarks_delete_bookmark( $bookmark_id, $user_id, $object_id, $object_type );

			if ( $delete_status ) {
				$data['msg'] = 0;
			} else {
				$data['msg'] = 1;
			}

			/*do_action( 'cbxbookmark_bookmark_removed_before', $bookmark_id, $user_id, $object_id, $object_type );

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$delete_bookmark = $wpdb->delete( $bookmark_table, [
				'object_id' => $object_id,
				'user_id'   => $user_id
			], [ '%d', '%d' ] );

			if ( $delete_bookmark !== false ) {
				$data['msg'] = 0;

				do_action( 'cbxbookmark_bookmark_removed', $bookmark_id, $user_id, $object_id, $object_type );
			} else {

				$data['msg'] = 1;
			}*/
		} else {
			$data['msg'] = esc_html__( 'No data available', 'cbxwpbookmark' );
		}

		echo wp_json_encode( $data );
		wp_die();
	}//end delete_bookmark_post

	/**
	 * enqueue styles
	 */
	public function enqueue_styles() {
		do_action( 'cbxwpbookmark_css_start' );

		wp_register_style( 'awesome-notifications', CBXWPBOOKMARK_ROOT_URL . 'assets/vendors/awesome-notifications/style.css', [], CBXWPBOOKMARK_PLUGIN_VERSION, 'all' );
		wp_register_style( 'cbxwpbookmarkpublic-css', CBXWPBOOKMARK_ROOT_URL . 'assets/css/cbxwpbookmark-public.css', [ 'awesome-notifications' ], CBXWPBOOKMARK_PLUGIN_VERSION, 'all' );

		wp_enqueue_style( 'awesome-notifications' );
		wp_enqueue_style( 'cbxwpbookmarkpublic-css' );

		do_action( 'cbxwpbookmark_css_end' );
	}//end enqueue_styles

	/**
	 * enqueue scripts
	 */
	public function enqueue_scripts() {

		$close_svg = cbxwpbookmarks_load_svg( 'icon_close' );

		$settings = $this->settings;

		$bookmark_mode           = $settings->get_field( 'bookmark_mode', 'cbxwpbookmark_basics', 'user_cat' );
		$category_default_status = intval( $settings->get_field( 'category_status', 'cbxwpbookmark_basics', 1 ) );

		$hide_cat_privacy = intval( $settings->get_field( 'hide_cat_privacy', 'cbxwpbookmark_basics', 0 ) );

		$cat_hide_class = ( $hide_cat_privacy == 1 ) ? 'cbxwpbkmark_cat_hide' : '';

		do_action( 'cbxwpbookmark_js_start' );

		wp_enqueue_script( 'jquery' );


		$category_template = '
            <div class="cbxbookmark-mycat-editbox">
                <input class="cbxbmedit-catname cbxbmedit-catname-edit" name="catname" value="##catname##" placeholder="' . esc_html__( 'Category title', 'cbxwpbookmark' ) . '" />                
                <select class="cbxbmedit-privacy input-catprivacy ' . $cat_hide_class . '" name="catprivacy">
                  <option value="1" title="' . esc_html__( 'Public Category', 'cbxwpbookmark' ) . '">' . esc_html__( 'Public', 'cbxwpbookmark' ) . '</option>
                  <option value="0" title="' . esc_html__( 'Private Category', 'cbxwpbookmark' ) . '">' . esc_html__( 'Private', 'cbxwpbookmark' ) . '</option>
                </select>
                <button data-busy="0" title="' . esc_attr__( 'Click to update', 'cbxwpbookmark' ) . '"  class="cbxbookmark-btn cbxbookmark-cat-save ld-ext-right">' . esc_html__( 'Update', 'cbxwpbookmark' ) . '<i class="ld ld-ring ld-spin"></i></button>
                <button title="' . esc_attr__( 'Click to close', 'cbxwpbookmark' ) . '"  class="cbxbookmark-btn cbxbookmark-btn-secondary cbxbookmark-cat-close icon icon-only cbx-icon-parent-flex" ><i class="cbx-icon">' . $close_svg . '</i><i class="cbxbookmark-cat-close-label sr-only">' . esc_html__( 'Close', 'cbxwpbookmark' ) . '</i></button>
                <div class="clear clearfix cbxwpbkmark-clearfix"></div>
            </div>';

		if ( $bookmark_mode != 'user_cat' ) {
			$category_template = '';
		}


		wp_register_script( 'awesome-notifications', CBXWPBOOKMARK_ROOT_URL . 'assets/vendors/awesome-notifications/script.js', [], CBXWPBOOKMARK_PLUGIN_VERSION, true );


		wp_register_script( 'cbxwpbookmark-events', CBXWPBOOKMARK_ROOT_URL . 'assets/js/cbxwpbookmark-events.js', [], CBXWPBOOKMARK_PLUGIN_VERSION, true );
		wp_register_script( 'cbxwpbookmarkpublicjs', CBXWPBOOKMARK_ROOT_URL . 'assets/js/cbxwpbookmark-public.js', [
			'cbxwpbookmark-events',
			'awesome-notifications',
			'jquery'
		], CBXWPBOOKMARK_PLUGIN_VERSION, true );

		//
		$shareurl_html = '<div class="shareurl_modal">';
		$shareurl_html .= '<h3>' . esc_html__( 'Copy and Share Url', 'cbxwpbookmark' ) . '</h3>';
		$shareurl_html .= '<div class="shareurl_copy">';
		$shareurl_html .= '<input readonly class="shareurl_copy_input" name="shareurl_copy_input" type="text" value="##share_url##" />';
		$shareurl_html .= '<button data-url="##share_url##" class="shareurl_copy_btn">' . esc_html__( 'Copy', 'cbxwpbookmark' ) . '</button>';
		$shareurl_html .= '</div>';
		$shareurl_html .= '</div>';

		$cbxwpbookmark_translation = [
			'ajaxurl'                              => admin_url( 'admin-ajax.php' ),
			'nonce'                                => wp_create_nonce( "cbxbookmarknonce" ),
			//'cbxbookmark_lang'           => get_user_locale(),
			'cat_template'                         => wp_json_encode( $category_template ),
			'category_delete_success'              => esc_html__( 'Category deleted successfully', 'cbxwpbookmark' ),
			'category_delete_error'                => esc_html__( 'Unable to delete the category', 'cbxwpbookmark' ),
			'are_you_sure_global'                  => esc_html__( 'Are you sure?', 'cbxwpbookmark' ),
			'are_you_sure_delete_desc'             => esc_html__( 'Once you delete, it\'s gone forever. You can not revert it back.', 'cbxwpbookmark' ),
			'areyousuretodeletecat'                => esc_html__( 'Are you sure you want to delete this Bookmark Category?', 'cbxwpbookmark' ),
			'areyousuretodeletebookmark'           => esc_html__( 'Are you sure you want to delete this Bookmark?', 'cbxwpbookmark' ),
			'bookmark_failed'                      => esc_html__( 'Failed to Bookmark', 'cbxwpbookmark' ),
			'bookmark_removed'                     => esc_html__( 'Bookmark Removed', 'cbxwpbookmark' ),
			'bookmark_removed_empty'               => esc_html__( 'All Bookmarks Removed', 'cbxwpbookmark' ),
			'bookmark_removed_failed'              => esc_html__( 'Bookmark Removed Failed', 'cbxwpbookmark' ),
			'error_msg'                            => esc_html__( 'Error loading data. Response code = ', 'cbxwpbookmark' ),
			'category_name_empty'                  => esc_html__( 'Category name can not be empty', 'cbxwpbookmark' ),
			'add_to_head_default'                  => esc_html__( 'Click Category to Bookmark', 'cbxwpbookmark' ),
			'add_to_head_cat_list'                 => esc_html__( 'Click to Edit Category', 'cbxwpbookmark' ),
			'add_to_head_cat_edit'                 => esc_html__( 'Edit Category', 'cbxwpbookmark' ),
			'add_to_head_cat_create'               => esc_html__( 'Create Category', 'cbxwpbookmark' ),
			'no_cats_found'                        => esc_html__( 'No category found', 'cbxwpbookmark' ),
			'add_to_head_max_cat'                  => esc_html__( 'Maximum category limit reached', 'cbxwpbookmark' ),
			//'category_loaded_add'        => esc_html__('Click Category to Bookmark', 'cbxwpbookmark'),
			'max_cat_limit'                        => 0,
			'max_cat_limit_error'                  => esc_html__( 'Sorry, you reached the maximum category limit and to create one one, please delete unnecessary categories first', 'cbxwpbookmark' ),
			'user_current_cat_count'               => 0,
			'user_current_cats'                    => '',
			'user_can_create_cat'                  => 1,
			'bookmark_mode'                        => $bookmark_mode,
			'bookmark_not_found'                   => esc_html__( 'No bookmarks found', 'cbxwpbookmark' ),
			'load_more'                            => esc_html__( 'Load More ...', 'cbxwpbookmark' ),
			'category_default_status'              => $category_default_status,
			'delete_all_bookmarks_by_user_confirm' => esc_html__( 'Are you sure to delete all of your bookmarks? This process can not be undone.', 'cbxwpbookmark' ),
			//default category status if user category mode is enabled
			'awn_options'                          => [
				'tip'           => esc_html__( 'Tip', 'cbxwpbookmark' ),
				'info'          => esc_html__( 'Info', 'cbxwpbookmark' ),
				'success'       => esc_html__( 'Success', 'cbxwpbookmark' ),
				'warning'       => esc_html__( 'Attention', 'cbxwpbookmark' ),
				'alert'         => esc_html__( 'Error', 'cbxwpbookmark' ),
				'async'         => esc_html__( 'Loading', 'cbxwpbookmark' ),
				'confirm'       => esc_html__( 'Confirmation', 'cbxwpbookmark' ),
				'confirmOk'     => esc_html__( 'OK', 'cbxwpbookmark' ),
				'confirmCancel' => esc_html__( 'Cancel', 'cbxwpbookmark' )
			],
			'shareurl_html'                        => $shareurl_html,
			'shareurl_trans'                       => [
				'copy'   => esc_html__( 'Copy', 'cbxwpbookmark' ),
				'copied' => esc_html__( 'Copied', 'cbxwpbookmark' )
			]
		];

		$cbxwpbookmark_translation = apply_filters( 'cbxwpbookmark_public_jsvar', $cbxwpbookmark_translation );

		wp_localize_script( 'cbxwpbookmarkpublicjs', 'cbxwpbookmark', $cbxwpbookmark_translation );

		wp_enqueue_script( 'cbxwpbookmark-events' );
		wp_enqueue_script( 'awesome-notifications' );


		do_action( 'cbxwpbookmark_js_before_cbxwpbookmarkpublicjs' );

		wp_enqueue_script( 'cbxwpbookmarkpublicjs' );

		do_action( 'cbxwpbookmark_js_end' );
	}//end enqueue_scripts

	/**
	 * Load bookmark sublist via ajax
	 */
	public function load_bookmarks_sublist() {
		check_ajax_referer( 'cbxbookmarknonce', 'security' );

		global $wpdb;


		$settings      = $this->settings;
		$bookmark_mode = $settings->get_field( 'bookmark_mode', 'cbxwpbookmark_basics', 'user_cat' );


		$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';
		$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

		$user_id = absint( get_current_user_id() ); //get the current logged in user id

		$cat_id    = isset( $_POST['cat_id'] ) ? absint( $_POST['cat_id'] ) : 0;
		$cat_total = isset( $_POST['cat_total'] ) ? absint( $_POST['cat_total'] ) : 0;
		$privacy   = isset( $_POST['privacy'] ) ? absint( $_POST['privacy'] ) : 1;
		$userid    = isset( $_POST['userid'] ) ? absint( $_POST['userid'] ) : 0;
		$totalpage = isset( $_POST['totalpage'] ) ? absint( $_POST['totalpage'] ) : 0;
		$page      = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 0;


		$per_page   = apply_filters( 'cbxwpbookmark_sublist_perpage', 10 );
		$total_page = ceil( $cat_total / $per_page );

		if ( $userid == 0 ) {
			$userid = get_current_user_id();
		}

		if ( $userid == 0 ) {
			$privacy = 1; //only public
		}

		$main_sql             = '';
		$cat_sql              = '';
		$category_privacy_sql = '';


		//$page = 1;


		$start_point = ( $page * $per_page ) - $per_page;
		$limit_sql   = "LIMIT";
		$limit_sql   .= ' ' . $start_point . ',';
		$limit_sql   .= ' ' . $per_page;

		$order_by = 'object_id';
		$order    = 'DESC';

		if ( $bookmark_mode == 'user_cat' ) {
			$param    = [ $userid, $cat_id ];
			$main_sql .= "SELECT *  FROM $bookmark_table  WHERE user_id=%d AND cat_id = %d group by object_id  ORDER BY $order_by $order $limit_sql";

		} else {
			$param    = [ $cat_id ];
			$main_sql .= "SELECT *  FROM $bookmark_table  WHERE cat_id = %d group by object_id  ORDER BY $order_by $order $limit_sql";
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		$items = $wpdb->get_results( $wpdb->prepare( $main_sql, $param ) );

		$output = '';

		if ( $items === null || sizeof( $items ) > 0 ) {
			$object_types = CBXWPBookmarkHelper::object_types( true ); //get plain post type as array

			$instance               = [];
			$instance['show_thumb'] = 0;


			foreach ( $items as $item ) {
				$action_html = '';

				$sub_item_class = 'cbxbookmark-category-list-sublist-item';

				if ( in_array( $item->object_type, $object_types ) ) {

					$li_output = cbxwpbookmark_get_template_html( 'bookmarkpost/single.php', [
						'item'           => $item,
						'action_html'    => $action_html,
						'sub_item_class' => $sub_item_class
					] );

					$output .= $li_output;

				} else {
					ob_start();

					do_action( 'cbxwpbookmark_othertype_item', $instance, $item, $action_html, $sub_item_class );

					$li_output = ob_get_clean();
					$output    .= $li_output;
				}
			}
		}


		$message = [];
		//code 1 = bookmarks found
		//code 0 = bookmarks not found

		if ( $output != '' ) {
			$message['page']      = $page;
			$message['totalpage'] = $totalpage;
			$message['show_more'] = ( $page < $totalpage ) ? 1 : 0;
			$message['code']      = 1;
			$message['msg']       = esc_html__( 'Bookmarks loaded', 'cbxwpbookmark' );
			$message['output']    = wp_json_encode( $output );
		} else {
			$message['page']      = $page;
			$message['totalpage'] = $totalpage;
			$message['show_more'] = ( $page < $totalpage ) ? 1 : 0;
			$message['code']      = 0;
			$message['msg']       = esc_html__( 'Bookmark not found', 'cbxwpbookmark' );
		}

		echo wp_json_encode( $message );
		wp_die();
	}//end load_bookmarks_sublist

	public function init_misc() {
		//elementor

		//elementor Widget
		add_action( 'elementor/widgets/register', [ $this, 'init_elementor_widgets' ] );

		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'elementor_icon_loader' ], 99999 );
	}//end method init_misc

	/**
	 * Init elementor widget
	 *
	 * @throws Exception
	 */
	public function init_elementor_widgets() {

		//register the bookmark button widget
		if ( ! class_exists( 'CBXWPBookmarkBtn_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxwpbookmark-btn-elemwidget.php';
		}
		\Elementor\Plugin::instance()->widgets_manager->register( new CBXWPBookmark_ElemWidget\Widgets\CBXWPBookmarkBtn_ElemWidget() );


		//register the my bookmarks widget
		if ( ! class_exists( 'CBXWPBookmarkMyBookmark_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxwpbookmark-mybookmark-elemwidget.php';
		}
		\Elementor\Plugin::instance()->widgets_manager->register( new CBXWPBookmark_ElemWidget\Widgets\CBXWPBookmarkMyBookmark_ElemWidget() );


		//register the bookmark category widget
		if ( ! class_exists( 'CBXWPBookmarkCategory_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxwpbookmark-category-elemwidget.php';
		}
		\Elementor\Plugin::instance()->widgets_manager->register( new CBXWPBookmark_ElemWidget\Widgets\CBXWPBookmarkCategory_ElemWidget() );


		//register the most bookmarked posts widget
		if ( ! class_exists( 'CBXWPBookmarkMost_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxwpbookmark-most-elemwidget.php';
		}
		\Elementor\Plugin::instance()->widgets_manager->register( new CBXWPBookmark_ElemWidget\Widgets\CBXWPBookmarkMost_ElemWidget() );

	}//end widgets_registered

	/**
	 * Add new category to elementor
	 *
	 * @param $elements_manager
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'cbxwpbookmark',
			[
				'title' => esc_html__( 'CBX Bookmark Widgets', 'cbxwpbookmark' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}//end add_elementor_widget_categories

	/**
	 * Load Elementor Custom Icon
	 */
	public function elementor_icon_loader() {
		wp_register_style( 'cbxwpbookmark_elementor_icon',
			CBXWPBOOKMARK_ROOT_URL . 'assets/css/cbxwpbookmark-elementor.css', false, $this->version );
		wp_enqueue_style( 'cbxwpbookmark_elementor_icon' );

	}//end elementor_icon_loader

	/**
	 * WPBakery Widgets registers
	 *
	 * Before WPBakery inits includes the new widgets
	 */
	public function vc_before_init_actions() {
		//include vc params
		if ( ! class_exists( 'CBXWPBookmark_VCParam_DropDownMulti' ) ) {
			require_once CBXWPBOOKMARK_ROOT_PATH . 'widgets/vc_widgets/params/class-cbxwpbookmark-vc-param-dropdown-multi.php';
		}

		//includes the vc widgets
		//bookmark button widget
		if ( ! class_exists( 'CBXWPBookmarkBtn_VCWidget' ) ) {
			require_once CBXWPBOOKMARK_ROOT_PATH . 'widgets/vc_widgets/class-cbxwpbookmark-btn-vcwidget.php';
		}
		new CBXWPBookmarkBtn_VCWidget();

		//my bookmarked post widget
		if ( ! class_exists( 'CBXWPBookmarkMyBookmark_VCWidget' ) ) {
			require_once CBXWPBOOKMARK_ROOT_PATH . 'widgets/vc_widgets/class-cbxwpbookmark-mybookmark-vcwidget.php';
		}
		new CBXWPBookmarkMyBookmark_VCWidget();

		//my bookmark category widget
		if ( ! class_exists( 'CBXWPBookmarkCategory_VCWidget' ) ) {
			require_once CBXWPBOOKMARK_ROOT_PATH . 'widgets/vc_widgets/class-cbxwpbookmark-category-vcwidget.php';
		}
		new CBXWPBookmarkCategory_VCWidget();


		//most bookmarked post widget
		if ( ! class_exists( 'CBXWPBookmarkMost_VCWidget' ) ) {
			require_once CBXWPBOOKMARK_ROOT_PATH . 'widgets/vc_widgets/class-cbxwpbookmark-most-vcwidget.php';
		}
		new CBXWPBookmarkMost_VCWidget();
	}//end vc_before_init_actions

	public function admin_init_ajax_lang() {
		if ( defined( 'DOING_AJAX' ) ) {
			//write_log($_REQUEST);
		}
	}//end method admin_init_ajax_lang

	/**
	 * Adding bookmark style guide
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	public function add_theme_class( $classes ) {
		$settings             = $this->settings;
		$bookmark_theme_class = $settings->get_field( 'display_theme', 'cbxwpbookmark_basics', 'cbxwpbookmark-blue' );

		return array_merge( $classes, [ $bookmark_theme_class ] );
	}//end method add_theme_class

	/**
	 * Delete all bookmarks of any user by user from frontend
	 */
	public function delete_all_bookmarks_by_user() {
		check_ajax_referer( 'cbxbookmarknonce', 'security' );
		$message = [];
		if ( ! is_user_logged_in() ) {
			$message['code'] = 1;
			$message['msg']  = esc_html__( 'Please login to delete your bookmarks.', 'cbxwpbookmark' );

			wp_send_json( $message );
		}

		$user_id   = absint( get_current_user_id() );
		$bookmarks = CBXWPBookmarkHelper::getBookmarksByUser( $user_id );

		if ( is_array( $bookmarks ) && sizeof( $bookmarks ) > 0 ) {
			global $wpdb;
			$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

			foreach ( $bookmarks as $single_bookmark ) {
				$id = absint( $single_bookmark['id'] );

				$delete_status = cbxwpbookmarks_delete_bookmark( $id, $single_bookmark['user_id'], $single_bookmark['object_id'], $single_bookmark['object_type'] );

				/*do_action( 'cbxbookmark_bookmark_removed_before', $id, $single_bookmark['user_id'], $single_bookmark['object_id'], $single_bookmark['object_type'] );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $bookmark_table WHERE id=%d", intval( $id ) ) );

				if ( $delete_status !== false ) {
					do_action( 'cbxbookmark_bookmark_removed', $id, $single_bookmark['user_id'], $single_bookmark['object_id'], $single_bookmark['object_type'] );
				}*/
			}
		}


		$message['code'] = 1;
		$message['msg']  = esc_html__( 'All bookmarks deleted.', 'cbxwpbookmark' );

		wp_send_json( $message );
	}//end delete_all_bookmarks_by_user

	public function bbp_template_before_single_topic() {
		$this->bbp_template_single_topic_automation( 'before' );
	}//end bbp_template_before_single_topic

	public function bbp_template_after_single_topic() {
		$this->bbp_template_single_topic_automation( 'after' );
	}//end bbp_template_after_single_topic

	public function bbp_template_before_single_forum() {
		$this->bbp_template_single_forum_automation( 'before' );
	}//end bbp_template_before_single_forum

	public function bbp_template_after_single_forum() {
		$this->bbp_template_single_forum_automation( 'after' );
	}//end bbp_template_after_single_forum

	/**
	 * bbpress forum : display bookmark automatically(not in used)
	 *
	 * @param $content
	 * @param $r
	 * @param $args
	 *
	 * @return mixed|string
	 */
	public function bbp_get_single_forum_description( $content, $r, $args ) {
		$post_id = bbp_get_forum_id( $r['forum_id'] );


		$post_type = 'forum';

		$settings = $this->settings;

		$post_types_to_show_bookmark = $settings->get_field( 'cbxbookmarkposttypes', 'cbxwpbookmark_basics', [
			'post',
			'page'
		] );
		if ( ! is_array( $post_types_to_show_bookmark ) ) {
			$post_types_to_show_bookmark = [];
		}

		$post_types_automation = $settings->get_field( 'post_types_automation', 'cbxwpbookmark_basics', [] );
		if ( ! is_array( $post_types_automation ) ) {
			$post_types_automation = [];
		}


		$position   = $settings->get_field( 'cbxbookmarkpostion', 'cbxwpbookmark_basics', 'after_content' );
		$skip_ids   = $settings->get_field( 'skip_ids', 'cbxwpbookmark_basics', '' );
		$skip_roles = $settings->get_field( 'skip_roles', 'cbxwpbookmark_basics', '' );
		$showcount  = intval( $settings->get_field( 'showcount', 'cbxwpbookmark_basics', 0 ) );

		if ( $position == 'disable' ) {
			return $content;
		}

		//if bookmark allowed for post types
		if ( ! in_array( $post_type, $post_types_to_show_bookmark ) ) {
			return $content;
		}

		//if automation allowed for post types
		if ( ! in_array( $post_type, $post_types_automation ) ) {
			return $content;
		}

		//grab bookmark button html
		if ( is_array( $skip_roles ) ) {
			$skip_roles = implode( ',', $skip_roles );
		}


		$auto_integration_ok = true;

		$bookmark_html = apply_filters( 'cbxwpbookmark_auto_integration', $auto_integration_ok, $post_id, $post_type, $showcount, $skip_ids, $skip_roles ) ? show_cbxbookmark_btn( $post_id, $post_type, $showcount, '', $skip_ids, $skip_roles ) : '';


		//attach the bookmark button html before or after the content
		if ( $position == 'after_content' ) {
			return $content . $bookmark_html;
		} elseif ( $position == 'before_content' ) {
			return $bookmark_html . $content;
		}

		return $content;
	}//end  bbp_get_single_forum_description

	/**
	 * bbpress forum : display bookmark automatically
	 *
	 *
	 * @return mixed|string
	 */
	public function bbp_template_single_forum_automation( $pos = 'before' ) {

		$post_id = bbp_get_forum_id();


		$post_type = 'forum';

		$settings = $this->settings;

		$post_types_to_show_bookmark = $settings->get_field( 'cbxbookmarkposttypes', 'cbxwpbookmark_basics', [
			'post',
			'page'
		] );
		if ( ! is_array( $post_types_to_show_bookmark ) ) {
			$post_types_to_show_bookmark = [];
		}

		$post_types_automation = $settings->get_field( 'post_types_automation', 'cbxwpbookmark_basics', [] );
		if ( ! is_array( $post_types_automation ) ) {
			$post_types_automation = [];
		}


		$position   = $settings->get_field( 'cbxbookmarkpostion', 'cbxwpbookmark_basics', 'after_content' );
		$skip_ids   = $settings->get_field( 'skip_ids', 'cbxwpbookmark_basics', '' );
		$skip_roles = $settings->get_field( 'skip_roles', 'cbxwpbookmark_basics', '' );
		$showcount  = intval( $settings->get_field( 'showcount', 'cbxwpbookmark_basics', 0 ) );

		if ( $position == 'disable' ) {
			return;
		}

		//if bookmark allowed for post types
		if ( ! in_array( $post_type, $post_types_to_show_bookmark ) ) {
			return;
		}

		//if automation allowed for post types
		if ( ! in_array( $post_type, $post_types_automation ) ) {
			return;
		}

		//grab bookmark button html
		if ( is_array( $skip_roles ) ) {
			$skip_roles = implode( ',', $skip_roles );
		}


		$auto_integration_ok = true;

		$bookmark_html = apply_filters( 'cbxwpbookmark_auto_integration', $auto_integration_ok, $post_id, $post_type, $showcount, $skip_ids, $skip_roles ) ? show_cbxbookmark_btn( $post_id, $post_type, $showcount, '', $skip_ids, $skip_roles ) : '';


		//attach the bookmark button html before or after the content
		if ( $position == 'after_content' && $pos == 'after' ) {
			echo $bookmark_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} elseif ( $position == 'before_content' && $pos == 'before' ) {
			echo $bookmark_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}//end  bbp_template_single_forum_automation


	/**
	 * bbpress topic : display bookmark automatically
	 *
	 *
	 * @return mixed|string
	 */
	public function bbp_template_single_topic_automation( $pos = 'before' ) {
		$content = '';
		$post_id = bbp_get_topic_id();


		$post_type = 'topic';

		$settings = $this->settings;

		$post_types_to_show_bookmark = $settings->get_field( 'cbxbookmarkposttypes', 'cbxwpbookmark_basics', [
			'post',
			'page'
		] );
		if ( ! is_array( $post_types_to_show_bookmark ) ) {
			$post_types_to_show_bookmark = [];
		}

		$post_types_automation = $settings->get_field( 'post_types_automation', 'cbxwpbookmark_basics', [] );
		if ( ! is_array( $post_types_automation ) ) {
			$post_types_automation = [];
		}


		$position   = $settings->get_field( 'cbxbookmarkpostion', 'cbxwpbookmark_basics', 'after_content' );
		$skip_ids   = $settings->get_field( 'skip_ids', 'cbxwpbookmark_basics', '' );
		$skip_roles = $settings->get_field( 'skip_roles', 'cbxwpbookmark_basics', '' );
		$showcount  = intval( $settings->get_field( 'showcount', 'cbxwpbookmark_basics', 0 ) );

		if ( $position == 'disable' ) {
			return;
		}

		//if bookmark allowed for post types
		if ( ! in_array( $post_type, $post_types_to_show_bookmark ) ) {
			return $content;
		}

		//if automation allowed for post types
		if ( ! in_array( $post_type, $post_types_automation ) ) {
			return $content;
		}

		//grab bookmark button html
		if ( is_array( $skip_roles ) ) {
			$skip_roles = implode( ',', $skip_roles );
		}


		$auto_integration_ok = true;

		$bookmark_html = apply_filters( 'cbxwpbookmark_auto_integration', $auto_integration_ok, $post_id, $post_type, $showcount, $skip_ids, $skip_roles ) ? show_cbxbookmark_btn( $post_id, $post_type, $showcount, '', $skip_ids, $skip_roles ) : '';


		//attach the bookmark button html before or after the content
		if ( $position == 'after_content' && $pos == 'after' ) {
			echo $content . $bookmark_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} elseif ( $position == 'before_content' && $pos == 'before' ) {
			echo $bookmark_html . $content; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}//end bbp_template_single_topic_automation
}//end class CBXWPbookmark_Public