<?php

namespace CBXWPBookmark\Models;

use CBXWPBookmarkScoped\Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class UserModel
 *
 * @since 1.0.0
 */
class UserModel extends Eloquent {
	public $timestamps = false;
	protected $guarded = [];
	protected $table = 'users';

	protected $hidden = [ 'user_pass' ];

	public function __construct() {
		\CBXWPBookmarkHelper::load_orm();
	}

}//end Class UserModel