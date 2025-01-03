<?php

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
		$settings_api    = new CBXWPBookmark_Settings_API();
		$current_user_id = get_current_user_id();


		$bookmark_mode = $settings_api->get_option( 'bookmark_mode', 'cbxwpbookmark_basics', 'user_cat' );

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
	function cbxwpbookmarks_load_svg( $svg_name = '' ) {
		//note: code partially generated using chatgpt
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

		// Construct the full file path.
		$file_path = $directory . $svg_name . '.svg';
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
	function cbxwpbookmarks_delete_bookmark( $bookmark_id, $user_id, $object_id, $object_type ) {
		global $wpdb;
		$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

		$bookmark_id = absint( $bookmark_id );
		$user_id     = absint( $user_id );
		$object_id   = absint( $object_id );
		$object_type = esc_attr( $object_type );

		if ( $bookmark_id == 0 || $user_id == 0 || $object_id == 0 || $object_type == '' ) {
			return;
		}


		do_action( 'cbxbookmark_bookmark_removed_before', $bookmark_id, $user_id, $object_id, $object_type );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$delete_bookmark = $wpdb->delete( $bookmark_table,
			[
				'object_id' => $object_id,
				'user_id'   => $user_id,
			],
			[ '%d', '%d' ] );

		if ( $delete_bookmark !== false ) {
			do_action( 'cbxbookmark_bookmark_removed', $bookmark_id, $user_id, $object_id, $object_type );
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