<?php

//use Cbx\Bookmark\Helpers\CBXWPBookmarkHelper;
use Cbx\Bookmark\CBXWPBookmarkSettings;
use enshrined\svgSanitize\Sanitizer;

if ( ! function_exists( 'cbxwpbookmark_is_rest_api_request' ) ) {
	/**
	 * Check if doing rest request
	 *
	 * @return bool
	 */
	function cbxwpbookmark_is_rest_api_request() {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}

		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix = trailingslashit( rest_get_url_prefix() );

		return ( false !== strpos( wp_unslash( $_SERVER['REQUEST_URI'] ), $rest_prefix ) );//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	}//end function cbxwpbookmark_is_rest_api_request
}

if ( ! function_exists( 'cbxwpbookmark_doing_it_wrong' ) ) {
	/**
	 * Wrapper for _doing_it_wrong().
	 *
	 * @param  string  $function  Function used.
	 * @param  string  $message  Message to log.
	 * @param  string  $version  Version the message was added in.
	 *
	 * @since  1.0.0
	 */
	function cbxwpbookmark_doing_it_wrong( $function, $message, $version ) {
		// @codingStandardsIgnoreStart
		$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

		if ( wp_doing_ajax() || cbxwpbookmark_is_rest_api_request() ) {
			do_action( 'doing_it_wrong_run', $function, $message, $version );
			error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
		} else {
			_doing_it_wrong( $function, $message, $version );
		}
		// @codingStandardsIgnoreEnd
	}//end function cbxwpbookmark_doing_it_wrong
}

/**
 * The file that defines the custom fucntions of the plugin
 *
 *
 *
 * @link       codeboxr.com
 * @since      1.4.6
 *
 * @package    Cbxwpbookmark
 * @subpackage Cbxwpbookmark/includes
 */

if ( ! function_exists( 'cbxwpbookmark_object_types' ) ) {

	/**
	 * Return post types list, if plain is true then send as plain array , else array as post type groups
	 *
	 * @param  bool|false  $plain
	 *
	 * @return array
	 */
	function cbxwpbookmark_object_types( $plain = false ) {
		return CBXWPBookmarkHelper::post_types( $plain );
	}//end function cbxwpbookmark_object_types
}


if ( ! function_exists( 'show_cbxbookmark_btn' ) ):

	/**
	 * Returns bookmark button html markup
	 *
	 * @param  int  $object_id  post id
	 * @param  null  $object_type  post type
	 * @param  int  $show_count  if show bookmark counts
	 * @param  string  $extra_wrap_class  style css class
	 * @param  string  $skip_ids  post ids to skip
	 * @param  string  $skip_roles  user roles
	 *
	 * @return string
	 */
	function show_cbxbookmark_btn( $object_id = 0, $object_type = null, $show_count = 1, $extra_wrap_class = '', $skip_ids = '', $skip_roles = '' ) {
		return CBXWPBookmarkHelper::show_cbxbookmark_btn( $object_id, $object_type, $show_count, $extra_wrap_class, $skip_ids, $skip_roles );
	}//end function show_cbxbookmark_btn
endif;


if ( ! function_exists( 'cbxbookmark_post_html' ) ) {
	/**
	 * Returns bookmarks as per $instance attribues
	 *
	 * @param  array  $instance
	 * @param  bool  $echo
	 *
	 * @return void|string
	 */
	function cbxbookmark_post_html( $instance, $echo = false ) {
		$output = CBXWPBookmarkHelper::cbxbookmark_post_html( $instance );

		if ( $echo ) {
			echo '<ul class="cbxwpbookmark-list-generic cbxwpbookmark-mylist">' . $output . '</ul>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $output;
		}
	}//end function cbxbookmark_post_html
}


if ( ! function_exists( 'cbxbookmark_mycat_html' ) ) {
	/**
	 * Return users bookmark categories
	 *
	 * @param  array  $instance
	 * @param  bool  $echo
	 *
	 * @return void|string
	 */
	function cbxbookmark_mycat_html( $instance, $echo = false ) {
		$settings        = new CBXWPBookmarkSettings();
		$current_user_id = get_current_user_id();


		$bookmark_mode = $settings->get_field( 'bookmark_mode', 'cbxwpbookmark_basics', 'user_cat' );

		if ( $bookmark_mode == 'user_cat' || $bookmark_mode == 'global_cat' ) {
			$output = CBXWPBookmarkHelper::cbxbookmark_mycat_html( $instance );
		} else {
			$output = '<li><strong>' . esc_html__( 'Sorry, User categories or global categories can not be shown if bookmark mode is not "No Category"', 'cbxwpbookmark' ) . '</strong></li>';
		}

		if ( $echo ) {
			$create_category_html = '';
			if ( $bookmark_mode == 'user_cat' && is_user_logged_in() && ( absint( $instance['userid'] ) == $current_user_id ) ) {
				$create_category_html .= CBXWPBookmarkHelper::create_category_html( $instance );
			}

			echo $create_category_html . '<ul class="cbxwpbookmark-list-generic cbxbookmark-category-list cbxbookmark-category-list-' . esc_attr( $bookmark_mode ) . '">' . $output . '</ul>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			//echo '<ul class="cbxwpbookmark-list-generic cbxbookmark-category-list cbxbookmark-category-list-' . $bookmark_mode . '">' . $output . '</ul>';
		} else {
			return $output;
		}
	}//end function cbxbookmark_mycat_html
}

if ( ! function_exists( 'cbxbookmark_most_html' ) ) {
	/**
	 * Returns most bookmarked posts
	 *
	 * @param  array  $instance
	 * @param  array  $attr
	 * @param  bool  $echo
	 *
	 * @return void|string
	 */
	function cbxbookmark_most_html( $instance, $attr = [], $echo = false ) {
		$output = CBXWPBookmarkHelper::cbxbookmark_most_html( $instance, $attr );

		if ( $echo ) {
			echo $output; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $output;
		}
	}//end cbxbookmark_most_html
}//end exists cbxbookmark_most_html


if ( ! function_exists( 'get_author_cbxwpbookmarks_url' ) ) {
	function get_author_cbxwpbookmarks_url( $author_id = 0 ) {
		return CBXWPBookmarkHelper::get_author_cbxwpbookmarks_url( $author_id );
	}//end function get_author_cbxwpbookmarks_url

}//end exists get_author_cbxwpbookmarks_url

if ( ! function_exists( 'cbxwpbookmarks_mybookmark_page_url' ) ) {
	/**
	 * Get mybookmark page url
	 *
	 * @return false|string
	 */
	function cbxwpbookmarks_mybookmark_page_url() {
		return CBXWPBookmarkHelper::cbxwpbookmarks_mybookmark_page_url();
	}//end function cbxwpbookmarks_mybookmark_page_url
}//end exists cbxwpbookmarks_mybookmark_page_url


if ( ! function_exists( 'cbxwpbookmarks_getTotalBookmark' ) ) {
	/**
	 * Get total bookmark for any post
	 *
	 * @param $object_id
	 *
	 * @return int
	 */
	function cbxwpbookmarks_getTotalBookmark( $object_id = 0 ) {
		return CBXWPBookmarkHelper::getTotalBookmark( $object_id );
	}//end function cbxwpbookmarks_getTotalBookmark
}

if ( ! function_exists( 'cbxwpbookmarks_getTotalBookmarkByUser' ) ) {
	/**
	 * Get total bookmark for any post
	 *
	 * @param $user_id
	 *
	 * @return int
	 */
	function cbxwpbookmarks_getTotalBookmarkByUser( $user_id = 0 ) {
		return CBXWPBookmarkHelper::getTotalBookmarkByUser( $user_id );
	}//end function cbxwpbookmarks_getTotalBookmark
}

if ( ! function_exists( 'cbxwpbookmarks_getTotalBookmarkByUserByPostype' ) ) {
	/**
	 * Get total bookmark by user_id by post type
	 *
	 * @param  int  $user_id
	 * @param  string  $post_type
	 *
	 * @return int
	 */
	function cbxwpbookmarks_getTotalBookmarkByUserByPostype( $user_id = 0, $post_type = '' ) {
		return CBXWPBookmarkHelper::getTotalBookmarkByUserByPostype( $user_id, $post_type );
	}//end function cbxwpbookmarks_getTotalBookmarkByUserByPostype
}


if ( ! function_exists( 'cbxwpbookmarks_getTotalBookmarkByCategory' ) ) {
	/**
	 * Get total bookmark count for any category id
	 *
	 * @param  int  $cat_id
	 *
	 * @return int
	 */
	function cbxwpbookmarks_getTotalBookmarkByCategory( $cat_id = 0 ) {
		return CBXWPBookmarkHelper::getTotalBookmarkByCategory( $cat_id );
	}//end function cbxwpbookmarks_getTotalBookmarkByCategory
}

if ( ! function_exists( 'cbxwpbookmarks_getTotalBookmarkByCategoryByUser' ) ) {
	/**
	 * Get total bookmark count for any category id of any user
	 *
	 * @param  int  $cat_id
	 * @param  int  $user_id
	 *
	 * @return int
	 */
	function cbxwpbookmarks_getTotalBookmarkByCategoryByUser( $cat_id = 0, $user_id = 0 ) {
		return CBXWPBookmarkHelper::getTotalBookmarkByCategoryByUser( $cat_id, $user_id );
	}//end function cbxwpbookmarks_getTotalBookmarkByCategoryByUser
}

if ( ! function_exists( 'cbxwpbookmarks_isBookmarked' ) ) {
	/**
	 * Is a post bookmarked at least once
	 *
	 * @param  int  $object_id
	 *
	 * @return bool
	 */
	function cbxwpbookmarks_isBookmarked( $object_id = 0 ) {
		return CBXWPBookmarkHelper::isBookmarked( $object_id );
	}//end function cbxwpbookmarks_isBookmarked
}

if ( ! function_exists( 'cbxwpbookmarks_isBookmarkedByUser' ) ) {
	/**
	 * Is post bookmarked by user
	 *
	 * @param  int  $object_id
	 * @param  string  $user_id
	 *
	 * @return mixed
	 */
	function cbxwpbookmarks_isBookmarkedByUser( $object_id = 0, $user_id = '' ) {
		return CBXWPBookmarkHelper::isBookmarkedByUser( $object_id, $user_id );
	}//end function cbxwpbookmarks_isBookmarkedByUser
}

if ( ! function_exists( 'cbxwpbookmarks_getBookmarkCategoryById' ) ) {
	/**
	 * Get bookmark category information by id
	 *
	 * @param $catid
	 *
	 * @return array|null|object|void
	 */
	function cbxwpbookmarks_getBookmarkCategoryById( $catid = 0 ) {
		return CBXWPBookmarkHelper::getBookmarkCategoryById( $catid );
	}//end function cbxwpbookmarks_getBookmarkCategoryById
}


if ( ! function_exists( 'cbxwpbookmarks_allowed_object_type' ) ) {
	/**
	 * Returns allowed object types including custom objects
	 *
	 * @return mixed|null
	 */
	function cbxwpbookmarks_allowed_object_type() {
		return CBXWPBookmarkHelper::allowed_object_type();
	}//end method cbxwpbookmarks_allowed_object_type
}


if ( ! function_exists( 'cbxwpbookmarks_get_order_keys' ) ) {
	/**
	 * Get order keys
	 *
	 * @return string[]
	 */
	function cbxwpbookmarks_get_order_keys() {
		return CBXWPBookmarkHelper::get_order_keys();
	}//end method cbxwpbookmarks_get_order_keys
}

if ( ! function_exists( 'cbxwpbookmarks_cat_sortable_keys' ) ) {
	/**
	 * Get Category sortable keys
	 *
	 * @return string[]
	 */
	function cbxwpbookmarks_cat_sortable_keys() {
		return CBXWPBookmarkHelper::cat_sortable_keys();
	}//end method cbxwpbookmarks_cat_sortable_keys
}

if ( ! function_exists( 'cbxwpbookmarks_bookmark_sortable_keys' ) ) {
	/**
	 * Get Bookmark sortable keys
	 *
	 * @return string[]
	 */
	function cbxwpbookmarks_bookmark_sortable_keys() {
		return CBXWPBookmarkHelper::bookmark_sortable_keys();
	}//end method cbxwpbookmarks_bookmark_sortable_keys
}

if ( ! function_exists( 'cbxwpbookmarks_bookmark_most_sortable_keys' ) ) {
	/**
	 * Get Bookmark most sortable keys
	 *
	 * @return string[]
	 */
	function cbxwpbookmarks_bookmark_most_sortable_keys() {
		return CBXWPBookmarkHelper::bookmark_most_sortable_keys();
	}//end method cbxwpbookmarks_bookmark_most_sortable_keys
}

if ( ! function_exists( 'cbxwpbookmarks_getTotalBookmarkCount' ) ) {
	/**
	 * Get total bookmark count system wide
	 *
	 * @param $object_id
	 *
	 * @return int
	 * @since 1.8.0
	 */
	function cbxwpbookmarks_getTotalBookmarkCount( $object_id = 0 ) {
		return CBXWPBookmarkHelper::getTotalBookmarkCount( $object_id );
	}//end function cbxwpbookmarks_getTotalBookmarkCount
}//end if cbxwpbookmarks_getTotalBookmarkCount

if ( ! function_exists( 'cbxwpbookmarks_getTotalCategoryCount' ) ) {
	/**
	 * Get total category count system wide
	 *
	 * @param $object_id
	 *
	 * @return int
	 * @since 1.8.0
	 */
	function cbxwpbookmarks_getTotalCategoryCount( $object_id = 0 ) {
		return CBXWPBookmarkHelper::getTotalCategoryCount( $object_id );
	}//end function cbxwpbookmarks_getTotalCategoryCount
}//end if cbxwpbookmarks_getTotalCategoryCount

if ( ! function_exists( 'cbxwpbookmarks_getTotalBookmarkCountByType' ) ) {
	/**
	 * Get total bookmark count by type system wide
	 *
	 * @param $object_type
	 *
	 * @return mixed
	 */
	function cbxwpbookmarks_getTotalBookmarkCountByType( $object_type = '' ) {
		return CBXWPBookmarkHelper::getTotalBookmarkCountByType( $object_type );
	}//end function cbxwpbookmarks_getTotalBookmarkCountByType
}//end if cbxwpbookmarks_getTotalBookmarkCountByType

if ( ! function_exists( 'cbxwpbookmarks_icon_path' ) ) {
	/**
	 * Resume icon path
	 *
	 * @return mixed|null
	 * @since 1.0.0
	 */
	function cbxwpbookmarks_icon_path() {
		$directory = trailingslashit( CBXWPBOOKMARK_ROOT_PATH ) . 'assets/icons/';

		return apply_filters( 'cbxwpbookmarks_icon_path', $directory );
	}//end method cbxwpbookmarks_icon_path
}


if ( ! function_exists( 'cbxwpbookmarks_load_svg' ) ) {
	/**
	 * Load an SVG file from a directory.
	 *
	 * @param  string  $svg_name  The name of the SVG file (without the .svg extension).
	 * @param  string  $directory  The directory where the SVG files are stored.
	 *
	 * @return string|false The SVG content if found, or false on failure.
	 * @since 1.0.0
	 */
	function cbxwpbookmarks_load_svg( $svg_name = '', $folder = '' ) {
		if ( $svg_name == '' ) {
			return '';
		}


		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$credentials = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, null );
		if ( ! WP_Filesystem( $credentials ) ) {
			return ''; // Error handling here
		}

		global $wp_filesystem;


		$directory = cbxwpbookmarks_icon_path();

		// Sanitize the file name to prevent directory traversal attacks.
		$svg_name = sanitize_file_name( $svg_name );

		if ( $folder != '' ) {
			$folder = trailingslashit( $folder );
		}

		// Construct the full file path.
		$file_path = $directory . $folder . $svg_name . '.svg';
		$file_path = apply_filters( 'cbxwpbookmarks_svg_file_path', $file_path, $svg_name );

		// Check if the file exists.
		//if ( file_exists( $file_path ) && is_readable( $file_path ) ) {
		if ( $wp_filesystem->exists( $file_path ) && is_readable( $file_path ) ) {
			// Get the SVG file content.
			return $wp_filesystem->get_contents( $file_path );
		} else {
			// Return false if the file does not exist or is not readable.
			return '';
		}
	}//end method cbxwpbookmarks_load_svg
}

if ( ! function_exists( 'cbxwpbookmarks_delete_bookmark_by_type_and_object_id' ) ) {
	/**
	 * Delete bookmark by object type and object id
	 *
	 * @param $object_id
	 * @param $object_type
	 *
	 * @return void
	 */
	function cbxwpbookmarks_delete_bookmark_by_type_and_object_id( $object_id = 0, $object_type = '' ) {
		global $wpdb;

		$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';
		$object_id      = absint( $object_id );
		$object_type    = esc_attr( $object_type );
		$bookmarks      = null;

		if ( $object_id == 0 ) {
			return;
		}

		if ( $object_type != '' ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$bookmarks = $wpdb->get_results( $wpdb->prepare( "SELECT log.* FROM $bookmark_table AS log WHERE log.object_id = %d AND  log.object_type = %s;", $object_id, $object_type ), 'ARRAY_A' );
		} else {
			//todo:  Need to write the code here
		}


		if ( is_array( $bookmarks ) && sizeof( $bookmarks ) > 0 ) {
			foreach ( $bookmarks as $bookmark ) {
				$bookmark_id = absint( $bookmark['id'] );
				$object_type = esc_attr( $bookmark['object_type'] );

				do_action( 'cbxbookmark_bookmark_removed_before', $bookmark_id, $bookmark['user_id'], $object_id, $object_type );

				//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$delete_bookmark = $wpdb->delete( $bookmark_table,
					[
						'object_id'   => $object_id,
						'object_type' => $object_type,
					],
					[ '%d', '%s' ] );

				if ( $delete_bookmark !== false ) {
					do_action( 'cbxbookmark_bookmark_removed', $bookmark_id, $bookmark['user_id'], $object_id, $object_type );
				}
			}
		}
	}//end method cbxwpbookmarks_delete_bookmark_by_type_and_object_id
}

if ( ! function_exists( 'cbxwpbookmarks_delete_bookmarks' ) ) {
	/**
	 * Delete bookmark by object id and object type(optional))
	 *
	 * @param  int  $object_id
	 * @param  string  $object_type
	 */
	function cbxwpbookmarks_delete_bookmarks( $object_id, $object_type = '' ) {
		//global $wpdb;
		//$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

		$object_id = intval( $object_id );

		$object_types = CBXWPBookmarkHelper::object_types( true ); //get plain post type as array

		$bookmarks = CBXWPBookmarkHelper::getBookmarksByObject( $object_id, $object_type );

		if ( is_array( $bookmarks ) && sizeof( $bookmarks ) > 0 ) {
			foreach ( $bookmarks as $bookmark ) {
				$bookmark_id = intval( $bookmark['id'] );
				$user_id     = intval( $bookmark['user_id'] );
				$object_type = esc_attr( $bookmark['object_type'] );

				if ( ! in_array( $object_type, $object_types ) ) {
					return;
				}

				cbxwpbookmarks_delete_bookmark( $bookmark_id, $user_id, $object_id, $object_type );

				/*do_action( 'cbxbookmark_bookmark_removed_before', $bookmark_id, $user_id, $object_id, $object_type );

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$delete_bookmark = $wpdb->delete( $bookmark_table,
					[
						'object_id' => $object_id,
						'user_id'   => $user_id,
					],
					[ '%d', '%d' ] );

				if ( $delete_bookmark !== false ) {
					do_action( 'cbxbookmark_bookmark_removed', $bookmark_id, $user_id, $object_id, $object_type );
				}*/
			}
		}
	}//end cbxwpbookmarks_delete_bookmarks
}

if ( ! function_exists( ' cbxwpbookmarks_delete_bookmark' ) ) {
	/**
	 * Delete a bookmark
	 *
	 * @param $bookmark_id
	 * @param $user_id
	 * @param $object_id
	 * @param $object_type
	 * @param $category_id
	 *
	 * @return bool|int|mixed|mysqli_result|null
	 */
	function cbxwpbookmarks_delete_bookmark( $bookmark_id, $user_id, $object_id, $object_type, $category_id = 0 ) {
		global $wpdb;
		$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

		$bookmark_id = absint( $bookmark_id );
		$user_id     = absint( $user_id );
		$object_id   = absint( $object_id );
		$object_type = esc_attr( $object_type );
		$category_id = absint( $category_id );

		if ( $bookmark_id == 0 || $user_id == 0 || $object_id == 0 || $object_type == '' ) {
			return false;
		}


		do_action( 'cbxbookmark_bookmark_removed_before', $bookmark_id, $user_id, $object_id, $object_type, $category_id );

		if ( $category_id > 0 ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$delete_bookmark = $wpdb->delete( $bookmark_table,
				[
					'id'        => $bookmark_id,
					'object_id' => $object_id,
					'user_id'   => $user_id,
					'cat_id'    => $category_id,
				],
				[ '%d', '%d', '%d', '%d' ] );
		} else {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$delete_bookmark = $wpdb->delete( $bookmark_table,
				[
					'id'        => $bookmark_id,
					'object_id' => $object_id,
					'user_id'   => $user_id,
				],
				[ '%d', '%d', '%d' ] );
		}


		if ( $delete_bookmark !== false ) {
			do_action( 'cbxbookmark_bookmark_removed', $bookmark_id, $user_id, $object_id, $object_type, $category_id );
		}

		return $delete_bookmark;
	}//end method cbxwpbookmarks_delete_bookmark
}

if ( ! function_exists( 'cbxwpbookmarks_login_url_with_redirect' ) ) {
	function cbxwpbookmarks_login_url_with_redirect() {
		//$login_url          = wp_login_url();
		//$redirect_url       = '';

		if ( is_singular() ) {
			$login_url = wp_login_url( get_permalink() );
			//$redirect_url = get_permalink();
		} else {
			global $wp;
			$login_url = wp_login_url( home_url( add_query_arg( [], $wp->request ) ) );
			//$redirect_url = home_url( add_query_arg( [], $wp->request ) );
		}

		return $login_url;
	}//end function cbxwpbookmarks_login_url_with_redirect
}

if ( ! function_exists( 'cbxwpbookmark_all_caps' ) ) {
	/**
	 * All dashboard caps
	 *
	 * @return mixed|null
	 */
	function cbxwpbookmark_all_caps() {
		$all_caps = array_merge( cbxwpbookmark_log_caps(),
			cbxwpbookmark_category_caps(),
		);

		return apply_filters( 'cbxwpbookmark_all_caps', $all_caps );
	}//end function cbxwpbookmark_all_caps
}

if ( ! function_exists( 'cbxwpbookmark_event_caps' ) ) {
	/**
	 * cbxwpbookmark log component capabilities for dashboard
	 *
	 * @return mixed|null
	 */
	function cbxwpbookmark_log_caps() {
		//format: plugin_component_verb
		$caps = [
			'cbxwpbookmark_dashboard_manage',
			'cbxwpbookmark_settings_manage',
			'cbxwpbookmark_log_manage',
			'cbxwpbookmark_log_add',
			'cbxwpbookmark_log_edit',
			'cbxwpbookmark_log_delete',
		];

		return apply_filters( 'cbxwpbookmark_log_caps', $caps );
	}//end function cbxwpbookmark_log_caps
}


if ( ! function_exists( 'cbxwpbookmark_category_caps' ) ) {
	/**
	 * cbxwpbookmark category component capabilities for dashboard
	 *
	 * @return mixed|null
	 */
	function cbxwpbookmark_category_caps() {
		//format: plugin_component_verb
		$caps = [
			'cbxwpbookmark_category_manage',
			'cbxwpbookmark_category_add',
			'cbxwpbookmark_category_edit',
			'cbxwpbookmark_category_delete',
		];

		return apply_filters( 'cbxwpbookmark_category_caps', $caps );
	}//end function cbxwpbookmark_category_caps
}

if ( ! function_exists( 'cbxwpbookmark_get_user_capabilities' ) ) {
	/**
	 * Get user capabilities
	 *
	 * @return array
	 */
	function cbxwpbookmark_get_user_capabilities() {
		$wp_user = new \WP_User( get_current_user_id() );

		return $wp_user->allcaps;
	} //end function cbxwpbookmark_get_user_capabilities
}

if ( ! function_exists( 'cbxwpbookmark_get_daily_bookmark_counts' ) ) {
	/**
	 * get monthly event count
	 *
	 * @param  int  $year
	 * @param  int  $status
	 *
	 * @return array
	 */
	function cbxwpbookmark_get_daily_bookmark_counts( $year = null, $month = null, $user_id = null ): array {
		return \CBXWPBookmarkHelper::getDailyBookmarkCount( $year, $month, $user_id );
	}//end function cbxwpbookmark_get_daily_bookmark_counts
}

if ( ! function_exists( 'cbxwpbookmark_check_version_and_deactivate_plugin' ) ) {
	/**
	 * Check any plugin active, check version, if less than x then deactivate
	 *
	 * @param  string  $plugin_slug  plugin slug
	 * @param  string  $required_version  required plugin version
	 * @param  string  $transient  transient name
	 *
	 * @return bool|void
	 * @since 2.0.0
	 */
	function cbxwpbookmark_check_version_and_deactivate_plugin( $plugin_slug = '', $required_version = '', $transient = '' ) {
		if ( $plugin_slug == '' ) {
			return;
		}

		if ( $required_version == '' ) {
			return;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		// Check if the plugin is active
		if ( is_plugin_active( $plugin_slug ) ) {
			// Get the plugin data
			$plugin_data    = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_slug );
			$plugin_version = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : '';
			if ( $plugin_version == '' || is_null( $plugin_version ) ) {
				return;
			}

			// Compare the plugin version with the required version
			if ( version_compare( $plugin_version, $required_version, '<' ) ) {
				// Deactivate the plugin
				deactivate_plugins( $plugin_slug );
				if ( $transient != '' ) {
					set_transient( $transient, 1 );
				}
			}
		}

		//return false;
	}//end method cbxwpbookmark_check_version_and_deactivate_plugin
}

if ( ! function_exists( 'cbxwpbookmark_deactivate_mycred_proaddon' ) ) {
	/**
	 * If mycred pro addon activated then deactivate and add notice
	 *
	 * @return void
	 * @since 2.0.0
	 */
	function cbxwpbookmark_deactivate_mycred_proaddon() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$plugin_slug = 'cbxwpbookmarkmycred/cbxwpbookmarkmycred.php';
		if ( is_plugin_active( $plugin_slug ) ) {
			set_transient( 'cbxwpbookmark_mycredaddon_deactivated', 1 );
			deactivate_plugins( $plugin_slug );
		}

	}//end function cbxwpbookmark_deactivate_mycred_proaddon
}


if ( ! function_exists( 'cbxwpbookmark_icon_path' ) ) {
	/**
	 * event icon path
	 *
	 * @return mixed|null
	 * @since 1.0.0
	 */
	function cbxwpbookmark_icon_path() {
		$directory = trailingslashit( CBXWPBOOKMARK_ROOT_PATH ) . 'assets/icons/';

		return apply_filters( 'cbxwpbookmark_icon_path', $directory );
	}//end method cbxwpbookmark_icon_path
}

if ( ! function_exists( 'cbxwpbookmark_load_svg' ) ) {
	/**
	 * Load an SVG file from a directory.
	 *
	 * @param  string  $svg_name  The name of the SVG file (without the .svg extension).
	 * @param  string  $folder  the partial folder directory
	 *
	 * @return string|false The SVG content if found, or false on failure.
	 * @since 1.0.0
	 */
	function cbxwpbookmark_load_svg( $svg_name = '', $folder = '' ) {
		//note: code partially generated using chatgpt
		if ( $svg_name == '' ) {
			return '';
		}

		$directory = cbxwpbookmark_icon_path();

		// Sanitize the file name to prevent directory traversal attacks.
		$svg_name = sanitize_file_name( $svg_name );
		if ( $folder != '' ) {
			$folder = trailingslashit( $folder );
		}

		// Construct the full file path.
		$file_path = $directory . $folder . $svg_name . '.svg';

		$file_path = apply_filters( 'cbxwpbookmark_svg_file_path', $file_path, $svg_name );

		// Check if the file exists.
		if ( file_exists( $file_path ) && is_readable( $file_path ) ) {
			// Get the SVG file content.
			return file_get_contents( $file_path );
		} else {
			// Return false if the file does not exist or is not readable.
			return '';
		}
	}//end method cbxwpbookmark_load_svg
}

if ( ! function_exists( 'cbxwpbookmark_esc_svg' ) ) {
	/**
	 * SVG sanitizer
	 *
	 * @param  string  $svg_content  The content of the SVG file
	 *
	 * @return string|false The SVG content if found, or false on failure.
	 * @since 1.0.0
	 */
	function cbxwpbookmark_esc_svg( $svg_content = '' ) {
		// Create a new sanitizer instance
		$sanitizer = new Sanitizer();

		return $sanitizer->sanitize( $svg_content );
	}//end method cbxwpbookmark_esc_svg
}

if ( ! function_exists( 'cbxwpbookmark_decode_entities_array' ) ) {
	/**
	 * Html entity decode
	 *
	 * @param $arr
	 *
	 * @return array|string[]
	 * @since 2.0.0
	 *
	 */
	function cbxwpbookmark_decode_entities_array( $arr = [] ) {
		return array_map( function ( $v ) {
			return is_string( $v ) ? html_entity_decode( $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' ) : $v;
		}, $arr );
	}//end function cbxwpbookmark_decode_entities_array
}