<?php
namespace CBXWPBookmark;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use CBXWPBookmark\CBXWPBookmarkSettings;
//use CBXWPBookmark\Helpers\CBXWPBookmarkHelper;

/**
 * The customizer specific functionality of the plugin.
 *
 * @link       codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxwpbookmark
 * @subpackage cbxwpbookmark/includes
 */


/**
 * This class handles all shortcodes
 *
 * Class CBXWPBookmarkShortcodes
 */
class CBXWPBookmarkShortcodes {
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
	 * Constructor.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$this->version = current_time( 'timestamp' ); //for development time only
		}

		$this->settings = new CBXWPBookmarkSettings();

		global $wp_version;

		add_action( 'init', [ $this, 'init_shortcodes' ] );
	}//end of construct

	/**
	 * Init all shortcodes
	 */
	public function init_shortcodes() {
		//bookmark button using shortcode
		add_shortcode( 'cbxwpbookmarkbtn', [ $this, 'button_shortcode' ] ); //bookmark button(old)


		//show bookmark list using shortcode
		add_shortcode( 'cbxwpbookmark', [ $this, 'mybookmark_shortcode' ] ); //my bookmarks(old)


		//show bookmark categories using shortcode
		add_shortcode( 'cbxwpbookmark-mycat', [ $this, 'category_shortcode' ] ); //bookmark category(old but can be use as new)

		//show most bookmarked posts using shortcode
		add_shortcode( 'cbxwpbookmark-most', [ $this, 'most_shortcode' ] ); //bookmarked post

		//user dashboard shortcodes
		add_shortcode( 'cbxwpbookmark_user_dashboard', [ $this, 'user_dashboard_shortcode' ] );
	}//end init_shortcodes

	/**
	 * Render bookmark button - shortcode callback
	 *
	 * @param $attr
	 *
	 * @return string
	 */
	public function button_shortcode( $attr ) {
		$attr = array_change_key_case( (array) $attr, CASE_LOWER );

		// Checking Available Parameter
		global $post;

		$attr = shortcode_atts(
			[
				'object_id'        => absint( $post->ID ),
				'object_type'      => esc_attr( $post->post_type ),
				'show_count'       => 1,
				'extra_wrap_class' => '',
				'skip_ids'         => '',
				'skip_roles'       => '' //example 'administrator, editor, author, contributor, subscriber'
			], $attr, 'cbxwpbookmarkbtn' );

		$attr['object_id']        = absint( $attr['object_id'] );
		$attr['object_type']      = esc_attr( $attr['object_type'] );
		$attr['show_count']       = absint( $attr['show_count'] );
		$attr['extra_wrap_class'] = trim( esc_attr( $attr['extra_wrap_class'] ) );

		//take care skip ids and roles

		$skip_ids = trim( esc_attr( $attr['skip_ids'] ) );

		if ( $skip_ids != '' ) {
			//purify each id
			$skip_ids      = explode( ',', $skip_ids );
			$skip_ids_temp = [];
			foreach ( $skip_ids as $skip_id ) {
				$skip_ids_temp[] = absint( trim( esc_attr( $skip_id ) ) );
			}

			$skip_ids = implode( ',', array_filter( $skip_ids_temp ) );
		}

		$attr['skip_ids'] = $skip_ids;

		$skip_roles = trim( esc_attr( $attr['skip_roles'] ) );
		if ( $skip_roles != '' ) {
			//purify each role
			$skip_roles      = explode( ',', $skip_roles );
			$skip_roles_temp = [];
			$system_roles    = array_keys( \CBXWPBookmarkHelper::user_roles( true, true ) );

			foreach ( $skip_roles as $skip_role ) {
				$skip_role = trim( esc_attr( $skip_role ) );
				if ( in_array( $skip_role, $system_roles ) ) {
					$skip_roles_temp[] = $skip_role;
				}
			}

			$skip_roles = implode( ',', $skip_roles_temp );
		}

		$attr['skip_roles'] = $skip_roles;

		extract( $attr );

		return show_cbxbookmark_btn( $object_id, $object_type, $show_count, $extra_wrap_class, $skip_ids, $skip_roles );
	}//end button_shortcode

	/**
	 * Bookmarked Posts shortcode callback
	 *
	 * @param $attr
	 *
	 * @return string
	 */
	public function mybookmark_shortcode( $attr ) {
		$delete_svg = cbxwpbookmarks_load_svg( 'icon_delete' );
		$share_svg  = cbxwpbookmarks_load_svg( 'icon_share' );

		$attr = array_change_key_case( (array) $attr, CASE_LOWER );

		// Checking Available Parameter
		global $wpdb;
		$bookmark_table = esc_sql($wpdb->prefix . 'cbxwpbookmark');
		$category_table = esc_sql($wpdb->prefix . 'cbxwpbookmarkcat');


		$settings       = $this->settings;
		$bookmark_mode = $settings->get_field( 'bookmark_mode', 'cbxwpbookmark_basics', 'user_cat' );

		$current_user_id = get_current_user_id();

		$attr = shortcode_atts(
			[
				'title'          => esc_html__( 'All Bookmarks', 'cbxwpbookmark' ),
				'order'          => 'DESC',
				'orderby'        => 'id', //id, object_id, object_type, title
				'limit'          => 10,
				'type'           => '', //post or object type, multiple post type in comma
				'catid'          => '', //category id
				'loadmore'       => 1,  //this is shortcode only params
				'cattitle'       => 1,  //show category title,
				'catcount'       => 1,  //show item count per category
				'allowdelete'    => 0,
				'allowdeleteall' => 0,
				'showshareurl'   => 1,
				'base_url'       => esc_url( cbxwpbookmarks_mybookmark_page_url() ),
				'userid'         => absint( $current_user_id ),
				'offset'         => 0
			], $attr, 'cbxwpbookmark' );

		$attr['title'] = $title = esc_attr( sanitize_text_field( $attr['title'] ) );

		$attr['limit']        = absint( $attr['limit'] );
		$attr['catid']        = absint( $attr['catid'] );
		$attr['cattitle']     = absint( $attr['cattitle'] );
		$attr['catcount']     = absint( $attr['catcount'] );
		$attr['allowdelete']  = absint( $attr['allowdelete'] );
		$attr['showshareurl'] = absint( $attr['showshareurl'] );
		$attr['offset']       = absint( $attr['offset'] );
		$attr['base_url']     = esc_url( $attr['base_url'] );

		$types               = array_map( 'trim', explode( ',', esc_attr( $attr['type'] ) ) );
		$types               = array_filter( $types );
		$allowed_object_type = cbxwpbookmarks_allowed_object_type();
		$attr['type']        = array_intersect( $types, $allowed_object_type );

		$order_by = trim( esc_attr( $attr['orderby'] ) );
		if ( ! in_array( $order_by, cbxwpbookmarks_bookmark_sortable_keys() ) ) {
			$order_by = 'id';
		}
		$attr['orderby'] = $order_by;


		$order      = strtoupper( esc_attr( $attr['order'] ) );
		$order_keys = cbxwpbookmarks_get_order_keys();
		if ( ! in_array( $order, $order_keys ) ) {
			$order = 'DESC';
		}
		$attr['order'] = $order;

		//if the url has cat id (cbxbmcatid get param) thenm use it or try it from shortcode
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$attr['catid'] = ( isset( $_GET['cbxbmcatid'] ) && $_GET['cbxbmcatid'] != null ) ? absint( sanitize_text_field( wp_unslash( $_GET['cbxbmcatid'] ) ) ) : esc_attr( $attr['catid'] );

		if ( absint( $attr['catid'] ) == 0 || $attr['catid'] == '0' ) {
			$attr['catid'] = '';
		}//compatibility with previous shortcode default values


		$attr['catid'] = array_filter( array_map( 'absint', explode( ',', esc_attr( $attr['catid'] ) ) ) );


		$userid_temp = $attr['userid'];

		//let's find out if the userid is email or username
		if ( is_email( $userid_temp ) ) {
			//possible email
			$user_temp = get_user_by( 'email', $userid_temp );

			if ( $user_temp !== false ) {
				$userid_temp = absint( $user_temp->ID );

				if ( $userid_temp > 0 ) {
					$attr['userid'] = $userid_temp;
				}
			} else {
				//email but user not found so reset it to guest
				$attr['userid'] = 0;
			}

		} elseif ( ! is_numeric( $userid_temp ) ) {
			if ( ( $user_temp = get_user_by( 'login', $userid_temp ) ) !== false ) {
				//user_login
				$userid_temp = absint( $user_temp->ID );
				if ( $userid_temp > 0 ) {
					$attr['userid'] = absint( $userid_temp );
				}
			} elseif ( ( $user_temp = get_user_by( 'slug', $userid_temp ) ) !== false ) {
				//user_login
				$userid_temp = absint( $user_temp->ID );
				if ( $userid_temp > 0 ) {
					$attr['userid'] = absint( $userid_temp );
				}
			} else {
				$attr['userid'] = 0;
			}
		}


		//get userid from url linked from other page
		//if ( isset( $_GET['userid'] ) && absint( $_GET['userid'] ) > 0 ) {

		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['userid'] ) ) {
			$userid_temp = wp_unslash( $_GET['userid'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( is_numeric( $userid_temp ) ) {
				//if user id is used
				$attr['userid'] = absint( $userid_temp );
			} elseif ( ( $user_temp = get_user_by( 'login', $userid_temp ) ) !== false ) {
				//user_login
				$userid_temp = absint( $user_temp->ID );
				if ( $userid_temp > 0 ) {
					$attr['userid'] = absint( $userid_temp );
				}
			} elseif ( ( $user_temp = get_user_by( 'slug', $userid_temp ) ) !== false ) {
				//user_login
				$userid_temp = absint( $user_temp->ID );
				if ( $userid_temp > 0 ) {
					$attr['userid'] = absint( $userid_temp );
				}
			} else {
				$attr['userid'] = 0;
			}
		}

		//userswp.io plugin integration
		if ( function_exists( 'is_uwp_profile_page' ) && is_uwp_profile_page() ) {
			if ( is_uwp_profile_page() ) {
				$displayed_user = uwp_get_displayed_user();
				$attr['userid'] = $displayed_user->ID;
			}
		}
		//end userswp.io plugin integration

		//ultimatemember plugin integration
		if ( function_exists( 'UM' ) ) {
			$profile_id = absint( UM()->options()->get( 'core_user' ) );
			if ( is_page( $profile_id ) ) {
				$user_id        = um_profile_id();
				$attr['userid'] = $user_id;
			}
		}
		//end ultimatemember plugin integration


		$attr['userid'] = absint( $attr['userid'] );

		//determine if we allow user to delete
		$allow_delete_all_html  = '';
		$attr['allowdeleteall'] = $allow_delete_all = absint( $attr['allowdeleteall'] );
		if ( $allow_delete_all && is_user_logged_in() && $attr['userid'] == $current_user_id ) {
			//$allow_delete_all      = 1;
			$allow_delete_all_html = '<a title="' . esc_attr__( 'Click to delete all', 'cbxwpbookmark' ) . '" role="button" data-list="1" data-busy="0" class="cbxbookmark-btn cbxwpbookmark_deleteall cbxwpbookmark_deleteall_list no-underline icon icon-right ld-ext-right" href="#"><i class="cbx-icon cbx-icon-15">' . $delete_svg . '</i><i class="button-label">' . esc_html__( 'Delete All', 'cbxwpbookmark' ) . '</i><i class="ld ld-ring ld-spin"></i></a>';
		}


		extract( $attr );

		$limit = intval( $limit );
		if ( $limit == 0 ) {
			$limit = 10;
		}


		$show_loadmore_html = '';


		$privacy = 2; //all
		if ( $userid == 0 || ( $userid != get_current_user_id() ) ) {
			$privacy     = 1;
			$allowdelete = 0;

			$attr['privacy']     = $privacy;
			$attr['allowdelete'] = $allowdelete;
		}


		//$category_privacy_sql = '';
		$cat_sql  = '';
		$type_sql = '';


		if ( $bookmark_mode != 'no_cat' ) {
			if ( is_array( $catid ) && sizeof( $catid ) > 0 ) {
				$cats_ids_placeholders = implode( ',', array_fill( 0, count( $catid ), '%d' ) );
				/* phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare */
				$cat_sql = $wpdb->prepare( "AND cat_id IN ({$cats_ids_placeholders})", $catid );
			} else {
				if ( $bookmark_mode == 'user_cat' ) {
					//same user seeing
					if ( $privacy != 2 ) {
						//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
						$cats = $wpdb->get_results( $wpdb->prepare( "SELECT *  FROM  {$category_table} WHERE user_id = %d AND privacy = %d", $userid, intval( $privacy ) ), ARRAY_A );
					} else {
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
						$cats = $wpdb->get_results( $wpdb->prepare( "SELECT *  FROM  {$category_table} WHERE user_id = %d", $userid ), ARRAY_A );
					}

					$cats_ids = [];
					if ( is_array( $cats ) && sizeof( $cats ) > 0 ) {
						foreach ( $cats as $cat ) {
							$cats_ids[] = intval( $cat['id'] );
						}

						$cats_ids_placeholders = implode( ',', array_fill( 0, count( $cats_ids ), '%d' ) );
						// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, PluginCheck.Security.DirectDB.UnescapedDBParameter
						$cat_sql = $wpdb->prepare( "AND cat_id IN ({$cats_ids_placeholders})", $cats_ids );
					}
				}
			}
		}


		$total_count = 0;
		$total_page  = 1;


		if ( $limit > 0 ) {
			if ( sizeof( $type ) > 0 ) {
				$type_placeholders = implode( ',', array_fill( 0, count( $type ), '%s' ) );
				/* phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare */
				$type_sql = $wpdb->prepare( "AND object_type IN ({$type_placeholders})", $type );
			}


			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$total_count = intval( $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM (select count(*) as totalobject FROM $bookmark_table  WHERE user_id = %d $cat_sql $type_sql group by object_id) AS TotalData", $userid ) ) );
			$total_page  = ( $total_count > 0 ) ? ceil( $total_count / $limit ) : 1;
		}


		$extra_css_class = '';
		if ( $attr['loadmore'] == 1 && $total_page > 1 ) {
			$extra_css_class = 'cbxwpbookmark-mylist-sc-more';
			$offset          += $limit;

			$show_loadmore_html = '<p class="cbxbookmark-more-wrap"><a data-busy="0" href="#" class="cbxbookmark-more ld-ext-right" data-cattitle="' . esc_attr( $cattitle ) . '" data-order="' . esc_attr( $order ) . '" data-orderby="' . esc_attr( $order_by ) . '"  data-userid="' . absint( $userid ) . '" data-limit="' . absint( $limit ) . '" data-offset="' . absint( $offset ) . '" data-catid="' . implode( ',', $catid ) . '" data-type="' . implode( ',',
					$type ) . '" data-totalpage="' . absint( $total_page ) . '" data-currpage="1" data-allowdelete="' . absint( $allowdelete ) . '">' . esc_html__( 'Load More', 'cbxwpbookmark' ) . '<i class="ld ld-ring ld-spin"></i></a></p>';
		}


		//if only bookmark mode is user or global cat
		if ( intval( $cattitle ) && $bookmark_mode != 'no_cat' ) {
			if ( sizeof( $catid ) == 1 ) {
				$cat_info = \CBXWPBookmarkHelper::getBookmarkCategoryById( reset( $catid ) );

				if ( is_array( $cat_info ) && sizeof( $cat_info ) > 0 ) {
					$cat_count_html = '';
					if ( $catcount ) {
						$cat_bookmark_count = \CBXWPBookmarkHelper::getTotalBookmarkByCategory( reset( $catid ) );
						$cat_count_html     = '<i>(' . number_format_i18n( $cat_bookmark_count ) . ')</i>';
					}

					$title = wp_unslash( $cat_info['cat_name'] ) . $cat_count_html;
				}
			}
		}


		$share_url_html = '';

		if ( $attr['showshareurl'] ) {
			$share_url      = \CBXWPBookmarkHelper::myBookmarksShareUrl( $attr );
			$share_url_html = '<a title="' . esc_attr__( 'Share bookmarks with others', 'cbxwpbookmark' ) . '" class="cbxbookmark-btn cbxwpbookmark_share no-underline icon icon-right" href="' . esc_url( $share_url ) . '"><i class="cbx-icon cbx-icon-15">' . $share_svg . '</i><i class="button-label">' . esc_html__( 'Share', 'cbxwpbookmark' ) . '</i></a>';
		}


		$title_html = $title . $allow_delete_all_html . $share_url_html;


		if ( $title_html != '' ) {
			$title = '<h3 class="cbxwpbookmark-title cbxwpbookmark-title-postlist">' . $title_html . '</h3>';
		}

		return '<div class="cbxwpbookmark-mylist-wrap">' . $title . '<ul class="cbxwpbookmark-list-generic cbxwpbookmark-mylist cbxwpbookmark-mylist-sc ' . $extra_css_class . '" >' . cbxbookmark_post_html( $attr ) . '</ul>' . $show_loadmore_html . '</div>';
	}//end mybookmark_shortcode

	/**
	 * Shows any user's bookmarked categories using shortcode
	 *
	 * @param $attr
	 *
	 * @return string
	 */
	public function category_shortcode( $attr ) {
		$attr = array_change_key_case( (array) $attr, CASE_LOWER );

		$settings       = new CBXWPBookmarkSettings();
		$bookmark_mode = $settings->get_field( 'bookmark_mode', 'cbxwpbookmark_basics', 'user_cat' );

		$current_user_id = get_current_user_id();

		$attr = shortcode_atts(
			[
				'title'          => esc_html__( 'Bookmark Categories', 'cbxwpbookmark' ),
				//if empty title will not be shown
				'order'          => 'ASC',
				//DESC, ASC
				'orderby'        => 'cat_name',
				//other possible values  id, cat_name, privacy
				'privacy'        => 2,
				//1 = public 0 = private  2= ignore
				'display'        => 0,
				//0 = list  1= dropdown,
				'show_count'     => 0,
				'allowedit'      => 0,
				'show_bookmarks' => 0,
				//show bookmark as sublist on click on category
				'userid'         => $current_user_id,
				'base_url'       => cbxwpbookmarks_mybookmark_page_url()
			], $attr, 'cbxwpbookmark-mycat'
		);


		$attr['title']          = esc_attr( $attr['title'] );
		$attr['privacy']        = absint( $attr['privacy'] );
		$attr['display']        = absint( $attr['display'] );
		$attr['show_count']     = absint( $attr['show_count'] );
		$attr['allowedit']      = absint( $attr['allowedit'] );
		$attr['show_bookmarks'] = absint( $attr['show_bookmarks'] );
		$attr['base_url']       = esc_url( $attr['base_url'] );

		$order    = strtoupper( trim( esc_attr( $attr['order'] ) ) );
		$order_by = trim( esc_attr( $attr['orderby'] ) );

		$orders_allowed = \CBXWPBookmarkHelper::get_order_keys();
		if ( ! in_array( $order, $orders_allowed ) ) {
			$attr['order'] = $order = 'ASC';
		}

		$sort_allowed = \CBXWPBookmarkHelper::cat_sortable_keys();
		if ( ! in_array( $order_by, $sort_allowed ) ) {
			$attr['orderby'] = $order_by = 'cat_name';
		}


		$userid_temp = $attr['userid'];

		//let's find out if the userid is email or username
		if ( is_email( $userid_temp ) ) {
			$user_temp = get_user_by( 'email', $userid_temp );
			if ( $user_temp !== false ) {
				$userid_temp = absint( $user_temp->ID );
				if ( $userid_temp > 0 ) {
					$attr['userid'] = $userid_temp;
				}
			} else {
				//email but user not found so reset it to guest
				$attr['userid'] = 0;
			}

		} elseif ( ! is_numeric( $userid_temp ) ) {
			if ( ( $user_temp = get_user_by( 'login', $userid_temp ) ) !== false ) {
				//user_login
				$userid_temp = absint( $user_temp->ID );
				if ( $userid_temp > 0 ) {
					$attr['userid'] = absint( $userid_temp );
				}
			} elseif ( ( $user_temp = get_user_by( 'slug', $userid_temp ) ) !== false ) {
				//user_login
				$userid_temp = absint( $user_temp->ID );
				if ( $userid_temp > 0 ) {
					$attr['userid'] = absint( $userid_temp );
				}
			} else {
				$attr['userid'] = 0;
			}
		}


		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['userid'] ) ) {
			$userid_temp = wp_unslash( $_GET['userid'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( is_numeric( $userid_temp ) ) {
				//if user id is used
				$attr['userid'] = absint( $userid_temp );
			} elseif ( ( $user_temp = get_user_by( 'login', $userid_temp ) ) !== false ) {
				//user_login
				$userid_temp = absint( $user_temp->ID );
				if ( $userid_temp > 0 ) {
					$attr['userid'] = absint( $userid_temp );
				}
			} elseif ( ( $user_temp = get_user_by( 'slug', $userid_temp ) ) !== false ) {
				//user_login
				$userid_temp = absint( $user_temp->ID );
				if ( $userid_temp > 0 ) {
					$attr['userid'] = absint( $userid_temp );
				}
			} else {
				$attr['userid'] = 0;
			}
		}

		//userswp.io plugin integration
		if ( function_exists( 'is_uwp_profile_page' ) && is_uwp_profile_page() ) {
			if ( is_uwp_profile_page() ) {
				$displayed_user = uwp_get_displayed_user();
				$attr['userid'] = $displayed_user->ID;
			}
		}//end userswp.io plugin integration

		//ultimatemember plugin integration
		if ( function_exists( 'UM' ) ) {
			$profile_id = absint( UM()->options()->get( 'core_user' ) );
			if ( is_page( $profile_id ) ) {
				$user_id        = um_profile_id();
				$attr['userid'] = $user_id;
			}
		}//end ultimatemember plugin integration

		$attr['userid'] = absint( $attr['userid'] );

		$output = '';

		//if other than no_cat mode we will have category
		if ( $bookmark_mode != 'no_cat' ) {
			$output .= '<div class="cbxbookmark-category-list-wrap">';
			if ( $attr['title'] != '' ) {
				$output .= '<h3 class="cbxwpbookmark-title cbxwpbookmark-title-mycat">' . esc_html( $attr['title'] ) . '</h3>';
			}

			$create_category_html = '';

			if ( $bookmark_mode == 'user_cat' && is_user_logged_in() && ( absint( $attr['userid'] ) == $current_user_id ) ) {
				$create_category_html .= \CBXWPBookmarkHelper::create_category_html( $attr );
			}

			$output .= ( intval( $attr['display'] ) == 0 ) ? $create_category_html : '';
			$output .= ( intval( $attr['display'] ) == 0 ) ? '<ul class="cbxwpbookmark-list-generic cbxbookmark-category-list cbxbookmark-category-list-' . esc_attr( $bookmark_mode ) . ' cbxbookmark-category-list-sc">' : '';
			$output .= cbxbookmark_mycat_html( $attr );
			$output .= ( intval( $attr['display'] ) == 0 ) ? '</ul>' : '';
			$output .= '</div>';
		} else {
			//this message is better to hide
			$output .= '<strong>' . esc_html__( 'Sorry, This widget is not compatible as per setting. This widget can be used only if bookmark mode is "User owns category" or "Global Category"', 'cbxwpbookmark' ) . '</strong>';
		}

		return $output;
	}//end category_shortcode

	/**
	 * Most bookmarked post shortcode
	 *
	 * @param $attr
	 *
	 * @return string
	 */
	public function most_shortcode( $attr ) {
		$attr = array_change_key_case( (array) $attr, CASE_LOWER );

		$attr = shortcode_atts(
			[
				'title'      => esc_html__( 'Most Bookmarked Posts', 'cbxwpbookmark' ),
				//if empty title will not be shown
				'order'      => 'DESC',
				'orderby'    => 'object_count',
				//id, object_id, object_type, object_count, title
				'limit'      => 10,
				'type'       => '',
				//db col name object_type,  post types eg, post, page, any custom post type, for multiple comma separated
				'daytime'    => 0,
				// 0 means all time,  any numeric values as days
				'show_count' => 1,
				'show_thumb' => 1,
				'ul_class'   => '',
				'li_class'   => ''
			], $attr, 'cbxwpbookmark-most' );


		$title      = esc_attr( $attr['title'] );
		$limit      = absint( $attr['limit'] );
		$daytime    = absint( $attr['daytime'] );
		$show_count = absint( $attr['show_count'] );
		$show_thumb = absint( $attr['show_thumb'] );

		$order    = strtoupper( trim( esc_attr( $attr['order'] ) ) );
		$order_by = trim( esc_attr( $attr['orderby'] ) );

		$orders_allowed = \CBXWPBookmarkHelper::get_order_keys();
		if ( ! in_array( $order, $orders_allowed ) ) {
			$attr['order'] = $order = 'DESC';
		}

		$sort_allowed = \CBXWPBookmarkHelper::bookmark_most_sortable_keys();
		if ( ! in_array( $order_by, $sort_allowed ) ) {
			$attr['orderby'] = $order_by = 'object_count';
		}

		$style_attr = [
			'ul_class' => esc_attr( $attr['ul_class'] ),
			'li_class' => esc_attr( $attr['li_class'] )
		];


		$allowed_object_types = cbxwpbookmarks_allowed_object_type();
		$types                = esc_attr( trim( $attr['type'] ) );
		$types                = explode( ',', $types );
		$types                = array_map( 'trim', $types );
		$types                = array_map( 'esc_attr', $types );
		$attr['type']         = array_intersect( $types, $allowed_object_types );


		return '<div class="cbxwpbookmark-mostlist-wrap">' . cbxbookmark_most_html( $attr, $style_attr ) . '</div>';
	}//end most_shortcode

	/**
	 * User dashboard shortcode
	 *
	 * @param $atts
	 *
	 * @return false|string|void
	 */
	public function user_dashboard_shortcode( $atts = [] ) {
		$settings = $this->settings;

		wp_enqueue_style( 'cbxwpbookmark-public' );

		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts = shortcode_atts( [
			'components' => '',
			'init_menu'  => ''
		], $atts, 'cbxwpbookmark_user_dashboard' );

        $atts = cbxwpbookmark_decode_entities_array($atts);

		$components                = array_filter( explode( ',', $atts['components'] ) );
		$components_in_use         = [];
		$init_menu                 = $atts['init_menu'];
		$user_dashboard_menus = apply_filters( 'cbxwpbookmark_user_dashboard_menus', [] );

		ob_start();
		?>
        <div class="cbx-chota cbxwpbookmark-frontend-manager-wrapper" id="cbxwpbookmark-user_dashboard">
			<?php
			if ( is_user_logged_in() ) {

				$current_url       = get_the_permalink();
				$current_component = isset( $_REQUEST['component'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['component'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended 

				$output_navs     = '<ul class="cbxwpbookmark-dashboard-menu">';
				$output_contents = '<div id="cbxwpbookmark-user_dashboard_contents">';

				$navs = [];

				foreach ( $user_dashboard_menus as $key => $menu ) {
					if ( sizeof( $components ) != 0 && ! in_array( $key, $components ) ) {
						continue;
					}

					$components_in_use[] = $key;
					$menu_url = add_query_arg( 'component', $key, $current_url );

					//$output_navs .= '<li class=""><a href="' . esc_url( $menu_url ) . '">' . esc_attr( $menu['title'] ) . '</a> </li>';

					$navs[ $key ] = [
						'url'   => $menu_url,
						'title' => $menu['title']
					];
				}


				if ( $current_component == '' ) {
					if ( $init_menu != '' && in_array( $init_menu, $components_in_use ) ) {
						$current_component = $init_menu;
					} else {
						$current_component = isset( $components_in_use[0] ) ? $components_in_use[0] : '';
					}
				}


				foreach ( $navs as $nav_key => $nav ) {
					$active_class = '';
					if ( $nav_key == $current_component ) {
						$active_class = 'active';
					}
					$output_navs .= '<li class="' . esc_attr( $active_class ) . '"><a href="' . esc_url( $nav['url'] ) . '">' . esc_attr( $nav['title'] ) . '</a> </li>';
				}


				$output_callback = '';


				if ( $current_component != '' ) {
					if ( isset( $user_dashboard_menus[ $current_component ] ) && $user_dashboard_menus[ $current_component ] ) {
						// get selected user dashboard menus data
						$user_dashboard_menu = $user_dashboard_menus[ $current_component ];
						if ( $user_dashboard_menu['type'] == 'shortcode' ) {
							if ( is_string( $user_dashboard_menu['callback'] ) ) {
								$output_callback = do_shortcode( $user_dashboard_menu['callback'] );
							}
						} else {
							if ( is_callable( $user_dashboard_menu['callback'] ) ) {
								$output_callback = call_user_func( $user_dashboard_menu['callback'] );
							}

						}
					}
				}



				$output_contents .= '<div class="user_dashboard_content user_dashboard_content_' . esc_attr( $current_component ) . ' active">' . $output_callback . '</div>';

				$output_navs     .= '</ul>';
				$output_contents .= '</div>';

				echo $output_navs . $output_contents; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				$show_login = $settings->get_field( 'show_login_form', 'cbxwpbookmark_general','no' );

				if($show_login == 'yes' ){
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo  cbxwpbookmark_get_template_html( 'global/login_form.php', [
						'settings' => $settings,
					] );
				}
				else{
					
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo cbxwpbookmark_get_template_html( 'global/login_url.php', [
						'settings' => $settings,
					] );
				}
			}
			?>
        </div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}//end method user_dashboard_shortcode
}//end class CBXWPBookmarkShortcodes