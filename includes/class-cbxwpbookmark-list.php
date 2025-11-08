<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Cbx\Bookmark\CBXWPBookmarkSettings;
//use Cbx\Bookmark\Helpers\CBXWPBookmarkHelper;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Bookmark listing class for admin side
 */
class CBXWPBookmark_List_Table extends WP_List_Table {

	/**
	 * The settings api of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $settings settings api of this plugin.
	 */
	private $settings;

	/**
	 * The current list of all branches.
	 *
	 * @since  3.1.0
	 * @access public
	 * @var array
	 */
	function __construct( $args = [] ) {

		$this->settings = new CBXWPBookmarkSettings();
		//Set parent defaults
		parent::__construct( [
			'singular' => 'cbxwpbookmarklist',     //singular name of the listed records
			'plural'   => 'cbxwpbookmarklists',    //plural name of the listed records
			'ajax'     => false,                   //does this table support ajax?
			'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
		] );
	}

	/**
	 * Callback for column 'id'
	 *
	 * @param  array  $item
	 *
	 * @return string
	 */
	function column_id( $item ) {
		//return '<a href="' . admin_url( 'admin.php?page=cbxwpbookmark&view=view&id=' . $item['id'] ) . '" title="' . esc_html__( 'View Review', 'cbxwpbookmark' ) . '">' . $item['id'] . '</a>' . ' (<a target="_blank" href="' . admin_url( 'admin.php?page=cbxwpbookmark&view=addedit&id=' . $item['id'] ) . '" title="' . esc_html__( 'Edit Review', 'cbxwpbookmark' ) . '">' . esc_html__( 'Edit', 'cbxwpbookmark' ) . '</a>)';

		return intval( $item['id'] );
	}//end column-id


	/**
	 * Callback for column 'object_id'
	 *
	 * @param  array  $item
	 *
	 * @return string
	 */
	function column_object_id( $item ) {
		$post_id     = $object_id = intval( $item['object_id'] );
		$object_type = esc_attr( $item['object_type'] );
		$settings     = new CBXWPBookmarkSettings();;
		$enable_buddypress_bookmark = intval( $settings->get_field( 'enable_buddypress_bookmark', 'cbxwpbookmark_proaddon', 0 ) );

		$object_types = CBXWPBookmarkHelper::object_types( true ); //get plain post type as array

		$edit_link = '';

		if ( in_array( $object_type, $object_types ) ) {
			$post_title = wp_strip_all_tags( get_the_title( intval( $post_id ) ) );
			$post_title = ( $post_title == '' ) ? esc_html__( 'Untitled article', 'cbxwpbookmark' ) : $post_title;
			$edit_link  = '<a target="_blank" href="' . get_permalink( $post_id ) . '">' . esc_html( $post_title ) . '</a>';

			$edit_url = esc_url( get_edit_post_link( $post_id ) );
			if ( ! is_null( $edit_url ) ) {
				$edit_link .= ' - <a target="_blank" href="' . $edit_url . '" target="_blank" title="' . esc_html__( 'Edit Post', 'cbxwpbookmark' ) . '">' . $post_id . '</a>';
			}

			return $post_id . ' - ' . $edit_link;
		} elseif ( $enable_buddypress_bookmark && $object_type == 'buddypress_activity' && function_exists( 'bp_activity_get' ) ) {

			//$activity_get = bp_activity_get_specific( array( 'activity_ids' => array($object_id) ) );
			$args = [
				//'ids' => $object_id,
				'in'       => $post_id,
				'per_page' => 1
			];

			$activity_get = bp_activity_get( $args );


			if ( isset( $activity_get['activities'][0] ) ) {
				$activity = $activity_get['activities'][0];

				$content = wp_strip_all_tags( $activity->content );
				$content = ( $content != '' ) ? $content : esc_html__( 'buddyPress Activity', 'cbxwpbookmark' );

				$edit_link = '<a target="_blank" href="' . bp_activity_get_permalink( $post_id ) . '">' . $content . '</a>';

				return $post_id . ' - ' . $edit_link;
			}
		}

		$edit_link = apply_filters( 'cbxwpbookmark_dashboard_listing_editlink', $edit_link, $object_id, $object_type );

		if ( $edit_link == '' ) {
			return $post_id . ' - ' . esc_html__( 'Untitled article', 'cbxwpbookmark' );
		} else {
			return $post_id . ' - ' . $edit_link;
		}
	}//end column_object_id

	/**
	 * Callback for column 'object_type'
	 *
	 * @param  array  $item
	 *
	 * @return string
	 */
	function column_object_type( $item ) {
		return esc_attr( $item['object_type'] );
	}//end column_object_type

	/**
	 * Callback for column 'User'
	 *
	 * @param  array  $item
	 *
	 * @return string
	 */
	function column_user_id( $item ) {
		$user_id = absint( $item['user_id'] );

		$user_html = $user_id;

		if ( current_user_can( 'edit_user', $user_id ) ) {
			$user_html = '<a href="' . get_edit_user_link( $user_id ) . '" target="_blank" title="' . esc_html__( 'Edit User', 'cbxwpbookmark' ) . '">' . $user_id . '</a>';
		}


		return $user_html;
	}//end column_user_id

	/**
	 * Callback for column 'cat_id
	 *
	 * @param  array  $item
	 *
	 * @return string
	 */
	function column_cat_id( $item ) {
		$cat_id = intval( $item['cat_id'] );
		if ( $cat_id > 0 ) {
			return '<a title="' . esc_html__( 'Click to edit category', 'cbxwpbookmark' ) . '" href="' . admin_url( 'admin.php?page=cbxwpbookmark-cats&view=edit&id=' . intval( $cat_id ) ) . '">' . esc_attr( wp_unslash( $item['cat_name'] ) ) . '</a>';
		} else {
			return esc_html__( 'N/A', 'cbxwpbookmark' );
		}


	}//end column_object_type


	/**
	 * Callback for column 'Date Created'
	 *
	 * @param  array  $item
	 *
	 * @return string
	 */
	function column_created_date( $item ) {
		$created_date = '';
		if ( $item['created_date'] != '0000-00-00 00:00:00' ) {
			$created_date = CBXWPBookmarkHelper::dateReadableFormat( stripslashes( $item['created_date'] ) );
		}

		return $created_date;
	}//end column_created_date

	/**
	 * Callback for column 'Date Created'
	 *
	 * @param  array  $item
	 *
	 * @return string
	 */
	function column_modyfied_date( $item ) {
		$created_date = '';
		if ( $item['modyfied_date'] != '0000-00-00 00:00:00' ) {
			$created_date = CBXWPBookmarkHelper::dateReadableFormat( stripslashes( $item['modyfied_date'] ) );
		}

		return $created_date;
	}//end column_modyfied_date

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/
			$this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
			/*$2%s*/
			$item['id']                //The value of the checkbox should be the record's id
		);
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
				return $item[ $column_name ];
			case 'object_id':
				return $item[ $column_name ];
			case 'object_type':
				return $item[ $column_name ];
			case 'user_id':
				return $item[ $column_name ];
			case 'cat_id':
				return $item[ $column_name ];
			case 'created_date':
				return $item[ $column_name ];
			default:
				//return print_r( $item, true ); //Show the whole array for troubleshooting purposes
				return apply_filters( 'cbxwpbookmark_list_admin_column_default', $item, $column_name );
		}
	}//end column_default

	function get_columns() {
		$columns = [
			'cb'           => '<input type="checkbox" />', //Render a checkbox instead of text
			'id'           => esc_html__( 'ID', 'cbxwpbookmark' ),
			'object_id'    => esc_html__( 'Post', 'cbxwpbookmark' ),
			'object_type'  => esc_html__( 'Post Type', 'cbxwpbookmark' ),
			'user_id'      => esc_html__( 'User', 'cbxwpbookmark' ),
			'cat_id'       => esc_html__( 'Category', 'cbxwpbookmark' ),
			'created_date' => esc_html__( 'Created', 'cbxwpbookmark' ),
			//'modyfied_date' => esc_html__( 'Modified', 'cbxwpbookmark' )
		];

		return apply_filters( 'cbxwpbookmark_list_admin_columns', $columns );
	}//end get_columns


	function get_sortable_columns() {
		$sortable_columns = [
			'id'           => [ 'logs.id', false ], //true means it's already sorted
			'object_id'    => [ 'logs.object_id', false ],
			'object_type'  => [ 'logs.object_type', false ],
			'user_id'      => [ 'logs.user_id', false ],
			'cat_id'       => [ 'logs.cat_id', false ],
			'created_date' => [ 'logs.created_date', false ]
		];

		return apply_filters( 'cbxwpbookmark_list_admin_sortable_columns', $sortable_columns );
	}//end get_sortable_columns

	/**
	 * Get sortable/order by keys
	 *
	 * @return mixed|null
	 */
	function get_sortable_keys() {
		$sortable_keys = [
			'id'           => 'logs.id',
			'object_id'    => 'logs.object_id',
			'object_type'  => 'logs.object_type',
			'user_id'      => 'logs.user_id',
			'cat_id'       => 'logs.cat_id',
			'created_date' => 'logs.created_date'
		];

		return apply_filters( 'cbxwpbookmark_list_admin_sortable_keys', $sortable_keys );
	}//end method get_sortable_keys

	/**
	 * Bulk action method
	 *
	 * @return array|mixed|void
	 */
	function get_bulk_actions() {
		$status_arr           = [];
		$status_arr['delete'] = esc_html__( 'Delete', 'cbxwpbookmark' );

		$bulk_actions = apply_filters( 'cbxwpbookmark_list_admin_bulk_action', $status_arr );

		return $bulk_actions;
	}//end get_bulk_actions

	/**
	 * Process bulk action
	 */
	function process_bulk_action() {
		// security check!
		if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
			//$nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
			$nonce  = CBXWPBookmarkHelper::filter_string_polyfill( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$action = 'bulk-' . $this->_args['plural'];

			if ( ! wp_verify_nonce( $nonce, $action ) ) {
				wp_die( 'Nope! Security check failed!' );
			}

		}

		$new_status = $this->current_action();

		if ( $new_status == - 1 ) {
			return;
		}


		//Detect when a bulk action is being triggered...
		if ( ! empty( $_REQUEST['cbxwpbookmarklist'] ) ) {
			global $wpdb;

			$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

			$results = isset( $_REQUEST['cbxwpbookmarklist'] ) ? wp_unslash( $_REQUEST['cbxwpbookmarklist'] ) : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			foreach ( $results as $id ) {

				$id = intval( $id );

				$single_bookmark = CBXWPBookmarkHelper::singleBookmark( $id );


				if ( 'delete' === $new_status ) {

					cbxwpbookmarks_delete_bookmark( $id, $single_bookmark['user_id'], $single_bookmark['object_id'], $single_bookmark['object_type'] );
				}
			}
		}

		return;
	}//end process_bulk_action


	/**
	 * Prepare the review log items
	 */
	function prepare_items() {
		global $wpdb; //This is used only if making any database queries
		//$settings = $this->settings;

		$user   = get_current_user_id();
		$screen = get_current_screen();

		$current_page = $this->get_pagenum();

		$option_name = $screen->get_option( 'per_page', 'option' ); //the core class name is WP_Screen

		$per_page = intval( get_user_meta( $user, $option_name, true ) );

		if ( $per_page == 0 ) {
			$per_page = intval( $screen->get_option( 'per_page', 'default' ) );
		}


		$columns  = $this->get_columns();
		$hidden   = get_hidden_columns( $this->screen );
		$sortable = $this->get_sortable_columns();


		$this->_column_headers = [ $columns, $hidden, $sortable ];


		$this->process_bulk_action();

		// phpcs:disable
		$search      = ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] != '' ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
		$id          = ( isset( $_REQUEST['id'] ) && $_REQUEST['id'] != 0 ) ? intval( $_REQUEST['id'] ) : 0;
		$object_id   = ( isset( $_REQUEST['object_id'] ) && $_REQUEST['object_id'] != 0 ) ? intval( $_REQUEST['object_id'] ) : 0;
		$object_type = ( isset( $_REQUEST['object_type'] ) && $_REQUEST['object_type'] != '' ) ? sanitize_text_field( $_REQUEST['object_type'] ) : '';
		$cat_id      = ( isset( $_REQUEST['cat_id'] ) && $_REQUEST['cat_id'] != 0 ) ? intval( $_REQUEST['cat_id'] ) : 0;
		$user_id     = ( isset( $_REQUEST['user_id'] ) && $_REQUEST['user_id'] != 0 ) ? intval( $_REQUEST['user_id'] ) : 0;
		$order       = ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] != '' ) ? sanitize_text_field( $_REQUEST['order'] ) : 'DESC';
		$order_by    = ( isset( $_REQUEST['orderby'] ) && $_REQUEST['orderby'] != '' ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'logs.id';
		// phpcs:enable

		$data = $this->getLogData( $search, $id, $object_id, $object_type, $cat_id, $user_id, $order_by, $order, $per_page, $current_page );

		$total_items = intval( $this->getLogDataCount( $search, $id, $object_id, $object_type, $cat_id, $user_id ) );

		$this->items = $data;

		/**
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args( [
			'total_items' => $total_items,                      //WE have to calculate the total number of items
			'per_page'    => $per_page,                         //WE have to determine how many items to show on a page
			'total_pages' => ceil( $total_items / $per_page )   //WE have to calculate the total number of pages
		] );

	}//end method prepare_items

	/**
	 * Get bookmark logs
	 *
	 * @param  string  $search
	 * @param  int  $id
	 * @param  int  $object_id
	 * @param  string  $object_type
	 * @param  int  $cat_id
	 * @param  int  $user_id
	 * @param  string  $order_by
	 * @param  string  $order
	 * @param  int  $per_page
	 * @param  int  $page
	 *
	 * @return array|null|object
	 */
	public function getLogData( $search = '', $id = 0, $object_id = 0, $object_type = '', $cat_id = 0, $user_id = 0, $order_by = 'logs.id', $order = 'DESC', $per_page = 20, $page = 1 ) {
		global $wpdb;
		$settings       = $this->settings;
		$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';
		$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';

		//prepare data
		$allowed_object_types = cbxwpbookmarks_allowed_object_type();
		if ( ! in_array( $object_type, $allowed_object_types ) ) {
			$object_type = '';
		}

		$sortable_keys = array_values( $this->get_sortable_keys() );

		if ( ! in_array( $order_by, $sortable_keys ) ) {
			$order_by = 'logs.id';
		}

		$order      = strtoupper( $order );
		$order_keys = cbxwpbookmarks_get_order_keys();

		if ( ! in_array( $order, $order_keys ) ) {
			$order = 'DESC';
		}
		//end prepare data


		$sql_select = "logs.*, cat.cat_name as cat_name";


		$sql_select = apply_filters( 'cbxwpbookmark_list_admin_select', $sql_select, $search, $id, $object_id, $object_type, $cat_id, $user_id, $order_by, $order, $per_page, $page );

		$join = $where_sql = '';

		$join .= " LEFT JOIN $category_table  as cat ON cat.id = logs.cat_id ";

		$join = apply_filters( 'cbxwpbookmark_list_admin_join', $join, $search, $id, $object_id, $object_type, $cat_id, $user_id, $order_by, $order, $per_page, $page );


		if ( $object_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.object_id=%d', intval( $object_id ) );
		}

		if ( $object_type !== '' ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.object_type=%s', $object_type );
		}

		if ( $cat_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.cat_id=%d', intval( $cat_id ) );
		}

		if ( $user_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.user_id=%d', intval( $user_id ) );
		}

		$where_sql = apply_filters( 'cbxwpbookmark_list_admin_where', $where_sql, $search, $id, $object_id, $object_type, $cat_id, $user_id, $order_by, $order, $per_page, $page );

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}


		$limit_sql = '';

		if ( $per_page != - 1 ) {
			$start_point = ( $page * $per_page ) - $per_page;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $per_page;
		}

		$sortingOrder = " ORDER BY $order_by $order ";

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		return $wpdb->get_results( "SELECT $sql_select FROM $bookmark_table as logs $join  WHERE  $where_sql $sortingOrder  $limit_sql", 'ARRAY_A' );
	}//end getLogData

	/**
	 * Bookmark total counter
	 *
	 * @param  string  $search
	 * @param  int  $id
	 * @param  int  $object_id
	 * @param  string  $object_type
	 * @param  int  $cat_id
	 * @param  int  $user_id
	 *
	 * @return null|string
	 */
	public function getLogDataCount( $search = '', $id = 0, $object_id = 0, $object_type = '', $cat_id = 0, $user_id = 0 ) {
		global $wpdb;
		$settings       = $this->settings;
		$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

		//prepare data
		$allowed_object_types = cbxwpbookmarks_allowed_object_type();
		if ( ! in_array( $object_type, $allowed_object_types ) ) {
			$object_type = '';
		}
		//end prepare data


		$sql_select = "SELECT COUNT(*) FROM $bookmark_table as logs";

		$join = $where_sql = '';

		$join = apply_filters( 'cbxwpbookmark_list_admin_join_total', $join, $search, $id, $object_id, $object_type, $cat_id, $user_id );


		if ( $object_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.object_id=%d', intval( $object_id ) );
		}

		if ( $object_type !== '' ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.object_type=%s', $object_type );
		}

		if ( $cat_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.cat_id=%d', intval( $cat_id ) );
		}

		if ( $user_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.user_id=%d', intval( $user_id ) );
		}

		$where_sql = apply_filters( 'cbxwpbookmark_list_admin_where_total', $where_sql, $search, $id, $object_type, $object_id, $user_id, $cat_id );

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}


		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		return $wpdb->get_var( "$sql_select $join  WHERE  $where_sql" );
	}//end getLogDataCount


	/**
	 * Generates content for a single row of the table
	 *
	 * @param  object  $item  The current item
	 *
	 * @since  3.1.0
	 * @access public
	 *
	 */
	public function single_row( $item ) {
		$row_class = 'cbxwpbookmark_list_row';
		$row_class = apply_filters( 'cbxwpbookmark_list_row_class', $row_class, $item );
		echo '<tr id="cbxwpbookmark_list_row_' . intval( $item['id'] ) . '" class="' . esc_attr( $row_class ) . '">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}//end single_row

	/**
	 * Message to be displayed when there are no items
	 *
	 * @since  3.1.0
	 * @access public
	 */
	public function no_items() {
		echo '<div class="notice notice-warning inline "><p>' . esc_html__( 'No bookmarks found. Please change your search criteria for better result.', 'cbxwpbookmark' ) . '</p></div>';
	}//end no_items

	/**
	 * Pagination
	 *
	 * @param  string  $which
	 */
	protected function pagination( $which ) {

		if ( empty( $this->_pagination_args ) ) {
			return;
		}

		$total_items     = $this->_pagination_args['total_items'];
		$total_pages     = $this->_pagination_args['total_pages'];
		$infinite_scroll = false;
		if ( isset( $this->_pagination_args['infinite_scroll'] ) ) {
			$infinite_scroll = $this->_pagination_args['infinite_scroll'];
		}

		if ( 'top' === $which && $total_pages > 1 ) {
			$this->screen->render_screen_reader_content( 'heading_pagination' );
		}

		/* translators: %s: Number of items. */
		$output = '<span class="displaying-num">' . sprintf( _n( '%s item', '%s items', $total_items, 'cbxwpbookmark' ), number_format_i18n( $total_items ) ) . '</span>';

		$current              = $this->get_pagenum();
		$removable_query_args = wp_removable_query_args();

		//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

		$current_url = remove_query_arg( $removable_query_args, $current_url );

		$page_links = [];

		$total_pages_before = '<span class="paging-input">';
		$total_pages_after  = '</span></span>';

		$disable_first = $disable_last = $disable_prev = $disable_next = false;

		if ( $current == 1 ) {
			$disable_first = true;
			$disable_prev  = true;
		}
		if ( $current == 2 ) {
			$disable_first = true;
		}
		if ( $current == $total_pages ) {
			$disable_last = true;
			$disable_next = true;
		}
		if ( $current == $total_pages - 1 ) {
			$disable_last = true;
		}

		$pagination_params = [];

		$search = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		//$logdate = ( isset( $_REQUEST['logdate'] ) && $_REQUEST['logdate'] != '' ) ? sanitize_text_field( $_REQUEST['logdate'] ) : '';

		if ( $search != '' ) {
			$pagination_params['s'] = $search;
		}

		/*if ($logdate != '') {
			$pagination_params['logdate'] = $logdate;
		}*/


		$pagination_params = apply_filters( 'cbxwpbookmark_pagination_log_params', $pagination_params );

		if ( $disable_first ) {
			$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>';
		} else {
			$page_links[] = sprintf( "<a class='first-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( remove_query_arg( 'paged', $current_url ) ),
				__( 'First page', 'cbxwpbookmark' ),
				'&laquo;'
			);
		}

		if ( $disable_prev ) {
			$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>';
		} else {
			$pagination_params['paged'] = max( 1, $current - 1 );

			$page_links[] = sprintf( "<a class='prev-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( $pagination_params, $current_url ) ),
				__( 'Previous page', 'cbxwpbookmark' ),
				'&lsaquo;'
			);
		}

		if ( 'bottom' === $which ) {
			$html_current_page  = $current;
			$total_pages_before = '<span class="screen-reader-text">' . __( 'Current Page', 'cbxwpbookmark' ) . '</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">';
		} else {
			$html_current_page = sprintf( "%s<input class='current-page' id='current-page-selector' type='text' name='paged' value='%s' size='%d' aria-describedby='table-paging' /><span class='tablenav-paging-text'>",
				'<label for="current-page-selector" class="screen-reader-text">' . __( 'Current Page', 'cbxwpbookmark' ) . '</label>',
				$current,
				strlen( $total_pages )
			);
		}
		$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );

		/* translators: 1. Current page 2. Total page */
		$page_links[] = $total_pages_before . sprintf( _x( '%1$s of %2$s', 'paging', 'cbxwpbookmark' ), $html_current_page, $html_total_pages ) . $total_pages_after;

		if ( $disable_next ) {
			$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>';
		} else {
			$pagination_params['paged'] = min( $total_pages, $current + 1 );

			$page_links[] = sprintf( "<a class='next-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( $pagination_params, $current_url ) ),
				__( 'Next page', 'cbxwpbookmark' ),
				'&rsaquo;'
			);
		}

		if ( $disable_last ) {
			$page_links[] = '<span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>';
		} else {
			$pagination_params['paged'] = $total_pages;

			$page_links[] = sprintf( "<a class='last-page' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
				esc_url( add_query_arg( $pagination_params, $current_url ) ),
				__( 'Last page', 'cbxwpbookmark' ),
				'&raquo;'
			);
		}

		$pagination_links_class = 'pagination-links';
		if ( ! empty( $infinite_scroll ) ) {
			$pagination_links_class = ' hide-if-js';
		}
		$output .= "\n<span class='$pagination_links_class'>" . join( "\n", $page_links ) . '</span>';

		if ( $total_pages ) {
			$page_class = $total_pages < 2 ? ' one-page' : '';
		} else {
			//$page_class = ' no-pages';
			$page_class = ' ';
		}
		$this->_pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";

		echo $this->_pagination; // phpcs:ignore
	}
}//end class CBXWPBookmark_List_Table