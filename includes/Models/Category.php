<?php
namespace CBXWPBookmark\Models;

use CBXWPBookmarkScoped\Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Category
 *
 * @since 1.0.0
 */
class Category extends Eloquent {
	public $timestamps = false;
	protected $guarded = [];
	protected $table = 'cbxwpbookmarkcat';

	/**
	 * @var string[]
	 */
	protected $appends = [	
		'formatted_created_date',
		'privacy_text',
		'formatted_updated_date',
	];

	/**
	 * delete the category
	 *
	 * @return bool|null
	 */
	public function delete() {
		$category = $this->toArray();
		do_action( 'cbxbookmark_category_deleted_before', $this->id, $category['user_id'] );

		$delete = parent::delete();
		if ( $delete ) {
			do_action( 'cbxbookmark_category_deleted', $this->id, $category['user_id'] );
		} else {
			do_action( 'cbxwpbookmark_delete_failed', $this->id, $category['user_id'] );
		}

		return $delete;
	}//end method delete

	/**
	 * Relation between users table
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo( UserModel::class, "user_id", "ID" );
	}

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

	/**
	 * get formatted update date
	 *
	 * @return string
	 */
	public function getFormattedUpdatedDateAttribute() {
		if ( ! isset( $this->attributes['id'] ) ) {
			return '';
		}
		if ( ! isset( $this->attributes['modyfied_date'] ) ) {
			return '';
		}

		$format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

		return date_i18n( $format, strtotime( $this->attributes['modyfied_date'] ) );
	}//end method getFormattedCreatedDateAttribute

	/**
	 * get privacy text
	 *
	 * @return string
	 */
	public function getPrivacyTextAttribute() {
		if ( ! isset( $this->attributes['id'] ) ) {
			return '';
		}

		if ( ! isset( $this->attributes['privacy'] ) ) {
			return '';
		}

		return $this->attributes['privacy'] == 1 ? esc_html__('Public' , 'cbxwpbookmark') : esc_html__('Private' , 'cbxwpbookmark');
	}//end method getPrivacyTextAttribute
}//end Class Category