<?php
namespace Cbx\Bookmark\Models;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Illuminate\Database\Eloquent\Model as Eloquent;

class Migrations extends Eloquent {

	protected $table = 'cbxmigrations';

	protected $guarded = [];

	public $timestamps = false;
}