<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Bookmark category listing class in admin panel
 */
class CBXWPBookmark_Category_Table extends WP_List_Table {

	/**
	 * The current list of all branches.
	 *
	 * @since  3.1.0
	 * @access public
	 * @var array
	 */
	function __construct( $args = [] ) {

		//Set parent defaults
		parent::__construct( [
			'singular' => 'cbxwpbookmarkcat',     //singular name of the listed records
			'plural'   => 'cbxwpbookmarkcats',    //plural name of the listed records
			'ajax'     => false,                  //does this table support ajax?
			'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
		] );
	}//end constructor

	/**
	 * Callback for column 'id'
	 *
	 * @param  array  $item
	 *
	 * @return string
	 */
	function column_id( $item ) {
		return intval( $item['id'] ) . ' - <a href="' . esc_url( admin_url( 'admin.php?page=cbxwpbookmarkcats&view=edit&id=' . intval( $item['id'] ) ) ) . '" title="' . esc_html__( 'Edit Category', 'cbxwpbookmark' ) . '">' . esc_html__( 'Edit', 'cbxwpbookmark' ) . '</a>';
	}//end method column_id


	/**
	 * Callback for column 'cat_name'
	 *
	 * @param  array  $item
	 *
	 * @return string
	 */
	function column_cat_name( $item ) {
		return esc_attr( wp_unslash( $item['cat_name'] ) );
	}//end column_cat_name

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
			$user_html = '<a href="' . get_edit_user_link( $user_id ) . '" target="_blank" title="' . esc_attr__( 'Edit User', 'cbxwpbookmark' ) . '">' . $user_id . '</a>';
		}

		return $user_html;
	}//end column_user_id

	/**
	 * Callback for column 'privacy
	 *
	 * @param  array  $item
	 *
	 * @return string
	 */
	function column_privacy( $item ) {
		$privacy_arr = CBXWPBookmarkHelper::privacy_status_arr();
		$privacy     = intval( $item['privacy'] );
		if ( isset( $privacy_arr[ $privacy ] ) ) {
			return esc_attr( $privacy_arr[ $privacy ] );
		}

		return esc_attr( $privacy );
	}//end method column_privacy


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
	}//end method column_cb

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
				return $item[ $column_name ];
			case 'cat_name':
				return $item[ $column_name ];
			case 'user_id':
				return $item[ $column_name ];
			case 'privacy':
				return $item[ $column_name ];
			case 'created_date':
				return $item[ $column_name ];
			case 'modyfied_date':
				return $item[ $column_name ];
			default:
				//return print_r( $item, true ); //Show the whole array for troubleshooting purposes
				return apply_filters( 'cbxwpbookmark_category_admin_column_default', $item, $column_name );
		}
	}//end column_default

	function get_columns() {
		$columns = [
			'cb'            => '<input type="checkbox" />', //Render a checkbox instead of text
			'id'            => esc_html__( 'ID', 'cbxwpbookmark' ),
			'cat_name'      => esc_html__( 'Title', 'cbxwpbookmark' ),
			'user_id'       => esc_html__( 'User', 'cbxwpbookmark' ),
			'privacy'       => esc_html__( 'Privacy', 'cbxwpbookmark' ),
			'created_date'  => esc_html__( 'Created', 'cbxwpbookmark' ),
			'modyfied_date' => esc_html__( 'Modified', 'cbxwpbookmark' )
		];

		return apply_filters( 'cbxwpbookmark_category_admin_columns', $columns );
	}//end get_columns

	/**
	 * Get sortable columns
	 *
	 * @return array|mixed|null
	 */
	function get_sortable_columns() {
		$sortable_columns = [
			'id'            => [ 'logs.id', false ], //true means it's already sorted
			'cat_name'      => [ 'logs.cat_name', false ],
			'user_id'       => [ 'logs.user_id', false ],
			'privacy'       => [ 'logs.privacy', false ],
			'created_date'  => [ 'logs.created_date', false ],
			'modyfied_date' => [ 'logs.modyfied_date', false ]
		];

		return apply_filters( 'cbxwpbookmark_category_admin_sortable_columns', $sortable_columns );
	}//end get_sortable_columns

	/**
	 * Get sortable/order by keys
	 *
	 * @return mixed|null
	 */
	function get_sortable_keys() {
		$sortable_keys = [
			'id'            => 'logs.id',
			'cat_name'      => 'logs.cat_name',
			'user_id'       => 'logs.user_id',
			'privacy'       => 'logs.privacy',
			'created_date'  => 'logs.created_date',
			'modyfied_date' => 'logs.modyfied_date'
		];

		return apply_filters( 'cbxwpbookmark_category_admin_sortable_keys', $sortable_keys );
	}//end get_sortable_keys


	/**
	 * Bulk action method
	 *
	 * @return array|mixed|void
	 */
	function get_bulk_actions() {
		$status_arr           = [];
		$status_arr['delete'] = esc_html__( 'Delete', 'cbxwpbookmark' );

		return apply_filters( 'cbxwpbookmark_category_admin_bulk_action', $status_arr );
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
		if ( ! empty( $_REQUEST['cbxwpbookmarkcat'] ) ) {
			global $wpdb;

			$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';
			$bookmark_table = $wpdb->prefix . 'cbxwpbookmark';

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$results = isset( $_REQUEST['cbxwpbookmarkcat'] ) ? wp_unslash( $_REQUEST['cbxwpbookmarkcat'] ) : [];
			foreach ( $results as $id ) {

				$id              = absint( $id );
				$single_category = CBXWPBookmarkHelper::singleCategory( $id );


				if ( 'delete' === $new_status ) {
					do_action( 'cbxbookmark_category_deleted_before', $id, $single_category['user_id'] );

					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $category_table WHERE id=%d", $id ) );


					if ( $delete_status !== false ) {
						do_action( 'cbxbookmark_category_deleted', $id, $single_category['user_id'] );

						// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
						$bookmarks_by_category = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $bookmark_table WHERE cat_id = %d", $id ), ARRAY_A );

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
					}
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
		$search = ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] != '' ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
		$id     = ( isset( $_REQUEST['id'] ) && $_REQUEST['id'] != 0 ) ? intval( $_REQUEST['id'] ) : 0;
		//$cat_name = ( isset( $_REQUEST['cat_name'] ) && $_REQUEST['cat_name'] != '' ) ? esc_attr( $_REQUEST['cat_name'] ) : '';
		$user_id = ( isset( $_REQUEST['user_id'] ) && $_REQUEST['user_id'] != 0 ) ? intval( $_REQUEST['user_id'] ) : 0;
		$privacy = ( isset( $_REQUEST['privacy'] ) && $_REQUEST['privacy'] != 0 ) ? intval( $_REQUEST['privacy'] ) : '';

		$order    = ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] != '' ) ? sanitize_text_field( $_REQUEST['order'] ) : 'DESC';
		$order_by = ( isset( $_REQUEST['orderby'] ) && $_REQUEST['orderby'] != '' ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'logs.id';
		// phpcs:enable

		//take care the order and order by
		//order by
		$sortable_keys = array_values( $this->get_sortable_keys() );

		if ( ! in_array( $order_by, $sortable_keys ) ) {
			$order_by = 'logs.id';
		}

		//order
		$order      = strtoupper( $order );
		$order_keys = cbxwpbookmarks_get_order_keys();

		if ( ! in_array( $order, $order_keys ) ) {
			$order = 'DESC';
		}
		//end take care the order and order by


		$data = $this->getLogData( $search, $id, $user_id, $privacy, $order_by, $order, $per_page, $current_page );

		$total_items = intval( $this->getLogDataCount( $search, $id, $user_id, $privacy ) );

		$this->items = $data;

		/**
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args( [
			'total_items' => $total_items,                      //WE have to calculate the total number of items
			'per_page'    => $per_page,                         //WE have to determine how many items to show on a page
			'total_pages' => ceil( $total_items / $per_page )   //WE have to calculate the total number of pages
		] );

	}

	/**
	 * Get Bookmark category data
	 *
	 * @param  string  $search
	 * @param  int  $id
	 * @param  int  $user_id
	 * @param  string  $privacy
	 * @param  string  $order_by
	 * @param  string  $order
	 * @param  int  $per_page
	 * @param  int  $page
	 *
	 * @return array|null|object
	 */
	public function getLogData( $search = '', $id = 0, $user_id = 0, $privacy = '', $order_by = 'logs.id', $order = 'DESC', $per_page = 20, $page = 1 ) {
		global $wpdb;

		$sortable_keys = array_values( $this->get_sortable_keys() );

		if ( ! in_array( $order_by, $sortable_keys ) ) {
			$order_by = 'logs.id';
		}

		$order      = strtoupper( $order );
		$order_keys = cbxwpbookmarks_get_order_keys();

		if ( ! in_array( $order, $order_keys ) ) {
			$order = 'DESC';
		}

		$per_page = absint( $per_page );
		$page     = absint( $page );

		$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';

		$sql_select = "logs.*";

		$sql_select = apply_filters( 'cbxwpbookmark_category_admin_select', $sql_select, $search, $id, $user_id, $privacy, $order_by, $order, $per_page, $page );

		$join = $where_sql = '';

		$join = apply_filters( 'cbxwpbookmark_category_admin_join', $join, $search, $id, $user_id, $privacy, $order_by, $order, $per_page, $page );

		if ( $search != '' ) {
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}

			$wild = '%';
			$like = $wild . $wpdb->esc_like( $search ) . $wild;

			$where_sql .= $wpdb->prepare( " logs.cat_name LIKE %s ", $like );
		}


		if ( $id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.id=%d', intval( $id ) );
		}


		if ( $user_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.user_id=%d', intval( $user_id ) );
		}

		if ( $privacy !== '' ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.privacy=%s', intval( $privacy ) );
		}

		$where_sql = apply_filters( 'cbxwpbookmark_category_admin_where', $where_sql, $search, $id, $user_id, $privacy, $order_by, $order, $per_page, $page );

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		$start_point = ( $page * $per_page ) - $per_page;
		$limit_sql   = "LIMIT";
		$limit_sql   .= ' ' . $start_point . ',';
		$limit_sql   .= ' ' . $per_page;

		$sortingOrder = " ORDER BY $order_by $order ";

		// phpcs:ignore
		return $wpdb->get_results( "SELECT $sql_select FROM $category_table as logs $join  WHERE  $where_sql $sortingOrder  $limit_sql", 'ARRAY_A' );
	}//end getLogData


	/**
	 * Total category counter
	 *
	 * @param  string  $search
	 * @param  int  $id
	 * @param  int  $user_id
	 * @param  string  $privacy
	 *
	 * @return null|string
	 */
	public function getLogDataCount( $search = '', $id = 0, $user_id = 0, $privacy = '' ) {

		global $wpdb;

		$category_table = $wpdb->prefix . 'cbxwpbookmarkcat';

		$sql_select = "SELECT COUNT(*) FROM $category_table as logs";

		$join = $where_sql = '';

		$join = apply_filters( 'cbxwpbookmark_category_admin_join_total', $join, $search, $id, $user_id, $privacy );

		if ( $search != '' ) {
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}


			$wild = '%';
			$like = $wild . $wpdb->esc_like( $search ) . $wild;


			$where_sql .= $wpdb->prepare( " logs.cat_name LIKE %s ", $like );
		}


		if ( $id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.id=%d', intval( $id ) );
		}


		if ( $user_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.user_id=%d', intval( $user_id ) );
		}

		if ( $privacy !== '' ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'logs.privacy=%s', intval( $privacy ) );
		}

		$where_sql = apply_filters( 'cbxwpbookmark_category_admin_where_total', $where_sql, $search, $id, $user_id, $privacy );

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
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
		$row_class = 'cbxwpbookmark_category_row';
		$row_class = apply_filters( 'cbxwpbookmark_category_row_class', $row_class, $item );
		echo '<tr id="cbxwpbookmark_category_row_' . esc_attr( $item['id'] ) . '" class="' . esc_attr( $row_class ) . '">';
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
		echo '<div class="notice notice-warning inline "><p>' . esc_html__( 'No category found. Please change your search criteria for better result.', 'cbxwpbookmark' ) . '</p></div>';
	}//end no_items
}//end class CBXWPBookmark_Category_Table