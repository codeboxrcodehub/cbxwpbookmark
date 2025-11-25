<?php
namespace CBXWPBookmark\Models;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//use CBXWPBookmark\Helpers\CBXWPBookmarkHelper;
use CBXWPBookmark\CBXWPBookmarkSettings;
use CBXWPBookmarkScoped\Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Bookmark
 *
 * @since 1.0.0
 */
class Bookmark extends Eloquent {

	protected $table = 'cbxwpbookmark';

	protected $guarded = [];

	public $timestamps = false;

	/**
	 * @var string[]
	 */
	protected $appends = [	
		'permalink', 
		'object_link',
		'user_link',
		'formatted_created_date',
		// 'formatted_mod_date',
	];

	/**
	 * delete the bookmark
	 *
	 * @return bool|null
	 */
	public function delete() {
		$bookmark = $this->toArray();

		$bookmark_id = absint( $this->id );
		$user_id     = absint( $bookmark['user_id'] );
		$object_id   = absint( $bookmark['object_id'] );
		$object_type = $bookmark['object_type'];
		$category_id = absint( $bookmark['cat_id'] );

		do_action( 'cbxbookmark_bookmark_removed_before', $bookmark_id, $user_id, $object_id, $object_type, $category_id );

		$delete = parent::delete();
		if ( $delete ) {
			do_action( 'cbxbookmark_bookmark_removed', $bookmark_id, $user_id, $object_id, $object_type, $category_id );
		} else {
			do_action( 'cbxwpbookmark_delete_failed', $bookmark_id, $user_id, $object_id, $object_type, $category_id );
		}

		return $delete;
	}//end method delete

	/**
	 * Relation between posts table
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function post() {
		return $this->belongsTo( PostModel::class, "object_id", "ID" );
	}

	/**
	 * Relation between users table
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo( UserModel::class, "user_id", "ID" );
	}

	/**
	 * Relation between category table
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function category() {
		return $this->belongsTo( Category::class, "cat_id", "id" );
	}

	/**
	 * get post edit link
	 *
	 */
	public function getObjectLinkAttribute() {
		if ( ! isset( $this->attributes['object_id'] ) ) {
			return '';
		}

		$post_id     = $object_id = intval( $this->attributes['object_id'] );
		$object_type = esc_attr( $this->attributes['object_type'] );
		$settings     = new CBXWPBookmarkSettings();;
		$enable_buddypress_bookmark = intval( $settings->get_field( 'enable_buddypress_bookmark', 'cbxwpbookmark_proaddon', 0 ) );

		$object_types = \CBXWPBookmarkHelper::object_types( true ); //get plain post type as array

		$edit_link = '';

		if ( in_array( $object_type, $object_types ) ) {
			$post_title = wp_strip_all_tags( get_the_title( intval( $post_id ) ) );
			$post_title = ( $post_title == '' ) ? esc_html__( 'Untitled article', 'cbxwpbookmark' ) : $post_title;
			$edit_link  = '<a target="_blank" href="' . get_permalink( $post_id ) . '">' . esc_html( $post_title ) . '</a>';

			// $edit_url = esc_url( get_edit_post_link( $post_id ) );
			// if ( ! is_null( $edit_url ) ) {
			// 	$edit_link .= ' - <a target="_blank" href="' . $edit_url . '" target="_blank" title="' . esc_html__( 'Edit Post', 'cbxwpbookmark' ) . '">' . $post_id . '</a>';
			// }

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
	}

	/**
	 * get post edit link
	 *
	 */
	public function getUserLinkAttribute() {
		if ( ! isset( $this->attributes['user_id'] ) ) {
			return '';
		}

		$user_id = absint( $this->attributes['user_id'] );

		$user_html = $user_id;

		if ( current_user_can( 'edit_user', $user_id ) ) {
			$user_html = '<a href="' . get_edit_user_link( $user_id ) . '" target="_blank" title="' . esc_html__( 'Edit User', 'cbxwpbookmark' ) . '">' . $user_id . '</a>';
		}

		return $user_html;
	}

	/**
	 * get permalink
	 *
	 */
	public function getPermalinkAttribute() {
		if ( ! isset( $this->attributes['object_id'] ) ) {
			return '';
		}

		return get_permalink( $this->attributes['object_id'] );
	}//end method getPermalinkAttribute

	/**
	 * get formatted create date
	 *
	 * @return string
	 */
	public function getFormattedCreatedDateAttribute() {
		if ( ! isset( $this->attributes['id'] ) ) {
			return '';
		}
		if ( ! isset( $this->attributes['created_date'] ) ) {
			return '';
		}

		$format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

		return date_i18n( $format, strtotime( $this->attributes['created_date'] ) );
	}//end method getFormattedCreatedDateAttribute
}//end class Event