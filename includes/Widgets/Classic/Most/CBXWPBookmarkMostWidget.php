<?php
//namespace CBXWPBookmark\Widgets\Classic\Most;

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cbxbookmarkedmost_Widget
 */
class CBXWPBookmarkMostWidget extends WP_Widget {

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
	protected $widget_slug = 'cbxwpbookmarkedmost-widget';


	/**
	 * Constructor
	 *
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {
		parent::__construct(
			$this->get_widget_slug(), esc_html__( 'CBX Most Bookmarked Posts', "cbxwpbookmark" ), [
				'classname'   => 'cbxwpbookmark-mostlist-wrap cbxwpbookmark-mostlist-wrap-widget ' . $this->get_widget_slug() . '-class',
				'description' => esc_html__( 'This widget shows most bookmarked post from all user within specific time limit.', "cbxwpbookmark" )
			]
		);

		//Refreshing the widget's cached output with each new post
		add_action( 'save_post', [ $this, 'flush_widget_cache' ] );
		add_action( 'deleted_post', [ $this, 'flush_widget_cache' ] );
		//add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
	}//end constructor


	/**
	 * Return the widget slug.
	 *
	 * @return    Plugin slug variable.
	 * @since    1.0.0
	 *
	 */
	public function get_widget_slug() {
		return $this->widget_slug;
	}//end get_widget_slug


	/**
	 * Outputs the content of the widget.
	 *
	 * @param  array  $args
	 * @param  array  $instance
	 *
	 * @return int|void
	 */
	public function widget( $args, $instance ) {
		// Check if there is a cached output
		$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

		if ( ! is_array( $cache ) ) {
			$cache = [];
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			return print $cache[ $args['widget_id'] ]; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		// go on with your widget logic, put everything into a string and …

		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		// Title
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Most Bookmarked Post', 'cbxwpbookmark' ) : $instance['title'], $instance, $this->id_base );


		// Defining Title of Widget
		if ( $title ) {
			$widget_string .= $args['before_title'] . $title . $args['after_title'];
		} else {
			$widget_string .= $args['before_title'] . $args['after_title'];
		}

		wp_enqueue_style( 'cbxwpbookmarkpublic-css' );

		$instance['title'] = '';

		$attr = [];

		$type = $instance['type'];
		if ( is_array( $type ) ) {
			$type = array_filter( $type );
			$type = implode( ',', $type );
		} else {
			$type = '';
		}


		$attr['title']      = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$attr['order']      = isset( $instance['order'] ) ? strtoupper( esc_attr( $instance['order'] ) ) : 'DESC';
		$attr['orderby']    = isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : 'object_count';
		$attr['limit']      = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 10;
		$attr['type']       = esc_attr( $type );
		$attr['daytime']    = isset( $instance['daytime'] ) ? absint( $instance['daytime'] ) : 0;
		$attr['show_count'] = isset( $instance['show_count'] ) ? absint( $instance['show_count'] ) : 1;
		$attr['show_thumb'] = isset( $instance['show_thumb'] ) ? absint( $instance['show_thumb'] ) : 1;

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

		$attr = apply_filters( 'cbxwpbookmark_widget_shortcode_builder_attr', $attr, $instance, 'cbxwpbookmark-most' );

		$attr_html = '';

		foreach ( $attr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
		}


		$widget_string .= do_shortcode( '[cbxwpbookmark-most ' . $attr_html . ']' );


		$widget_string .= $after_widget;

		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

		print $widget_string; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}// end widget

	public function flush_widget_cache() {
		wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}//end flush_widget_cache

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param  array  $new_instance
	 * @param  array  $old_instance
	 *
	 * @return array|mixed
	 */
	public function update( $new_instance, $old_instance ) {
		$instance['title']   = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['daytime'] = isset( $new_instance['orderby'] ) ? absint( $new_instance['daytime'] ) : 0;
		$instance['orderby'] = isset( $new_instance['orderby'] ) ? sanitize_text_field( $new_instance['orderby'] ) : 'object_count'; //id, object_id, object_type
		$instance['order']   = isset( $new_instance['order'] ) ? sanitize_text_field( $new_instance['order'] ) : 'DESC';

		$type = isset( $new_instance['type'] ) ? wp_unslash( $new_instance['type'] ) : [];  //object type: post, page, custom any post type or custom object type  ->  can be introduced in future
		if ( is_string( $type ) ) {
			$type = explode( ',', $type );
		}

		$type             = array_filter( $type );
		$instance['type'] = $type;


		$instance['limit']      = isset( $new_instance['limit'] ) ? absint( $new_instance['limit'] ) : 10;
		$instance['show_count'] = isset( $new_instance['show_count'] ) ? absint( $new_instance['show_count'] ) : 1;
		$instance['show_thumb'] = isset( $new_instance['show_thumb'] ) ? absint( $new_instance['show_thumb'] ) : 1;

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
				'title'      => esc_html__( 'Most Bookmarked Post', 'cbxwpbookmark' ),
				'limit'      => 10,
				'daytime'    => '0',
				'orderby'    => 'object_count',
				//id, object_id, object_type, object_count
				'order'      => 'DESC',
				'type'       => [],
				// possible post, page, any custom post type or object type if we introduce later, have plan
				'show_count' => 1,
				'show_thumb' => 1
			]
		);

		$title      = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$limit      = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 10;
		$daytime    = isset( $instance['daytime'] ) ? absint( $instance['daytime'] ) : 0;
		$order_by   = isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : 'object_count';               //id, object_id, object_type, object_count
		$order      = isset( $instance['order'] ) ? strtoupper( esc_attr( $instance['order'] ) ) : 'DESC';             //desc, asc
		$type       = isset( $instance['type'] ) ? wp_unslash( $instance['type'] ) : [];                               //post, page, custom post types or any custom object type
		$show_count = isset( $instance['show_count'] ) ? absint( $instance['show_count'] ) : 1;
		$show_thumb = isset( $instance['show_thumb'] ) ? absint( $instance['show_thumb'] ) : 1;

		if ( is_string( $type ) ) {
			$type = explode( ',', $type );
		}

		$type = array_filter( $type );

		// Display the admin form
		include( plugin_dir_path( __FILE__ ) . 'views/admin.php' );
	}//end form
}//end class CBXWPBookmarkMostWidget