<?php

namespace Cbx\Bookmark\Models;

//use Cbx\Bookmark\Helpers\CBXWPBookmarkHelper;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class PostModel
 *
 * @since 1.0.0
 */
class PostModel extends Eloquent {
	public $timestamps = false;
	protected $guarded = [];
	protected $table = 'posts';

	public function __construct() {
		\CBXWPBookmarkHelper::load_orm();
	}
}//end Class PostModel