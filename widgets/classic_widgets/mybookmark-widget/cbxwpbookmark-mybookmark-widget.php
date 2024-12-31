<?php

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CBX Bookmark - My Bookmark widget class
 *
 * Class CBXWPBookmarkMyBookmark_Widget
 */
class CBXWPBookmarkMyBookmark_Widget extends WP_Widget {
	/**
	 *
	 * Unique identifier for your widget.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * widget file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $widget_slug = 'cbxwpbookmark-widget';

	/**
	 * Constructor
	 *
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {
		parent::__construct(
			$this->get_widget_slug(), esc_html__( 'CBX My Bookmarked Posts', "cbxwpbookmark" ), [
				'classname'   => 'cbxwpbookmark-mylist-wrap cbxwpbookmark-mylist-wrap-widget ' . esc_attr( $this->get_widget_slug() ) . '-class',
				'description' => esc_html__( 'This widget shows bookmarked posts from a user', "cbxwpbookmark" )
			]
		);
	}// end constructor

	/**
	 * Return the widget slug.
	 *
	 * @return    Plugin slug variable.
	 * @since    1.0.0
	 *
	 */
	public function get_widget_slug() {
		return $this->widget_slug;
	}//end method get_widget_slug

	/* -------------------------------------------------- */
	/* Widget API Functions
	  /*-------------------------------------------------- */

	/**
	 * Outputs the content of the widget.
	 *
	 * @param  array args  The array of form elements
	 * @param  array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		extract( $args, EXTR_SKIP );


		$widget_string = $before_widget;


		$default_title           = esc_html__( 'My Bookmarks', 'cbxwpbookmark' );
		$instance['honorauthor'] = isset( $instance['honorauthor'] ) ? absint( $instance['honorauthor'] ) : 0;

		if ( is_author() && absint( $instance['honorauthor'] ) ) {
			$curauth            = ( get_query_var( 'author_name' ) ) ? get_user_by( 'slug', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );
			$instance['userid'] = absint( $curauth->ID );
			$title              = esc_html__( 'Author\'s Bookmarks', 'cbxwpbookmark' );
		} else {
			$current_user_id    = absint( get_current_user_id() );
			$instance['userid'] = $current_user_id;
			$title              = apply_filters( 'widget_title', empty( $instance['title'] ) ? $default_title : $instance['title'], $instance, $this->id_base );
		}

		if ( $title !== '' ) {
			$widget_string .= $args['before_title'] . $title . $args['after_title'];
		} else {
			$widget_string .= $args['before_title'] . $args['after_title'];
		}

		wp_enqueue_style( 'cbxwpbookmarkpublic-css' );


		//$instance['loadmore'] = 0;                                                                    //disable pagination in widget
		//$readmore             = isset( $instance['readmore'] ) ? intval( $instance['readmore'] ) : 0; //if show view more link in widget


		//$settings_api      = new CBXWPBookmark_Settings_API();
		//$mybookmark_pageid = absint( $settings_api->get_option( 'mybookmark_pageid', 'cbxwpbookmark_basics', 0 ) );

		$instance['title'] = ''; // we will send our shortcode's title attribute blank so that in widget it doesn't show extra title

		$attr = [];

		$type = isset( $instance['type'] ) ? $instance['type'] : [];

		if ( is_array( $type ) ) {
			$type = array_filter( $type );
			$type = implode( ',', $type );
		} else {
			$type = '';
		}


		$attr['title']          = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$attr['order']          = isset( $instance['order'] ) ? strtoupper( esc_attr( $instance['order'] ) ) : 'DESC';
		$attr['orderby']        = isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : 'id';
		$attr['limit']          = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 10;
		$attr['type']           = esc_attr( $type );
		$attr['catid']          = isset( $instance['catid'] ) ? esc_attr( $instance['catid'] ) : '';
		$attr['loadmore']       = isset( $instance['loadmore'] ) ? absint( $instance['loadmore'] ) : 1;
		$attr['cattitle']       = isset( $instance['cattitle'] ) ? absint( $instance['cattitle'] ) : 1;
		$attr['catcount']       = isset( $instance['catcount'] ) ? absint( $instance['catcount'] ) : 1;
		$attr['allowdelete']    = isset( $instance['allowdelete'] ) ? absint( $instance['allowdelete'] ) : 0;
		$attr['allowdeleteall'] = isset( $instance['allowdeleteall'] ) ? absint( $instance['allowdeleteall'] ) : 0;
		$attr['showshareurl']   = isset( $instance['showshareurl'] ) ? absint( $instance['showshareurl'] ) : 1;
		$attr['base_url']       = isset( $instance['base_url'] ) ? esc_url( $instance['base_url'] ) : cbxwpbookmarks_mybookmark_page_url();

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

		$attr = apply_filters( 'cbxwpbookmark_widget_shortcode_builder_attr', $attr, $instance, 'cbxwpbookmark' );


		$attr_html = '';

		foreach ( $attr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
		}

		$widget_string .= do_shortcode( '[cbxwpbookmark ' . $attr_html . ']' );
		$widget_string .= $after_widget;

		print $widget_string; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}//end widget


	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param  array new_instance The new instance of values to be generated via the update.
	 * @param  array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']          = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['order']          = isset( $new_instance['order'] ) ? sanitize_text_field( $new_instance['order'] ) : 'DESC';
		$instance['orderby']        = isset( $new_instance['orderby'] ) ? sanitize_text_field( $new_instance['orderby'] ) : 'id';
		$instance['limit']          = isset( $new_instance['limit'] ) ? absint( $new_instance['limit'] ) : 10;
		$instance['loadmore']       = isset( $new_instance['loadmore'] ) ? absint( $new_instance['loadmore'] ) : 1;
		$instance['cattitle']       = isset( $new_instance['cattitle'] ) ? absint( $new_instance['cattitle'] ) : 1;
		$instance['catcount']       = isset( $new_instance['catcount'] ) ? absint( $new_instance['catcount'] ) : 1;
		$instance['allowdelete']    = isset( $new_instance['allowdelete'] ) ? absint( $new_instance['allowdelete'] ) : 0;
		$instance['allowdeleteall'] = isset( $new_instance['allowdeleteall'] ) ? absint( $new_instance['allowdeleteall'] ) : 0;
		$instance['showshareurl']   = isset( $new_instance['showshareurl'] ) ? absint( $new_instance['showshareurl'] ) : 1;
		$instance['honorauthor']    = isset( $new_instance['honorauthor'] ) ? absint( $new_instance['honorauthor'] ) : 0;                                          //extra in widget
		$instance['base_url']       = isset( $new_instance['base_url'] ) ? sanitize_text_field( $new_instance['base_url'] ) : cbxwpbookmarks_mybookmark_page_url();//extra in widget

		$type = isset( $new_instance['type'] ) ? wp_unslash( $new_instance['type'] ) : [];  //object type: post, page, custom any post type or custom object type  ->  can be introduced in future
		if ( is_string( $type ) ) {
			$type = explode( ',', $type );
		}

		$type             = array_filter( $type );
		$instance['type'] = $type;

		return $instance;
	}//end update

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param  array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			[
				'title'          => esc_html__( 'My Bookmarks', 'cbxwpbookmark' ),
				'order'          => 'DESC',
				'orderby'        => 'id', //id, object_id, object_type
				'limit'          => 10,
				'type'           => [], //object type, eg, post, page, any custom post type
				'catid'          => '',
				'loadmore'       => 0,
				'cattitle'       => 1,  //show category title,
				'catcount'       => 1,  //show item count per category
				'allowdelete'    => 0,
				'allowdeleteall' => 0,
				'showshareurl'   => 1,
				'honorauthor'    => 0,                                   //extra in widget to work with author page
				'base_url'       => cbxwpbookmarks_mybookmark_page_url() //extra in widget to work with author page

			]
		);

		$title          = isset( $instance['title'] ) ? $instance['title'] : '';
		$order          = isset( $instance['order'] ) ? strtoupper( esc_attr( $instance['order'] ) ) : 'DESC';   //desc, asc
		$order_by       = isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : 'id';               //id, object_id, object_type
		$limit          = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 10;
		$catid          = isset( $instance['catid'] ) ? esc_attr( $instance['catid'] ) : '';
		$loadmore       = isset( $instance['loadmore'] ) ? absint( $instance['loadmore'] ) : 1;
		$cattitle       = isset( $instance['cattitle'] ) ? absint( $instance['cattitle'] ) : 1;
		$catcount       = isset( $instance['catcount'] ) ? absint( $instance['catcount'] ) : 1;
		$allowdelete    = isset( $instance['allowdelete'] ) ? absint( $instance['allowdelete'] ) : 0;
		$allowdeleteall = isset( $instance['allowdeleteall'] ) ? absint( $instance['allowdeleteall'] ) : 0;
		$showshareurl   = isset( $instance['showshareurl'] ) ? absint( $instance['showshareurl'] ) : 1;
		$honorauthor    = isset( $instance['honorauthor'] ) ? absint( $instance['honorauthor'] ) : 0;                                //extra in widget to work with author page
		$base_url       = isset( $instance['base_url'] ) ? esc_attr( $instance['base_url'] ) : cbxwpbookmarks_mybookmark_page_url(); //extra in widget to work with author page


		$type = isset( $instance['type'] ) ? wp_unslash( $instance['type'] ) : []; //object_type
		if ( is_string( $type ) ) {
			$type = explode( ',', $type );
		}

		$type = array_filter( $type );

		// Display the admin form
		include( plugin_dir_path( __FILE__ ) . 'views/admin.php' );
	}//end form
}//end CBXWPBookmarkMyBookmark_Widget